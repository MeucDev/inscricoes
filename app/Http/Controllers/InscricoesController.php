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

        $inscricao->pessoa->idade = Pessoa::getIdade($inscricao->pessoa->nascimento);
        $inscricao->presenca = true;
        $inscricao->imprimir = true;

        $evento = Evento::find($inscricao->evento_id);

        foreach ($inscricao->dependentes as $dependente) {
            $dependente->presenca = true;
            $dependente->pessoa->idade = Pessoa::getIdade($dependente->pessoa->nascimento);
            $dependente->imprimir = ($dependente->pessoa->idade >= $evento->idade_imprimir);
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
        $result->tipoInscricao = $inscricao->tipoInscricao ? $inscricao->tipoInscricao : 'NORMAL';

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
        $rules = [
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
            'necessidadesEspeciais' => 'nullable|boolean',

            'dependentes.*.TIPO' => 'required',
            'dependentes.*.nome' => 'required',
            'dependentes.*.nomecracha' => 'required',
            'dependentes.*.nascimento' => 'required|date_format:d/m/Y|after:01/01/1900',
            'dependentes.*.sexo' => 'required',
            'dependentes.*.alojamento' => 'required',
            'dependentes.*.refeicao' => 'required',
            'dependentes.*.necessidadesEspeciais' => 'nullable|boolean',
        ];

        // Adiciona validação de casamento apenas se o evento tiver a configuração habilitada
        if ($evento->registrar_data_casamento == 1) {
            $rules['dependentes.*.casamento'] = 'nullable|date_format:d/m/Y|after:01/01/1900|before:' . date("d/m/Y");
        }

        $this->validate($request, $rules); 

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

        // Processar tipoInscricao do objeto dados (prioritário) ou da query string como fallback
        $tipoInscricao = "NORMAL";
        $tiposValidos = ['NORMAL', 'BANDA', 'COMITE', 'STAFF'];
        
        // Priorizar tipoInscricao que vem do objeto dados (admin ou link especial via Vue)
        if (isset($dados->tipoInscricao) && in_array($dados->tipoInscricao, $tiposValidos)) {
            $tipoInscricao = $dados->tipoInscricao;
        }
        // Fallback: verificar se vem da query string (link especial direto)
        elseif ($request->has('bypass') && $request->has('type')) {
            $bypassDate = base64_decode($request->query('bypass'));
            $typeDecoded = base64_decode($request->query('type'));
            
            // Validar que bypass corresponde à data atual
            if ($bypassDate == date("Y-m-d") && in_array($typeDecoded, $tiposValidos)) {
                $tipoInscricao = $typeDecoded;
            }
        }

        $this->validar($request, $dados, $evento);
        
        $pessoa = Pessoa::atualizarCadastros($dados);

        $result = DB::transaction(function() use ($dados, $pessoa, $evento, $tipoInscricao) {
            $inscricao = Inscricao::criarInscricao($pessoa, $evento->id, $dados->interno, $tipoInscricao);
            $result = (object)[];
            
            // Sempre executa o checkout PagSeguro e registra no histórico
            $pagamentoResult = PagSeguroIntegracao::gerarPagamento($inscricao);
            HistoricoPagamento::registrar($inscricao->numero, 'CRIADO', $inscricao->valorTotal, '');
            
            // Se não for interno, retorna o link para redirecionamento
            // Se for interno, retorna objeto vazio (link já foi salvo em pagseguroLink)
            if (!$dados->interno) {
                $result = $pagamentoResult;
            }
            
            return $result;
        });        

        return response()->json($result);
    }

    public function alterar(Request $request, $id){
        $inscricao = Inscricao::findOrFail($id);
        $dados = (object) json_decode($request->getContent(), true);
        $evento = Evento::findOrFail($inscricao->evento_id);

        $this->validar($request, $dados, $evento);
        
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