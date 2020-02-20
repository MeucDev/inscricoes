<?php

namespace App\Http\Controllers;

use App\Pessoa;
use App\Inscricao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \DateTime;
use App\PagSeguroIntegracao;

class PessoasController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($cpf, $evento)
    {
        $pessoa = Pessoa::with("dependentes")->where("cpf", $cpf)->firstOrFail();

        $result = $pessoa->toUI($evento);

        $inscricao = Inscricao::where("pessoa_id", $pessoa->id)
            ->where("evento_id", $evento)
            ->first();
            
        $inscricao->calcularValores();
        if ($inscricao){
            $result->inscricaoPaga = $inscricao->inscricaoPaga == 1;
            $result->inscricao = $inscricao->numero;
            if (!$inscricao->inscricaoPaga){
                PagSeguroIntegracao::gerarPagamento($inscricao);
                $result->pagseguroLink = $inscricao->pagseguroLink;
            }
        }

        return response()->json($result);
    }
}
