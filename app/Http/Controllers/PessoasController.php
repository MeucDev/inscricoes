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
    public function show($cpf)
    {
        $pessoa = Pessoa::where("cpf", $cpf)->first();
        $pessoa->dependentes;

        return response()->json($pessoa);
    }
}
