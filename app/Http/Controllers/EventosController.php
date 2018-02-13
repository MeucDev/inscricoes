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
            return view('evento_invalido', ['evento' => $evento, 'mensagem' => 'Evento não encontrado']);

        if (!$evento->encerrado())
            return view('evento_invalido', ['evento' => $evento, 'mensagem' => 'Inscrições encerradas!']);
        
         $evento->toUI();
        return view('evento', ['evento' => $evento]);
    }


    public function validar(Request $request){
        $this->validate($request, [
            'TIPO' => 'required',
            'cpf' => 'required|digits:11',
            'nome' => 'required',
            'nomecracha' => 'required',
            'email' => 'required|email',
            'nascimento' => 'required|date_format:d/m/Y|after:01/01/1900|before:' . date("d/m/Y"),
            'sexo' => 'required',
            'telefone' => 'required|regex:/\d{2}\s\d{8,9}/u',
            'cep' => 'required|regex:/\d{5}-\d{3}/u',
            'uf' => 'required|min:2|max:2',
            'cidade' => 'required',
            'bairro' => 'required',
            'endereco' => 'required',
            'nroend' => 'required|numeric',
            'alojamento' => 'required',
            'refeicao' => 'required',

            'dependentes.*.TIPO' => 'required',
            'dependentes.*.nome' => 'required',
            'dependentes.*.nomecracha' => 'required',
            'dependentes.*.nascimento' => 'required|date_format:d/m/Y|after:01/01/1900',
            'dependentes.*.sexo' => 'required',
            'dependentes.*.alojamento' => 'required',
            'dependentes.*.refeicao' => 'required',
        ]);        
    }
   public function inscricao(Request $request, $id){
        $evento = Evento::findOrFail($id);

        $this->validar($request);

        $dados = (object) json_decode($request->getContent(), true);

        $pessoa = Pessoa::atualizarCadastros($dados);

        $result = DB::transaction(function() use ($dados, $pessoa, $id) {
            $inscricao = Inscricao::criarInscricao($pessoa, $id);
            $result = PagSeguroIntegracao::gerarPagamento($inscricao);
            return $result;
        });        

        return response()->json($result);
    }
}