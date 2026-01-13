<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inscricao;

class HistoricoPagamento extends Model
{
    protected $table = 'historico_pagamentos';

    public function inscricao()
    {
        return $this->belongsTo('App\Inscricao', 'inscricao_numero');
    }

    /**
     * Accessor para valorLiquido - retorna 0 se for null (para evitar erro no number_format do CRUDBooster)
     */
    public function getValorLiquidoAttribute($value)
    {
        return $value !== null ? (float) $value : 0;
    }

    /**
     * Accessor para valorTaxas - retorna 0 se for null (para evitar erro no number_format do CRUDBooster)
     */
    public function getValorTaxasAttribute($value)
    {
        return $value !== null ? (float) $value : 0;
    }

    public static function registrar($numero_inscricao, $operacao, $valor, $codigo_pag_seguro, $valorLiquido = null, $valorTaxas = null, $formaPagamento = null) {
        try{
            $registro = new HistoricoPagamento;
            $registro->inscricao_numero = $numero_inscricao;
            $registro->operacao = $operacao;
            $registro->valor = $valor;
            $registro->pagseguro_code = $codigo_pag_seguro;
            $registro->valorLiquido = $valorLiquido;
            $registro->valorTaxas = $valorTaxas;
            $registro->formaPagamento = $formaPagamento;
            $registro->save();
        }
        catch(Exception $e){
            print_r("Erro ao registrar histórico de pagamento: " . $e->getMessage());
        }
    }
}
