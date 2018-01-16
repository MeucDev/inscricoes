<?php

namespace App\Http\Controllers;

use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PessoasController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($cpf, $evento)
    {
        $pessoa = Pessoa::where("cpf", $cpf)->firstOrFail();
        $pessoa->dependentes->prepend($pessoa->conjuge);

        $pessoa->valor = $pessoa->getMeuValor($evento);

        foreach ($pessoa->dependentes as $dependente) {
            $dependente->valor = $dependente->getMeuValor($evento);
        }

        return response()->json($pessoa);
    }
}
