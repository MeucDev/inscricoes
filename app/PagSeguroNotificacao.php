<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;
use Illuminate\Http\Response;
use Exception;

class PagSeguroNotificacao
{
    public static function notificar($info){

        $code = $info->getCode();

        if (!$code){
            throw new Exception("Erro: Código nulo retornado pelo pagseguro.");
        }
        
        $inscricao = Inscricao::where('pagseguroCode', $code)->first();

        if (!$inscricao){
            throw new Exception("Não foi encontrada a inscrição com o código:" . $code);
        }

        $inscricao->inscricaoPaga = 1;
        $inscricao->valorInscricaoPago = $inscricao.valorInscricao;
        $inscricao->save();
        print_r("Inscrição paga:" . $inscricao->id);
    }
}
