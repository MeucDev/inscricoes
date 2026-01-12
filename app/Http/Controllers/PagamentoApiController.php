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

            // Verificar se já está paga (evitar duplicação)
            if ($inscricao->inscricaoPaga) {
                return response()->json([
                    'success' => false,
                    'error' => 'Inscrição já foi paga',
                    'inscricao_numero' => $inscricaoNumero
                ], 409);
            }

            // Verificar status (3 ou 4 = pago)
            if ($status != 3 && $status != 4) {
                return response()->json([
                    'success' => false,
                    'error' => 'Status inválido. Apenas status 3 ou 4 são considerados pagos.',
                    'status' => $status
                ], 400);
            }

            // Processar pagamento dentro de transação
            DB::transaction(function() use ($inscricao, $valor, $pagseguroCode, $status, $valorLiquido, $valorTaxas, $formaPagamento, $inscricaoNumero) {
                // Atualizar inscrição
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

                // Enviar email de confirmação
                PagSeguroNotificacao::enviarEmail($inscricao, "confirmacao");
            });

            return response()->json([
                'success' => true,
                'message' => 'Pagamento confirmado com sucesso',
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
