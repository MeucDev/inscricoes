# API PHP - Documentação de Pagamento

Documentação da API REST para confirmação de pagamentos via processo C# externo.

## 📋 Visão Geral

A API `/api/pagamento/confirmar` permite que o processo C# externo confirme pagamentos identificados no PagSeguro, atualizando o sistema PHP, registrando no histórico de pagamentos e enviando emails de confirmação.

## 🔐 Autenticação

A API utiliza autenticação por API Key através do header `X-API-Key`.

### Configuração

1. Configure a API key no arquivo `.env`:
   ```
   PAGAMENTO_API_KEY=sua-chave-secreta-aqui
   ```

2. Em produção, após alterar o `.env`, execute:
   ```bash
   php artisan config:cache
   ```

3. A API key também deve estar configurada no sistema C# (variável `API_PHP_KEY` ou `appsettings.json`).

## 📍 Endpoint

**URL:** `POST /api/pagamento/confirmar`

**Base URL:** Configurada no sistema C# (ex: `https://seu-dominio.com.br`)

## 📤 Requisição

### Headers

```
Content-Type: application/json
X-API-Key: sua-api-key-aqui
```

### Payload (JSON)

```json
{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 500.00,
  "status": 4,
  "valorLiquido": 485.00,
  "valorTaxas": 15.00,
  "formaPagamento": "Cartão de crédito"
}
```

#### Campos Obrigatórios

- `inscricao_numero` (integer, min: 1): Número da inscrição
- `pagseguro_code` (string, max: 255): Código da transação no PagSeguro
- `valor` (numeric, min: 0): Valor bruto da transação
- `status` (integer, in: 3,4): Status da transação (3=Paga, 4=Disponível)

#### Campos Opcionais

- `valorLiquido` (numeric, min: 0): Valor líquido recebido (após taxas)
- `valorTaxas` (numeric, min: 0): Valor das taxas cobradas
- `formaPagamento` (string, max: 50): Descrição da forma de pagamento (ex: "Cartão de crédito", "PIX", "Boleto")

### Exemplo de Requisição (cURL)

```bash
curl -X POST https://seu-dominio.com.br/api/pagamento/confirmar \
  -H "Content-Type: application/json" \
  -H "X-API-Key: sua-api-key-aqui" \
  -d '{
    "inscricao_numero": 12345,
    "pagseguro_code": "ABC123DEF456",
    "valor": 500.00,
    "status": 4,
    "valorLiquido": 485.00,
    "valorTaxas": 15.00,
    "formaPagamento": "Cartão de crédito"
  }'
```

### Exemplo de Requisição (PowerShell)

```powershell
$headers = @{
    "Content-Type" = "application/json"
    "X-API-Key" = "sua-api-key-aqui"
}

$body = @{
    inscricao_numero = 12345
    pagseguro_code = "ABC123DEF456"
    valor = 500.00
    status = 4
    valorLiquido = 485.00
    valorTaxas = 15.00
    formaPagamento = "Cartão de crédito"
} | ConvertTo-Json

Invoke-RestMethod -Uri "https://seu-dominio.com.br/api/pagamento/confirmar" `
    -Method Post `
    -Headers $headers `
    -Body $body
