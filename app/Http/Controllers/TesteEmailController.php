<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\TesteEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TesteEmailController extends Controller
{
    public function enviar(Request $request) 
    {
        // $dados = (object) json_decode($request->getContent(), true);
        TesteEmail::enviarEmail($dados, 'teste');
    }
}