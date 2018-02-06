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
        $this->valorTotal = $inscricao->valorInscricao + $inscricao->valorRefeicao + $inscricao->valorAlojamento;
        $this->valorTotalPago = 0;
    }
}


