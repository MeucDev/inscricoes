<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variacao extends Model
{
    public $timestamps = false;
    protected $table = 'valor_variacoes';

    public static function getValor($valor_id, $pessoa){
        $dateNow = date("Y-m-d");

        $variacoes = Variacao::where("valor_id", $valor_id)
            ->orderBy('data_ate')
            ->get();

        foreach ($variacoes as $variacao) {
            if ($variacao->data_ate && $dateNow <= $variacao->data_ate)
                return $variacao->valor;
            
            if ($pessoa->idade >= $variacao->idade_inicio && $pessoa->idade <= $variacao->idade_fim)
                return $variacao->valor;
        }

        return null;
    }        
}
