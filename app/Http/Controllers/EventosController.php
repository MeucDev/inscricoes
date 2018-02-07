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
use App\IntegracaoPagSeguro;

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

        $result = IntegracaoPagSeguro::gerarPagamento($inscricao);

        return response()->json($result);
    }
}