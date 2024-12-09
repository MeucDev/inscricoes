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
        $evento = Evento::where("data_fim", ">=", date("Y"))->orderBy("data_fim", "desc")->first();
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

        if($evento->breve()) {
            $evento->toUI();
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Falta pouco! Em breve as inscrições para o próximo evento serão abertas.']);
        }
        
        if(!$evento->aberto()) {
            $evento->toUI();
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Nenhum evento aberto.']);
        }
            

        if ($evento->encerrado()){
            $evento->toUI();
            return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Inscrições encerradas!']);
        }

        if ($validar && $evento->fila_espera()){
            $evento->toUI();
            return view('evento_fila_espera', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições!', 'detalhes' => 'Se ainda tiver interesse em participar, envie um e-mail para: contato@congressodefamilias.com.br']);
        }

        if ($validar && $evento->limite()){
            $evento->toUI();
            $data = $evento->dataProximoLote();
            if($data) {
                return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições deste lote! O próximo lote inicia em '.$data.'.']);
            }
            else {
                return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições!']);
            }
        }
            
        $evento->toUI();
        return view('evento', ['evento' => $evento]);
    }
}