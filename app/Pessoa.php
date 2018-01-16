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

    public static function getIdade($data){

        $data = str_replace("-","/",$data);
        // Separa em dia, mês e ano
        list($dia, $mes, $ano) = explode('/', $data);

        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
        return $idade;
    }
    public function getMeuValor($evento){
        return Pessoa::getValor($this, $evento);
    }

    public static function getValor($pessoa, $evento){
        if ($pessoa->nacimento)
            $pessoa->idade = Pessoa::getIdade($pessoa->nascimento);
        
        $valorInscricao = $pessoa->TIPO == 'R' ? \App\Valor::getValor("NORMAL", $evento, $pessoa) : 0;
        $valorAlojamento = \App\Valor::getValor($pessoa->alojamento, $evento, $pessoa);
        $valorRefeicao = \App\Valor::getValor($pessoa->refeicao, $evento, $pessoa);
        return $valorInscricao + $valorAlojamento + $valorRefeicao;
    }
}
