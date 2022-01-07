<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;

class PagSeguroIntegracao
{
    public static function gerarPagamento($inscricao){
        $items = [];
        array_push($items, [
            'id' => $inscricao->numero,
            'description' => $inscricao->evento->nome,
            'amount' => $inscricao->valorInscricao,
            'quantity' => '1'
        ]);

        $codigos = [];
        array_push($codigos, $inscricao->alojamento);
        array_push($codigos, $inscricao->refeicao);
        $valoresBoleto = $inscricao->getValoresCobrarBoleto($codigos);

        foreach($valoresBoleto as &$valorBoleto) {
            $valor = Valor::getValor($valorBoleto->codigo, $valorBoleto->evento_id, $inscricao->pessoa);
            array_push($items, [
                'id' => $inscricao->numero . '_' . $valorBoleto->codigo,
                'description' => $valorBoleto->nome . ' ' . $inscricao->pessoa->nomecracha,
                'amount' => $valor,
                'quantity' => '1'
            ]);

            foreach ($inscricao->dependentes as &$dependente){
                $valor = Valor::getValor($valorBoleto->codigo, $valorBoleto->evento_id, $dependente);
                if($valor > 0){
                    array_push($items, [
                        'id' => $dependente->numero . '_' . $valorBoleto->codigo,
                        'description' => $valorBoleto->nome . ' ' . $dependente->pessoa->nomecracha,
                        'amount' => $valor,
                        'quantity' => '1'
                    ]);
                }
            }
        }

        $data = [
            'reference' => $inscricao->numero,
            'items' => $items,
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

        $inscricao->pagseguroLink = $information->getLink();
        $inscricao->save();

        $result = (object)[];
        $result->link = $information->getLink();
        return $result;
    }
}
