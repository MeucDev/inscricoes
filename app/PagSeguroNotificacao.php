<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;

class PagSeguroNotificacao
{
    public function notificar($info){

        $code = $info->getNotificationCode();

        if ($code){
            $inscricao = Inscricao::where('pagseguroCode', $code);

            if ($inscricao){
                $inscricao->inscricaoPaga = 1;
                $inscricao->valorInscricaoPago = $inscricao.valorInscricao;
                $inscricao->save();
            }
        }
    }
}
