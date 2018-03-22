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

class EventosController extends Controller
{
    public function first()
    {
        $evento = Evento::whereYear("data_fim", date("Y"))->first();
        return $this->viewEvento($evento);
    }    

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $evento = Evento::find($id);
        return $this->viewEvento($evento);
    }

    public function viewEvento($evento){
        if (!$evento)
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Evento não encontrado']);

        if ($evento->encerrado())
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Inscrições encerradas!']);

        if ($evento->limite())
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições!']);
            
         $evento->toUI();
        return view('evento', ['evento' => $evento]);
    }
}