```

## 📥 Respostas

### Sucesso (200 OK)

```json
{
  "success": true,
  "message": "Pagamento processado com sucesso"
}
```

**Ações realizadas:**
1. Se a inscrição **ainda não estava paga**:
   - Inscrição marcada como paga (`inscricaoPaga = 1`)
   - Valores atualizados na inscrição
   - Registro criado no histórico de pagamentos (`historico_pagamentos`) com operação "APROVADO"
2. Se a inscrição **já estava paga**:
   - Inscrição mantida como paga e valores atualizados (bruto e código PagSeguro)
   - Último registro `APROVADO` em `historico_pagamentos` é atualizado com:
     - `valor` (bruto)
     - `valorLiquido` (se enviado e diferente)
     - `valorTaxas` (se enviado e diferente)
     - `formaPagamento` (se enviada e diferente)
   - Caso não exista registro `APROVADO`, um novo registro é criado com esses dados
3. Email de confirmação é enviado **apenas uma vez** por inscrição:
   - Primeiro envio marca `emailConfirmacaoEnviado = 1` na tabela `inscricoes`
   - Chamadas futuras não reenviam o email se `emailConfirmacaoEnviado = 1`

### Erro: Não Autorizado (401 Unauthorized)

```json
{
  "success": false,
  "error": "Unauthorized. Invalid API key."
}
```

**Causas:**
- Header `X-API-Key` ausente ou inválido
- API key não configurada no `.env` ou `config/services.php`
- Em produção: `config:cache` não executado após alterar `.env`

### Erro: Validação (422 Unprocessable Entity)

```json
{
  "success": false,
  "error": "Validation failed",
  "errors": {
    "inscricao_numero": ["The inscricao_numero field is required."],
    "status": ["The selected status is invalid."]
  }
}
```

**Campos de validação:**
- `inscricao_numero`: Obrigatório, inteiro, mínimo 1
- `pagseguro_code`: Obrigatório, string, máximo 255 caracteres
- `valor`: Obrigatório, numérico, mínimo 0
- `status`: Obrigatório, inteiro, valores aceitos: 3 ou 4
- `valorLiquido`: Opcional, numérico, mínimo 0
- `valorTaxas`: Opcional, numérico, mínimo 0
- `formaPagamento`: Opcional, string, máximo 50 caracteres

### Erro: Inscrição não encontrada (404 Not Found)

```json
{
  "success": false,
  "error": "Inscrição não encontrada"
}
```

**Causa:** O número da inscrição informado não existe no banco de dados.

### Erro: Inscrição já paga (409 Conflict)

```json
{
  "success": false,
  "error": "Inscrição já está marcada como paga"
}
```

**Causa:** A inscrição já foi marcada como paga anteriormente. Isso pode acontecer se:
- A inscrição foi confirmada manualmente
- O processo C# já confirmou anteriormente
- Webhook do PagSeguro processou antes

### Erro: Servidor (500 Internal Server Error)

```json
{
  "success": false,
  "error": "Erro interno do servidor"
}
```

**Causas possíveis:**
- Erro ao conectar ao banco de dados
- Erro ao enviar email
- Erro inesperado no processamento

## 🔄 Fluxo de Processamento

1. **Validação da API Key**: Middleware `ApiKeyAuth` verifica o header `X-API-Key`
2. **Validação do Payload**: Laravel valida os dados conforme regras definidas
3. **Busca da Inscrição**: Sistema busca a inscrição no banco de dados
4. **Verificação de Status**: Verifica se a inscrição já está paga
5. **Atualização da Inscrição**: 
   - Marca como paga (`inscricaoPaga = 1`)
   - Atualiza valores (`valorInscricaoPago`, `valorTotalPago`, `pagseguroCode`)
6. **Registro no Histórico**: Cria registro em `historico_pagamentos` com operação "APROVADO"
7. **Envio de Email**: Envia email de confirmação usando o sistema de email configurado

## 🗄️ Estrutura do Banco de Dados

### Tabela: `inscricoes`

Campos atualizados pela API:
- `inscricaoPaga` → `1`
- `valorInscricaoPago` → Valor do payload
- `valorTotalPago` → Valor do payload
- `pagseguroCode` → Código do PagSeguro do payload

### Tabela: `historico_pagamentos`

Novo registro criado:
- `inscricao_numero`: Número da inscrição
- `operacao`: "APROVADO"
- `valor`: Valor bruto
- `pagseguro_code`: Código da transação
- `valorLiquido`: Valor líquido (se informado)
- `valorTaxas`: Valor das taxas (se informado)
- `formaPagamento`: Forma de pagamento (se informada)
- `created_at`: Timestamp atual

## 🧪 Testes

Para testar a API, consulte o arquivo `API_TESTE_PAGAMENTO.md` que contém exemplos detalhados de requisições usando cURL, PowerShell e Postman.

## 🔒 Segurança

- **API Key**: Use uma chave forte e mantenha-a segura
- **HTTPS**: Sempre use HTTPS em produção
- **Rate Limiting**: Considere implementar rate limiting se necessário
- **Logs**: Monitore logs para detectar tentativas de acesso não autorizadas
- **Validação**: Todos os dados são validados antes do processamento

## 🐛 Troubleshooting

### Erro 401 mesmo com API key configurada

1. Verifique se o `.env` tem a variável `PAGAMENTO_API_KEY`
2. Em produção, execute `php artisan config:cache`
3. Verifique se o middleware `ApiKeyAuth` está registrado na rota
4. Confirme que o header está sendo enviado corretamente (case-sensitive: `X-API-Key`)

### Erro 422 (Validação)

1. Verifique o formato JSON do payload
2. Confirme que todos os campos obrigatórios estão presentes
3. Verifique tipos de dados (números devem ser numéricos, não strings)
4. Em PowerShell, use `ConvertTo-Json` para garantir formato correto

### Email não é enviado

1. Verifique configuração de email no Laravel (`.env`)
2. Verifique logs do Laravel para erros de envio
3. Confirme que o sistema de email está funcionando (teste manual)

### Inscrição não é atualizada

1. Verifique logs do Laravel para erros de banco de dados
2. Confirme que a inscrição existe no banco
3. Verifique permissões de escrita no banco de dados

## 📝 Notas Adicionais

- A API é idempotente: chamadas duplicadas com os mesmos dados não causam erro (a inscrição já estará paga)
- O processo C# implementa retry automático (backoff exponencial: 1s, 2s, 4s)
- Em caso de erro, o processo C# registra no log e aguarda próxima execução
- O sistema PHP valida se a inscrição já está paga antes de processar
