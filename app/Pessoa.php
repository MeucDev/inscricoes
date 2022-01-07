<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTime;
use App\PagSeguroIntegracao;

class Pessoa extends Model
{
    public $timestamps = false;

    public function conjuge()
    {
        return $this->belongsTo('App\Pessoa', 'conjuge_id');
    }

    public function dependentes()
    {
        return $this->hasMany('App\Pessoa', 'responsavel_id', 'id');
    }

    public static function getIdade($data){
        if (strpos($data, '/') !== false){
            return DateTime::createFromFormat('d/m/Y', $data)
                 ->diff(new DateTime('now'))
                 ->y;
        }else{
            return DateTime::createFromFormat('Y-m-d', $data)
                 ->diff(new DateTime('now'))
                 ->y;
        }
    }

    public function ajustarDados(){
        if ($this->nascimento)
            $this->nascimento = DateTime::createFromFormat('Y-m-d', $this->nascimento)->format('d/m/Y');
        if ($this->casamento)
            $this->casamento = DateTime::createFromFormat('Y-m-d', $this->casamento)->format('d/m/Y');
        if ($this->alojamento == "LAR")
            $this->refeicao = "LAR";
    }

    public function toUI($evento){

        if ($this->conjuge)
            $this->dependentes->prepend($this->conjuge);

        $this->ajustarDados();
        $this->valores = $this->getMeusValores($evento);
        
        foreach ($this->dependentes as $dependente) {
            $dependente->ajustarDados();
            $dependente->valores = $dependente->getMeusValores($evento);
        }

        $result = (object) $this->toArray();

        $result->dependentes = array_values($this->dependentes->reject(function($item) {
            return $item->inativo == 1;
        })->toArray());

        return $result;
    }

    public function getMeuValor($evento){
        return Pessoa::getValor($this, $evento);
    }

    public function getMeusValores($evento){
        return Pessoa::getValores($this, $evento);
    }    

    public static function getValor($pessoa, $evento){
        $valorInscricao = Pessoa::getValorInscricao($pessoa, $evento);
        $valorRefeicao = Pessoa::getValorRefeicao($pessoa, $evento);
        $valorAlojamento = Pessoa::getValorAlojamento($pessoa, $evento);
        return $valorInscricao + $valorRefeicao + $valorAlojamento;
    }

    public static function getValores($pessoa, $evento){
        $codigos = [];
        array_push($codigos, $pessoa->alojamento);
        array_push($codigos, $pessoa->refeicao);
        $valoresBoleto = Valor::getValoresCobrarBoleto($codigos, $evento);
        $boleto = 0;
        foreach($valoresBoleto as &$valorBoleto) {
            $boleto += floatval(Valor::getValor($valorBoleto->codigo, $valorBoleto->evento_id, $pessoa));
        }

        $valores = (object)[];
        $valores->inscricao = Pessoa::getValorInscricao($pessoa, $evento);
        $valores->alojamento = Pessoa::getValorAlojamento($pessoa, $evento);
        $valores->refeicao = Pessoa::getValorRefeicao($pessoa, $evento);
        $valores->desconto = intval(Desconto::getDesconto($pessoa));
        $valores->boleto = $valores->inscricao + $boleto;
        $valores->total = $valores->inscricao + $valores->alojamento + $valores->refeicao;
        
        return $valores;
    }

    public static function getValorInscricaoComDesconto($pessoa, $valorInscricao){
        $percDesconto = Desconto::getDesconto($pessoa);
        $result = $valorInscricao;
        if ($percDesconto > 0){
            $result = ($percDesconto * $valorInscricao) / 100;
        }

        return $result;
    }

    public static function getValorInscricao($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorInscricao = $pessoa->TIPO == 'R' ? Valor::getValor("NORMAL", $evento, $pessoa) : 0;

        $result = Pessoa::getValorInscricaoComDesconto($pessoa, $valorInscricao);
        return $result;
    }

