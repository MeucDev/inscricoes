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
        return $this->viewEvento($evento, true);
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
        return $this->viewEvento($evento, true);
    }

    public function viewEvento($evento, $validar){
        if (!$evento)
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Evento não encontrado']);

        if ($evento->encerrado()){
            $evento->toUI();
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Inscrições encerradas!']);
        }

        if ($validar && $evento->limite()){
            $evento->toUI();
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições!']);
        }
            
        $evento->toUI();
        return view('evento', ['evento' => $evento]);
    }
}