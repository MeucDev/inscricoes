<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;
use Illuminate\Http\Response;
use Exception;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

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
            print_r("Não foi encontrada a inscrição com o código: " . $numero);
            return;
        }

        //3) Paga: a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.
        //4) Disponível: a transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta.
        if ($info->getStatus()->getCode() == 3 || $info->getStatus()->getCode() == 4){
            print_r("Inscrição paga: " . $numero);
            $inscricao->inscricaoPaga = 1;
            $inscricao->valorInscricaoPago = $info->getAmounts()->getGrossAmount();
            $inscricao->pagseguroCode = $info->getCode();
            $inscricao->save();

            PagSeguroNotificacao::enviarEmail($inscricao, "confirmacao");
        }else{
            print_r("Inscrição não está paga: " . $numero);

            PagSeguroNotificacao::enviarEmail($inscricao, "pagamento_rejeitado");
        }
    }

    public static function enviarEmail($inscricao, $slug){
        $data = ['nome'=>$inscricao->pessoa->nome,'link'=>$inscricao->pagseguroLink];

        try{
            CRUDBooster::sendEmail(['to'=>$inscricao->pessoa->email,'data'=>$data,'template_name_you_created'=>$slug,'attachments'=>[]]);
        }   
        catch(Exception $e){
            print_r("Erro ao enviar email: " . $e->getMessage());
        }

    }
}
