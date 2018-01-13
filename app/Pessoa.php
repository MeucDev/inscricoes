<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function getValor($evento){
        $valorInscricao = $this->TIPO == 'R' ? \App\Valor::getValor("NORMAL", $evento, $pessoa) : 0;
        $valorAlojamento = \App\Valor::getValor($this->alojamento, $evento, $this);
        $valorRefeicao = \App\Valor::getValor($this->refeicao, $evento, $this);
        return $valorAlojamento + $valorRefeicao;
    }
}
