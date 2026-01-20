<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inscricao;
use App\HistoricoPagamento;
use App\PagSeguroNotificacao;
use Illuminate\Support\Facades\DB;
use Exception;

class PagamentoApiController extends Controller
{
    /**
     * Confirma pagamento de uma inscrição
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmar(Request $request)
    {
        try {
            // Parse JSON do body (Laravel 5.3 pode não fazer isso automaticamente)
            $content = $request->getContent();
            if (!empty($content)) {
                $jsonData = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                    $request->merge($jsonData);
                }
            }

            // Validação do payload
            $this->validate($request, [
                'inscricao_numero' => 'required|integer|min:1',
                'pagseguro_code' => 'required|string|max:255',
                'valor' => 'required|numeric|min:0',
                'status' => 'required|integer|in:3,4',
                'valorLiquido' => 'nullable|numeric|min:0',
                'valorTaxas' => 'nullable|numeric|min:0',
                'formaPagamento' => 'nullable|string|max:50'
            ]);

            $dados = $request->all();

            // Sanitização
            $inscricaoNumero = (int) $dados['inscricao_numero'];
            $pagseguroCode = trim($dados['pagseguro_code']);
            $valor = (float) $dados['valor'];
            $status = (int) $dados['status'];
            $valorLiquido = isset($dados['valorLiquido']) ? (float) $dados['valorLiquido'] : null;
            $valorTaxas = isset($dados['valorTaxas']) ? (float) $dados['valorTaxas'] : null;
            $formaPagamento = isset($dados['formaPagamento']) ? trim($dados['formaPagamento']) : null;

            // Verificar se inscrição existe (carregar relacionamento pessoa para email)
            $inscricao = Inscricao::with('pessoa')->find($inscricaoNumero);
            if (!$inscricao) {
                return response()->json([
                    'success' => false,
                    'error' => 'Inscrição não encontrada',
                    'inscricao_numero' => $inscricaoNumero
                ], 404);
            }

            // Verificar status (3 ou 4 = pago)
            if ($status != 3 && $status != 4) {
                return response()->json([
                    'success' => false,
                    'error' => 'Status inválido. Apenas status 3 ou 4 são considerados pagos.',
                    'status' => $status
                ], 400);
            }

            // Processar pagamento (criação ou atualização) dentro de transação
            DB::transaction(function() use ($inscricao, $valor, $pagseguroCode, $status, $valorLiquido, $valorTaxas, $formaPagamento, $inscricaoNumero) {
                // Compatibilidade com versões antigas de PHP (sem operador ??)
                $emailJaEnviado = isset($inscricao->emailConfirmacaoEnviado)
                    ? (bool) $inscricao->emailConfirmacaoEnviado
                    : false;

                if (!$inscricao->inscricaoPaga) {
                    // Primeiro pagamento: comportamento atual (criação)
                    $inscricao->inscricaoPaga = 1;
                    $inscricao->valorInscricaoPago = $valor;
                    $inscricao->valorTotalPago = $inscricao->valorInscricaoPago;
                    $inscricao->pagseguroCode = $pagseguroCode;
                    $inscricao->save();

                    // Registrar no histórico
                    HistoricoPagamento::registrar(
                        $inscricaoNumero, 
                        'APROVADO', 
                        $valor, 
                        $pagseguroCode,
                        $valorLiquido,
                        $valorTaxas,
                        $formaPagamento
                    );
                } else {
                    // Inscrição já paga: atualizar dados e histórico existente
                    $inscricao->inscricaoPaga = 1;
                    $inscricao->valorInscricaoPago = $valor;
                    $inscricao->valorTotalPago = $inscricao->valorInscricaoPago;
                    $inscricao->pagseguroCode = $pagseguroCode;
                    $inscricao->save();

                    // Localizar último registro APROVADO no histórico
                    $historico = HistoricoPagamento::where('inscricao_numero', $inscricaoNumero)
                        ->where('operacao', 'APROVADO')
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if (!$historico) {
                        // Cenário raro: inscrição paga sem histórico APROVADO registrado
                        HistoricoPagamento::registrar(
                            $inscricaoNumero, 
                            'APROVADO', 
                            $valor, 
                            $pagseguroCode,
                            $valorLiquido,
                            $valorTaxas,
                            $formaPagamento
                        );
                    } else {
                        $atualizou = false;

                        // Atualizar sempre o valor bruto
                        if ($historico->valor != $valor) {
                            $historico->valor = $valor;
                            $atualizou = true;
                        }

                        // Atualizar valor líquido apenas se enviado e diferente
                        if ($valorLiquido !== null && $historico->valorLiquido != $valorLiquido) {
                            $historico->valorLiquido = $valorLiquido;
                            $atualizou = true;
                        }

                        // Atualizar valor de taxas apenas se enviado e diferente
                        if ($valorTaxas !== null && $historico->valorTaxas != $valorTaxas) {
                            $historico->valorTaxas = $valorTaxas;
                            $atualizou = true;
                        }

                        // Atualizar forma de pagamento apenas se enviada e diferente
                        if ($formaPagamento !== null && $historico->formaPagamento != $formaPagamento) {
                            $historico->formaPagamento = $formaPagamento;
                            $atualizou = true;
                        }

                        if ($atualizou) {
                            $historico->save();
                        }
                    }
                }

                // Enviar email de confirmação apenas se ainda não enviado
                if (!$emailJaEnviado) {
                    PagSeguroNotificacao::enviarEmail($inscricao, "confirmacao");
                    $inscricao->emailConfirmacaoEnviado = 1;
                    $inscricao->save();
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Pagamento processado com sucesso',
                'inscricao_numero' => $inscricaoNumero
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Dados inválidos',
                'errors' => $e->validator->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao processar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }
}
