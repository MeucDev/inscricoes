<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;
use Illuminate\Http\Response;
use Exception;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use App\PagSeguroIntegracao;
use App\HistoricoPagamento;

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

        if ($inscricao->inscricaoPaga){
            print_r("A inscrição já foi paga: " . $numero);
            return;
        }

        $valor = $info->getAmounts()->getGrossAmount();
        $codigo_pag_seguro = $info->getCode();

        //3) Paga: a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.
        //4) Disponível: a transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta.
        if ($info->getStatus()->getCode() == 3 || $info->getStatus()->getCode() == 4){
            print_r("Inscrição paga: " . $numero);
            $inscricao->inscricaoPaga = 1;
            $inscricao->valorInscricaoPago = $valor;
            $inscricao->valorTotalPago = $inscricao->valorInscricaoPago;
            $inscricao->pagseguroCode = $codigo_pag_seguro;
            $inscricao->save();

            PagSeguroNotificacao::enviarEmail($inscricao, "confirmacao");

            HistoricoPagamento::registrar($numero, 'APROVADO', $valor, $codigo_pag_seguro);
        }else if ($info->getStatus()->getCode() == 7){
            print_r("Inscrição não está paga: " . $numero);

            PagSeguroIntegracao::gerarPagamento($inscricao);
            PagSeguroNotificacao::enviarEmail($inscricao, "pagamento_rejeitado");

            HistoricoPagamento::registrar($numero, 'NEGADO', $valor, $codigo_pag_seguro);
        }
    }

    public static function enviarEmail($inscricao, $slug){
        $data = ['nome'=>$inscricao->pessoa->nomecracha,'link'=>$inscricao->pagseguroLink];

        try{
            CRUDBooster::sendEmail(['to'=>$inscricao->pessoa->email,'data'=>$data,'template'=>$slug,'attachments'=>[]]);
        }   
        catch(Exception $e){
            print_r("Erro ao enviar email: " . $e->getMessage());
        }

    }
}
