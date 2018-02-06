<?php

namespace App\Http\Controllers;

use App\Pessoa;
use App\Valor;
use App\Inscricao;
use App\Evento;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


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

    public function createInscricao(Request $request, $id){
        $evento = Evento::findOrFail($id);
        $dados = (object) json_decode($request->getContent(), true);

        //todo: validar os dados da pessoa
        $pessoa = Pessoa::atualizaPessoa($dados);

        $inscricao = new Inscricao;
        $inscricao->populate($pessoa, $id);
        $inscricao->save();

        foreach ($pessoa->dependentes as $dependente) {
            if ($dependente->inativo == 1)
                continue;
                
            $inscricaoDependente = new Inscricao;
            $inscricaoDependente->populate($dependente, $id);
            $inscricao->dependentes()->save($inscricaoDependente);
        }
    }
}