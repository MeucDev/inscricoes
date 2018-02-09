<?php

namespace App\Http\Controllers;

use App\Pessoa;
use App\Valor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValoresController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function valor(Request $request, $evento)
    {
        $pessoa = (object) json_decode($request->getContent(), true);

        $valores = Pessoa::getValores($pessoa, $evento);

        return response()->json($valores);
    }
}
