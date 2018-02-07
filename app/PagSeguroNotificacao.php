<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;
use Illuminate\Http\Response;
use Exception;

class PagSeguroNotificacao
{
    public function notificar($info){

        $code = $info->getNotificationCode();

        if (!$code){
            print_r("Código nulo retornado pelo pagseguro.");
            Response::setStatusCode(Response::HTTP_BAD_REQUEST);
            return;
        }
        
        $inscricao = Inscricao::where('pagseguroCode', $code)->first();


        if (!$inscricao){
            print_r("Não foi encontrada a inscrição com o código:" . $code);
            Response::setStatusCode(Response::HTTP_BAD_REQUEST);
            return;
        }

        try{
            $inscricao->inscricaoPaga = 1;
            $inscricao->valorInscricaoPago = $inscricao.valorInscricao;
            $inscricao->save();
            print_r("Inscrição paga:" . $inscricao->id);
        }catch(Exception $e){
            print_r($e.getMessage());
            Response::setStatusCode(Response::HTTP_BAD_REQUEST);
        }
    }
}
