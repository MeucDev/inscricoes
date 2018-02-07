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
    public function getMeuValor($evento){
        return Pessoa::getValor($this, $evento);
    }

    public static function getValor($pessoa, $evento){
        $valorInscricao = Pessoa::getValorInscricao($pessoa, $evento);
        $valorRefeicao = Pessoa::getValorRefeicao($pessoa, $evento);
        $valorAlojamento = Pessoa::getValorAlojamento($pessoa, $evento);
        return $valorInscricao + $valorRefeicao + $valorAlojamento;
    }

    public static function getValorInscricao($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorInscricao = $pessoa->TIPO == 'R' ? \App\Valor::getValor("NORMAL", $evento, $pessoa) : 0;
        return $valorInscricao;
    }

    public static function getValorRefeicao($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorRefeicao = \App\Valor::getValor($pessoa->refeicao, $evento, $pessoa);
        return $valorRefeicao;
    }
    
    public static function getValorAlojamento($pessoa, $evento){
        if ($pessoa->nascimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorAlojamento = \App\Valor::getValor($pessoa->alojamento, $evento, $pessoa);
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
        $this->uf = $dados->uf;
        $this->cpf = $dados->cpf;
        $this->nroend = $dados->nroend;
        $this->sexo = $dados->sexo;
        $this->inativo = 0;
    }

    public static function atualizaPessoa($dados){
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
        
        $pessoa->save();

        foreach ($dadosDependentes as $dadosDependente) {
            $dadosDependente = (object) $dadosDependente;
            if ($dadosDependente->TIPO == 'C')
                continue;

            $dependente = null;

            foreach ($pessoa->dependentes as $item) {
                if ($item->nome == $dadosDependente->nome)
                    $dependente = $item;
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
        
        return $pessoa;
    }
    
}
