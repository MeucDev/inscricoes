<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;
use Illuminate\Http\Response;
use Exception;

class PagSeguroNotificacao
{
    public static function notificar($info){
        $code = str_replace('-', '', $info->getCode());

        if (!$code){
            print_r("Erro: Código nulo retornado pelo pagseguro.");
            return;
        }
        
        $inscricao = Inscricao::where('pagseguroCode', $code)->first();

        if (!$inscricao){
            print_r("Não foi encontrada a inscrição com o código:" . $code);
            return;
        }

        $inscricao->inscricaoPaga = 1;
        $inscricao->valorInscricaoPago = $inscricao.valorInscricao;
        $inscricao->save();
        print_r("Inscrição paga:" . $inscricao->id);
    }
}
