<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;

class IntegracaoPagSeguro
{
    private static function gerarPagamento($inscricao){
        $data = [
            'items' => [
                [
                    'id' => $inscricao->numero,
                    'description' => $inscricao->evento->nome,
                    'amount' => $inscricao->valorInscricao,
                    'quantity' => '1'
                ]
            ],
            'shipping' => [
                'address' => [
                    'postalCode' => $inscricao->pessoa->cep,
                    'street' => $inscricao->pessoa->endereco,
                    'number' => $inscricao->pessoa->nroend,
                    'district' => $inscricao->pessoa->bairro,
                    'city' => $inscricao->pessoa->cidade,
                    'state' => $inscricao->pessoa->uf,
                    'country' => 'BRA',
                ],
                'type' => 1,
                'cost' => 0,
            ],
            'sender' => [
                'email' => $inscricao->pessoa->email,
                'name' => $inscricao->pessoa->nome,
                'documents' => [
                    [
                        'number' => $inscricao->pessoa->cpf,
                        'type' => 'CPF'
                    ]
                ],
                'phone' => $inscricao->pessoa->telefone,
                'bornDate' => $inscricao->pessoa->nascimento,
            ]
        ]; 
        
        $checkout = PagSeguro::checkout()->createFromArray($data);
        $credentials = PagSeguro::credentials()->get();
        $information = $checkout->send($credentials); // Retorna um objeto de laravel\pagseguro\Checkout\Information\Information

        $inscricao->pagseguroCode = $information->getCode();
        $inscricao->save();
        
        $result = (object)[];
        $result->link = $information->getLink();
        return $result;
    }
}
