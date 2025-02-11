<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desconto extends Model
{
    public $timestamps = false;

    public static function getDesconto($pessoa){
        $desconto = Desconto::where('cpf', $pessoa->cpf)
            ->orWhere('nome', '=', $pessoa->uf)
            ->orWhere('nome', '=', $pessoa->cidade)->first();

        if ($desconto)
            return $desconto->perc;
        else
            return 0;
    }

    public static function getPossuiDescontoEventoAtual($pessoa, $eventoAtual) {
        $cpf = $pessoa->cpf;
        $nome = $pessoa->nome;
        $desconto = Desconto::where(function($query) use($cpf, $nome){
            $query->where('cpf', '=', $cpf)
                ->orWhere('nome', '=', $nome);
        })->where(function($query) use ($eventoAtual){
            $query->whereNotNull('evento_aplicar_id')
                ->where('evento_aplicar_id', '=', $eventoAtual);
        })->first();
        if($desconto) {
            return true;
        } else {
            return false;
        }
    }

    public static function getValorDescontoEventoAnteriorPeloEventoAtual($pessoa, $eventoAtual) {
        $result = 0;
        $cpf = $pessoa->cpf;
        $nome = $pessoa->nome;
        $itemDesconto = Desconto::where(function($query) use($cpf, $nome){
            $query->where('cpf', '=', $cpf)
                ->orWhere('nome', '=', $nome);
        })->where(function($query) use ($eventoAtual){
            $query->whereNotNull('evento_aplicar_id')
                ->where('evento_aplicar_id', '=', $eventoAtual);
        })->first();
        
        if($itemDesconto) {
            if($itemDesconto->valor_desconto && $itemDesconto->valor_desconto > 0) {
                $result = floatval($itemDesconto->valor_desconto);
            } else {
                $inscricao = Inscricao::getInscricaoByPessoaeEvento($pessoa, $itemDesconto->evento_origem_id);
                if($inscricao && $inscricao->valorTotalPago > 0) {
                    $result = floatval($inscricao->valorTotalPago);
                }
            }
        } 
        return $result;
    }
}
