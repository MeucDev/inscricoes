<?php

namespace App\Http\Controllers;

use App\Pessoa;
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
     
        if ($pessoa->nascimento)
            $pessoa->nascimento = DateTime::createFromFormat('Y-m-d', $pessoa->nascimento)->format('d/m/Y');
            
        if ($pessoa->conjuge)
            $pessoa->dependentes->prepend($pessoa->conjuge);

        $pessoa->valor = $pessoa->getMeuValor($evento);

        foreach ($pessoa->dependentes as $dependente) {
            $dependente->valor = $dependente->getMeuValor($evento);
            if ($dependente->nascimento)
                $dependente->nascimento = DateTime::createFromFormat('Y-m-d', $dependente->nascimento)->format('d/m/Y');
        }

        $result = (object) $pessoa->toArray();

        $result->dependentes = $pessoa->dependentes->reject(function($item) {
            return $item->inativo == 1;
        });

        return response()->json($result);
    }
}
