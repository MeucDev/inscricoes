<?php

namespace App\Http\Controllers;

use App\Pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \DateTime;


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
    
        $result = $pessoa->toUI($evento);

        return response()->json($result);
    }
}
