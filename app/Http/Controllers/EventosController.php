<?php

namespace App\Http\Controllers;

use App\Pessoa;
use App\Valor;
use App\Inscricao;
use App\Evento;
use App\LinkInscricao;
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

        $tipoInscricao = null;
        $bypassAtivo = false;

        // Prioridade 1: Validar parâmetro 'p' (link seguro)
        if (request()->has('p')) {
            $token = request()->query('p');
            $linkInscricao = LinkInscricao::where('token', $token)
                ->where('evento_id', $evento->id)
                ->first();
            
            if (!$linkInscricao) {
                $evento->toUI();
                return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Link de inscrição inválido']);
            }
            
            // Validar se o link pode ser usado
            if (!$linkInscricao->isValido() || !$linkInscricao->podeSerUsado()) {
                $evento->toUI();
                return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Link de inscrição inválido']);
            }
            
            $tipoInscricao = $linkInscricao->tipo_inscricao;
            $bypassAtivo = true; // Link seguro ativa bypass
        }
        // Prioridade 2: Validar bypass+type (links antigos - compatibilidade)
        elseif (request()->has('bypass') && request()->has('type')) {
            $bypassDate = base64_decode(request()->query('bypass'));
            $typeDecoded = base64_decode(request()->query('type'));
            $tiposValidos = ['NORMAL', 'BANDA', 'COMITE', 'STAFF'];
            
            if ($bypassDate == date("Y-m-d") && in_array($typeDecoded, $tiposValidos)) {
                $tipoInscricao = $typeDecoded;
                $bypassAtivo = true;
            }
        }
        // Prioridade 3: Apenas bypass (compatibilidade)
        elseif (request()->has('bypass')) {
            $bypassDate = base64_decode(request()->query('bypass'));
            if ($bypassDate == date("Y-m-d")) {
                $bypassAtivo = true;
            }
        }

        // Se não tem bypass (nem p nem bypass), aplicar validações normais
        if (!$bypassAtivo) {
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

            if ($validar && $evento->limite()){
                $evento->toUI();
                if ($evento->fila_espera()){
                    return view('evento_fila_espera', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições!', 'detalhes' => 'Se ainda tiver interesse em participar, envie um e-mail para: contato@congressodefamilias.com.br']);                   
                }
                $data = $evento->dataProximoLote();
                if($data) {
                    return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições deste lote! O próximo lote inicia em '.$data.'.']);
                }
                else {
                    return view('evento_mensagem', ['evento' => $evento, 'mensagem' => 'Desculpe, mas já atingimos o limite de inscrições!']);
                }
            }
        }
        
        $evento->toUI();
        return view('evento', ['evento' => $evento, 'tipoInscricao' => $tipoInscricao]);
    }
}