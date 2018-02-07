<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $primaryKey = 'numero';
    public $timestamps = false;
    protected $table = 'inscricoes';

    public function dependentes()
    {
        return $this->hasMany('App\Inscricao', 'numero_inscricao_responsavel', 'numero');
    }

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa', 'pessoa_id');
    }

    public function evento()
    {
        return $this->belongsTo('App\Evento', 'evento_id');
    }


    public function populate($pessoa, $evento){
        $this->dataInscricao = date("Y-m-d h:i:s");
        $this->ano = date("Y");
        $this->tipoInscricao = "NORMAL";
        $this->pessoa_id = $pessoa->id;
        $this->alojamento = $pessoa->alojamento;
        $this->equipeRefeicao = $pessoa->equipeRefeicao;
        $this->refeicao = $pessoa->refeicao;
        $this->inscricaoPaga = 0;
        $this->presencaConfirmada = 0;
        $this->evento_id = $evento;

        $this->valorInscricao = Pessoa::getValorInscricao($pessoa, $evento);
        $this->valorInscricaoPago = 0;
        $this->valorRefeicao = Pessoa::getValorRefeicao($pessoa, $evento);
        $this->valorAlojamento = Pessoa::getValorAlojamento($pessoa, $evento);
        $this->valorTotal = $this->valorInscricao + $this->valorRefeicao + $this->valorAlojamento;
        $this->valorTotalPago = 0;
    }

    public static function criarInscricao($pessoa, $evento){
        $inscricao = new Inscricao;
        $inscricao->populate($pessoa, $evento);
        $inscricao->save();

        if ($pessoa->conjuge){
            $inscricaoConjuge = new Inscricao;
            $inscricaoConjuge->populate($pessoa->conjuge, $evento);
            $inscricao->dependentes()->save($inscricaoConjuge);
        }

        foreach ($pessoa->dependentes as $dependente) {
            if ($dependente->inativo == 1)
                continue;

            $inscricaoDependente = new Inscricao;
            $inscricaoDependente->populate($dependente, $evento);
            $inscricao->dependentes()->save($inscricaoDependente);
        }

        return $inscricao;
    }    
}


