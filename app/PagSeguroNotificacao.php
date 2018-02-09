<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;
use Illuminate\Http\Response;
use Exception;

class PagSeguroNotificacao
{
    public static function notificar($info){
        $numero = $info->getReference();

        if (!$numero){
            print_r("Erro: Código nulo retornado pelo pagseguro.");
            return;
        }
        
        $inscricao = Inscricao::where('numero', $numero)->first();

        if (!$inscricao){
            print_r("Não foi encontrada a inscrição com o código:" . $numero);
            return;
        }

        $inscricao->inscricaoPaga = 1;
        $inscricao->valorInscricaoPago = $info->getAmounts()->getGrossAmount();
        $inscricao->save();
        print_r("Inscrição paga:" . $numero);
    }
}
