<?php

namespace App\Http\Controllers;

use App\Pessoa;
use App\Valor;
use App\Inscricao;
use App\Evento;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use laravel\pagseguro\Platform\Laravel5\PagSeguro;

class EventosController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, $nome = null)
    {
        return view('evento', ['evento' => Evento::findOrFail($id)]);
    }

    public function fazerInscricao(Request $request, $id){
        $evento = Evento::findOrFail($id);
        $dados = (object) json_decode($request->getContent(), true);

        $pessoa = Pessoa::atualizarCadastros($dados);

        $inscricao = Inscricao::criarInscricao($pessoa, $id);

        $result = $this->integrarPagSeguro($inscricao);

        return response()->json($result);
    }

    private function integrarPagSeguro($inscricao){
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