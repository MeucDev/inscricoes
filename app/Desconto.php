<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desconto extends Model
{
    public $timestamps = false;

    public static function getDesconto($pessoa){
        // Desconto por CPF tem precedência sobre desconto por distância (UF/cidade)
        // Primeiro, buscar desconto por CPF (apenas se CPF não estiver vazio)
        if (!empty($pessoa->cpf)) {
            // Normalizar CPF removendo caracteres não numéricos para garantir comparação correta
            // O CPF pode vir formatado (123.456.789-00) ou não (12345678900) do frontend/banco
            $cpfNormalizado = preg_replace('/\D/', '', $pessoa->cpf);
            
            // Buscar todos os descontos que têm CPF preenchido e comparar normalizado
            // Isso garante que funciona independente de como o CPF está armazenado no banco
            $descontosComCpf = Desconto::whereNotNull('cpf')
                ->where('cpf', '!=', '')
                ->get();
            
            foreach ($descontosComCpf as $desconto) {
                $cpfBancoNormalizado = preg_replace('/\D/', '', $desconto->cpf);
                if ($cpfBancoNormalizado === $cpfNormalizado) {
                    return $desconto->perc;
                }
            }
        }
        
        // Se não encontrou por CPF, buscar por UF (desconto por distância)
        if (!empty($pessoa->uf)) {
            $desconto = Desconto::where('nome', '=', $pessoa->uf)
                ->where(function($query) {
                    $query->whereNull('cpf')
                          ->orWhere('cpf', '=', '');
                }) // Garantir que não é um desconto por CPF
                ->first();
            
            if ($desconto) {
                return $desconto->perc;
            }
        }
        
        // Se não encontrou por UF, buscar por cidade (desconto por distância)
        if (!empty($pessoa->cidade)) {
            $desconto = Desconto::where('nome', '=', $pessoa->cidade)
                ->where(function($query) {
                    $query->whereNull('cpf')
                          ->orWhere('cpf', '=', '');
                }) // Garantir que não é um desconto por CPF
                ->first();
            
            if ($desconto) {
                return $desconto->perc;
            }
        }
        
        return 0;
    }

    public static function getPossuiDescontoEventoAtual($pessoa, $eventoAtual) {
        $cpf = $pessoa->cpf;
        $nome = $pessoa->nome;
        
        // Normalizar CPF para comparação
        $cpfNormalizado = !empty($cpf) ? preg_replace('/\D/', '', $cpf) : '';
        
        // Buscar descontos do evento atual
        $descontos = Desconto::where(function($query) use ($eventoAtual){
            $query->whereNotNull('evento_aplicar_id')
                ->where('evento_aplicar_id', '=', $eventoAtual);
        })->get();
        
        foreach ($descontos as $desconto) {
            // Comparar por CPF normalizado
            if (!empty($cpfNormalizado) && !empty($desconto->cpf)) {
                $cpfBancoNormalizado = preg_replace('/\D/', '', $desconto->cpf);
                if ($cpfBancoNormalizado === $cpfNormalizado) {
                    return true;
                }
            }
            // Comparar por nome
            if (!empty($nome) && $desconto->nome === $nome) {
                return true;
            }
        }
        
        return false;
    }

    public static function getValorDescontoEventoAnteriorPeloEventoAtual($pessoa, $eventoAtual) {
        $result = 0;
        $cpf = $pessoa->cpf;
        $nome = $pessoa->nome;
        
        // Normalizar CPF para comparação
        $cpfNormalizado = !empty($cpf) ? preg_replace('/\D/', '', $cpf) : '';
        
        // Buscar descontos do evento atual
        $descontos = Desconto::where(function($query) use ($eventoAtual){
            $query->whereNotNull('evento_aplicar_id')
                ->where('evento_aplicar_id', '=', $eventoAtual);
        })->get();
        
        $itemDesconto = null;
        foreach ($descontos as $desconto) {
            // Comparar por CPF normalizado (prioridade)
            if (!empty($cpfNormalizado) && !empty($desconto->cpf)) {
                $cpfBancoNormalizado = preg_replace('/\D/', '', $desconto->cpf);
                if ($cpfBancoNormalizado === $cpfNormalizado) {
                    $itemDesconto = $desconto;
                    break;
                }
            }
            // Comparar por nome (fallback)
            if (!$itemDesconto && !empty($nome) && $desconto->nome === $nome) {
                $itemDesconto = $desconto;
            }
        }
        
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
