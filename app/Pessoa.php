<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \DateTime;

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

    public function toUI($evento){
        if ($this->nascimento)
            $this->nascimento = DateTime::createFromFormat('Y-m-d', $this->nascimento)->format('d/m/Y');

        if ($this->conjuge)
            $this->dependentes->prepend($this->conjuge);

        $this->valores = $this->getMeusValores($evento);
        
        foreach ($this->dependentes as $dependente) {
            $dependente->valores = $dependente->getMeusValores($evento);
            if ($dependente->nascimento)
                $dependente->nascimento = DateTime::createFromFormat('Y-m-d', $dependente->nascimento)->format('d/m/Y');
        }

        $inscricao = Inscricao::where("pessoa_id", $this->id)
            ->where("evento_id", $evento)
            ->first();
            
        if ($inscricao){
            $this->inscricaoPaga = $inscricao->inscricaoPaga == 1;
            $this->pagseguroLink = $inscricao->pagseguroLink;
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
        $valores = (object)[];
        $valores->inscricao = Pessoa::getValorInscricao($pessoa, $evento);
        $valores->alojamento = Pessoa::getValorAlojamento($pessoa, $evento);
        $valores->refeicao = Pessoa::getValorRefeicao($pessoa, $evento);
        $valores->total = $valores->inscricao + $valores->alojamento + $valores->refeicao;
        
        return $valores;
    }

    public static function getValorInscricao($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorInscricao = $pessoa->TIPO == 'R' ? Valor::getValor("NORMAL", $evento, $pessoa) : 0;

        $desconto = Desconto::getDesconto($pessoa);
        if ($desconto > 0){
            $valorInscricao = ($desconto * $valorInscricao) / 100;
        }

        return $valorInscricao;
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
        
        $this->TIPO = $dados->TIPO;
        $this->alojamento = $dados->alojamento;

        //todo: a equipe deve ser atribuida conforme
        $this->equipeRefeicao = $dados->equipeRefeicao;
        $this->nascimento = date('Y-m-d', strtotime($nascimento));
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
        if ($dados->id < 0)
            $pessoa = new Pessoa;
        else
            $pessoa = Pessoa::findOrFail($dados->id);

        $pessoa->populate($dados);

        $dadosDependentes = collect($dados->dependentes);

        $dadosConjuge = $dadosDependentes->first(function($item) {
            $d = (object) $item; 
            return $d->TIPO == "C";
        });

        Pessoa::atualizaConjuge($pessoa, $dadosConjuge);
        
        $pessoa->save();

        Pessoa::atualizaDependentes($pessoa, $dadosDependentes);

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
                    return ($item->nome == $dadosDependente->nome);
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