    public static function getValorRefeicao($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorRefeicao = Valor::getValor($pessoa->refeicao, $evento, $pessoa);
        return $valorRefeicao;
    }
    
    public static function getValorAlojamento($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorAlojamento = Valor::getValor($pessoa->alojamento, $evento, $pessoa);
        return $valorAlojamento;
    }

    public function populate($dados){
        $nascimento = str_replace('/', '-', $dados->nascimento);
        $casamento = str_replace('/', '-', $dados->casamento);
        
        $this->TIPO = $dados->TIPO;
        $this->alojamento = $dados->alojamento;

        //todo: a equipe deve ser atribuida conforme
        $this->equipeRefeicao = $dados->equipeRefeicao;
        $this->nascimento = date('Y-m-d', strtotime($nascimento));
        if ($dados->casamento)
            $this->casamento = date('Y-m-d', strtotime($casamento));
        $this->idade = Pessoa::getIdade($dados->nascimento);
        $this->nome = $dados->nome;
        $this->nomecracha = $dados->nomecracha;
        $this->presencaConfirmada = 0;
        $this->refeicao = $dados->refeicao;
        $this->bairro = $dados->bairro;
        $this->cep = $dados->cep;
        $this->cidade = $dados->cidade;
        $this->email = $dados->email;
        $this->endereco = $dados->endereco;
        $this->telefone = $dados->telefone;
        $this->uf = strtoupper($dados->uf);
        $this->cpf = $dados->cpf;
        $this->nroend = $dados->nroend;
        $this->sexo = $dados->sexo;
        $this->inativo = 0;
    }

    public static function atualizarCadastros($dados){
        $pessoa = Pessoa::where('cpf', $dados->cpf)->first();

        if (!$pessoa)
            $pessoa = new Pessoa;

        $pessoa->populate($dados);

        $dadosDependentes = collect($dados->dependentes);

        $dadosConjuge = $dadosDependentes->first(function($item) {
            $d = (object) $item; 
            return $d->TIPO == "C";
        });

        Pessoa::atualizaConjuge($pessoa, $dadosConjuge);
        
        $pessoa->save();

        Pessoa::atualizaDependentes($pessoa, $dadosDependentes);

        $pessoa = Pessoa::find($pessoa->id);
        
        return $pessoa;
    }
    public static function atualizaConjuge($pessoa, $dadosConjuge){
        if ($dadosConjuge){
            $dadosConjuge = (object)$dadosConjuge;
            if ($pessoa->conjuge){
                $conjuge = $pessoa->conjuge;
                $conjuge->populate($dadosConjuge);
                $conjuge->save();
            }
            else{
                $conjuge = new Pessoa;
                $conjuge->populate($dadosConjuge);
                $conjuge->save();
                $pessoa->conjuge()->associate($conjuge);
            }
            $pessoa->casamento = $conjuge->casamento;
        }else{
            $pessoa->conjuge()->associate(null);
        }
    }

    public static function atualizaDependentes($pessoa, $dadosDependentes){
        foreach ($dadosDependentes as $dadosDependente) {
            $dadosDependente = (object) $dadosDependente;
            if ($dadosDependente->TIPO == "C") 
                continue;

            $dependente = null;

            $dependente = $pessoa->dependentes->first(function($item) use ($dadosDependente) {
                return ($item->id == $dadosDependente->id);
            });

            if (!$dependente){
                $dependente = $pessoa->dependentes->first(function($item) use ($dadosDependente) {
                    return (strcasecmp(trim($item->nome), trim($dadosDependente->nome)) == 0);
                });
            }

            if ($dependente){
                $dependente->populate($dadosDependente);
                $dependente->save();
            }
            else
            {
                $dependente = new Pessoa;
                $dependente->populate($dadosDependente);
                $pessoa->dependentes()->save($dependente);
            }
        }

        foreach ($pessoa->dependentes as $dependente) {
            if (!$dadosDependentes->contains('nome', $dependente->nome)){
                $dependente->inativo = true;
                $dependente->save();
            }
        }
    }
    
}
