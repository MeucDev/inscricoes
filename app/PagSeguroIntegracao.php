<?php

namespace App;

use laravel\pagseguro\Platform\Laravel5\PagSeguro;

class PagSeguroIntegracao
{
    public static function gerarPagamento($inscricao){
        $result = (object)[];
        $result->inscricao_id = $inscricao->numero;
        
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

        $valores = $inscricao->getValores();
        $total = $valores->total;

        foreach($valoresBoleto as &$valorBoleto) {
            $valor = Valor::getValor($valorBoleto->codigo, $valorBoleto->evento_id, $inscricao->pessoa);
            $total += $valor;
            array_push($items, [
                'id' => $inscricao->numero . '_' . $valorBoleto->codigo,
                'description' => $valorBoleto->nome . ' ' . $inscricao->pessoa->nomecracha,
                'amount' => $valor,
                'quantity' => '1'
            ]);
        }

        foreach ($inscricao->dependentes as &$dependente){
            $codigos = [];
            array_push($codigos, $dependente->alojamento);
            array_push($codigos, $dependente->refeicao);
            $valoresBoleto = $dependente->getValoresCobrarBoleto($codigos);

            foreach($valoresBoleto as &$valorBoleto) {
                $valor = Valor::getValor($valorBoleto->codigo, $valorBoleto->evento_id, $dependente->pessoa);
                $total += $valor;
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

        $desconto = '';
        if($valores->totalDescontos > 0) {
            $desconto = '-'.$valores->totalDescontos.'';
        }
        $desconto = number_format($valores->totalDescontos * -1, 2);

        if($total > $valores->totalDescontos || $valores->totalDescontos == 0) {
            $data = [
                'reference' => $inscricao->numero,
                'items' => $items,
                'extraAmount' => $desconto,
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

            $result->link = $information->getLink();
            
            $inscricao->pagseguroLink = $result->link;
            
            $inscricao->save();
        }
        
        return $result;
    }
}
