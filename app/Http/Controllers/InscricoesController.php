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
use App\PagSeguroIntegracao;
use Illuminate\Validation\ValidationExceptionion;

class InscricoesController extends Controller
{
    public function show($id)
    {
        $inscricao = Inscricao::findOrFail($id);
        $inscricao->pessoa;
        $inscricao->presenca = true;

        foreach ($inscricao->dependentes as $dependente) {
            $dependente->pessoa;
            $dependente->presenca = true;
        }

        return $inscricao;
    }
    
    public function confirmar(Request $request, $id)
    {
        $dados = (object) json_decode($request->getContent(), true);

        $inscricao = Inscricao::findOrFail($id);

        $inscricao->presencaConfirmada = $dados->presenca;

        foreach ($inscricao->dependentes as $key => $value) {
            $value->presencaConfirmada = $dados->dependentes[$key]->presenca;
        }
        
        $inscricao->valorTotalPago = $dados->valorTotalPago;
        $inscricao->save();
    }    

}