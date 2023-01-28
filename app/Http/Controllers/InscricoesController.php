<?php

namespace App\Http\Controllers;

use App\Pessoa;
use App\Valor;
use App\Inscricao;
use App\Evento;
use App\HistoricoPagamento;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\PagSeguroIntegracao;
use Illuminate\Validation\ValidationExceptionion;

class InscricoesController extends Controller
{
    public function show($id)
    {
        $inscricao = Inscricao::with('pessoa')
        ->with('evento')
        ->with('dependentes')
        ->with('dependentes.pessoa')
        ->findOrFail($id);

        $inscricao->presenca = true;
        $inscricao->imprimir = true;

        foreach ($inscricao->dependentes as $dependente) {
            $dependente->presenca = true;
            $dependente->imprimir = ($dependente->pessoa->idade > 6);
        }

        if ($inscricao->refeicao != 'NENHUMA') 
            $inscricao->equipeRefeicao = Inscricao::escolherEquipe($inscricao);
        else
            $inscricao->equipeRefeicao = null;        
        return $inscricao;
    }
    
    public function pessoa($id)
    {
        $inscricao = Inscricao::with("pessoa")->findOrFail($id);

        $inscricao->pessoa->ajustarDados();

        $result =  (object) $inscricao->pessoa->toArray();
        $result->valores = $inscricao->getValores();

        foreach ($inscricao->dependentes as $dependente) {
            $dependente->pessoa->ajustarDados();
            $dep = (object) $dependente->pessoa->toArray();
            $dep->valores = $dependente->getValores();
            $result->dependentes[] = $dep;
        }

        if (!$result->dependentes)
            $result->dependentes = array();
        
        return response()->json($result);
    } 
    
    public function validar(Request $request, $dados, $evento){
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
            'nroend' => 'required',
            'alojamento' => 'required',
            'refeicao' => 'required',

            'dependentes.*.TIPO' => 'required',
            'dependentes.*.nome' => 'required',
            'dependentes.*.nomecracha' => 'required',
            'dependentes.*.nascimento' => 'required|date_format:d/m/Y|after:01/01/1900',
            'dependentes.*.casamento' => 'nullable|date_format:d/m/Y|after:01/01/1900|before:' . date("d/m/Y"),
            'dependentes.*.sexo' => 'required',
            'dependentes.*.alojamento' => 'required',
            'dependentes.*.refeicao' => 'required',
        ]); 

        if (!strrpos($dados->nome, ' '))
            throw new Exception("O nome do responsável deve ser completo");

        $refeicoesLar = Inscricao::where("evento_id", $evento->id)->where("refeicao", "like", "LAR%")->count();

        $refeicoesQuiosque = Inscricao::where("evento_id", $evento->id)->where("refeicao", "like", "QUIOSQUE%")->count();

        if ($evento->limite_refeicoes && $refeicoesLar >= $evento->limite_refeicoes)
            throw new Exception("O limite para refeições no Lar Filadélfia foi atingido.");
    }

    public function criar(Request $request, $evento){
        $evento = Evento::findOrFail($evento);
        $dados = (object) json_decode($request->getContent(), true);

        $this->validar($request, $dados, $evento);
        
        $pessoa = Pessoa::atualizarCadastros($dados);

        $result = DB::transaction(function() use ($dados, $pessoa, $evento) {
            $inscricao = Inscricao::criarInscricao($pessoa, $evento->id);
            $result = (object)[];
            if (!$dados->interno) {
                $result = PagSeguroIntegracao::gerarPagamento($inscricao);
                HistoricoPagamento::registrar($inscricao->numero, 'CRIADO', $inscricao->valorTotal, '');
            }
            return $result;
        });        

        return response()->json($result);
    }

    public function alterar(Request $request, $id){
        $inscricao = Inscricao::findOrFail($id);
        $dados = (object) json_decode($request->getContent(), true);

        $this->validar($request, $dados, $inscricao->evento_id);
        
        $pessoa = Pessoa::atualizarCadastros($dados);

        $result = DB::transaction(function() use ($dados, $pessoa, $id) {
            Inscricao::alterarInscricao($id, $pessoa);
        });        

        return response()->json($result);
    }

    public function definirPago(Request $request, $id){
        $inscricao = Inscricao::findOrFail($id);

        $result = DB::transaction(function() use ($dados, $id) {
            Inscricao::considerarInscricaoPaga($id);
        });        

        return response()->json($result);
    }

    public function presenca(Request $request, $id)
    {
        $dados = (object) json_decode($request->getContent(), true);

        $inscricao = Inscricao::with('dependentes')->findOrFail($id);

        DB::transaction(function() use ($inscricao, $dados, $id) {
            foreach ($inscricao->dependentes as $key => $value) {
                $value->presencaConfirmada = $dados->dependentes[$key]["presenca"];
                $value->equipeRefeicao = $dados->equipeRefeicao;
                $value->inscricaoPaga = 1;
                $value->save();
            }
            
            $inscricao->presencaConfirmada = $dados->presenca;
            $inscricao->valorInscricaoPago = $dados->valorInscricao;
            $inscricao->equipeRefeicao = $dados->equipeRefeicao;
            $inscricao->inscricaoPaga = 1;
            $inscricao->calcularTotalPago();
            $inscricao->save();
        });
    }    
}