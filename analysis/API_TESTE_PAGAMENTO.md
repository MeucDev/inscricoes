# Exemplos de Requisições - API de Confirmação de Pagamento

## Endpoint

**POST** `/api/pagamento/confirmar`

**URL Completa:** `http://localhost/api/pagamento/confirmar`  
*(Ajuste conforme seu ambiente: http://seu-dominio.com.br/api/pagamento/confirmar)*

---

## Headers Necessários

```
Content-Type: application/json
X-API-Key: seu-token-secreto-aqui
```

**Importante:** Substitua `seu-token-secreto-aqui` pelo valor definido em `PAGAMENTO_API_KEY` no arquivo `.env`

---

## Exemplo 1: Payload Completo (com campos opcionais)

### cURL

```bash
curl -X POST http://localhost:8000/api/pagamento/confirmar \
  -H "Content-Type: application/json" \
  -H "X-API-Key: mYYPLPCFQUofoBVglMjMKrrmZ3N44CDY" \
  -d '{
    "inscricao_numero": 12345,
    "pagseguro_code": "ABC123DEF456",
    "valor": 150.00,
    "status": 3,
    "valorLiquido": 145.50,
    "valorTaxas": 4.50,
    "formaPagamento": "Cartão de crédito"
  }'
```

### PowerShell (Windows)

**Opção 1: JSON Manual (Recomendado)**

```powershell
$body = '{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 150.00,
  "status": 3,
  "valorLiquido": 145.50,
  "valorTaxas": 4.50,
  "formaPagamento": "Cartão de crédito"
}'

Invoke-RestMethod -Uri "http://localhost:8000/api/pagamento/confirmar" `
  -Method Post `
  -ContentType "application/json" `
  -Headers @{"X-API-Key" = "mYYPLPCFQUofoBVglMjMKrrmZ3N44CDY"} `
  -Body $body
```

**Opção 2: ConvertTo-Json (com tratamento de erro)**

```powershell
try {
    $body = @{
        inscricao_numero = 12345
        pagseguro_code = "ABC123DEF456"
        valor = 150.00
        status = 3
        valorLiquido = 145.50
        valorTaxas = 4.50
        formaPagamento = "Cartão de crédito"
    } | ConvertTo-Json -Depth 10 -Compress

    Invoke-RestMethod -Uri "http://localhost:8000/api/pagamento/confirmar" `
      -Method Post `
      -Headers @{
        "Content-Type" = "application/json"
        "X-API-Key" = "mYYPLPCFQUofoBVglMjMKrrmZ3N44CDY"
      } `
      -Body $body
} catch {
    Write-Host "Erro:" -ForegroundColor Red
    $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
    $reader.BaseStream.Position = 0
    $reader.DiscardBufferedData()
    $responseBody = $reader.ReadToEnd()
    Write-Host $responseBody
}
```

### Postman

1. **Method:** POST
2. **URL:** `http://localhost/api/pagamento/confirmar`
3. **Headers:**
   - `Content-Type`: `application/json`
   - `X-API-Key`: `seu-token-secreto-aqui`
4. **Body** (raw JSON):
```json
{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 150.00,
  "status": 3,
  "valorLiquido": 145.50,
  "valorTaxas": 4.50,
  "formaPagamento": "Cartão de crédito"
}
```

---

## Exemplo 2: Payload Mínimo (sem campos opcionais)

### cURL

```bash
curl -X POST http://localhost/api/pagamento/confirmar \
  -H "Content-Type: application/json" \
  -H "X-API-Key: seu-token-secreto-aqui" \
  -d '{
    "inscricao_numero": 12345,
    "pagseguro_code": "ABC123DEF456",
    "valor": 150.00,
    "status": 3
  }'
```

### Postman

**Body** (raw JSON):
```json
{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 150.00,
  "status": 3
}
```

---

## Exemplo 3: Status 4 (Disponível)

```bash
curl -X POST http://localhost/api/pagamento/confirmar \
  -H "Content-Type: application/json" \
  -H "X-API-Key: seu-token-secreto-aqui" \
  -d '{
    "inscricao_numero": 12345,
    "pagseguro_code": "ABC123DEF456",
    "valor": 150.00,
    "status": 4,
    "valorLiquido": 145.50,
    "valorTaxas": 4.50,
    "formaPagamento": "Boleto"
  }'
```

---

## Respostas Esperadas

### ✅ Sucesso (200)

```json
{
  "success": true,
  "message": "Pagamento confirmado com sucesso",
  "inscricao_numero": 12345
}
```

### ❌ Erro - API Key Inválida (401)

```json
{
  "success": false,
  "error": "Unauthorized. Invalid API key."
}
```

### ❌ Erro - Inscrição Não Encontrada (404)

```json
{
  "success": false,
  "error": "Inscrição não encontrada",
  "inscricao_numero": 99999
}
```

### ❌ Erro - Inscrição Já Paga (409)

```json
{
  "success": false,
  "error": "Inscrição já foi paga",
  "inscricao_numero": 12345
}
```

### ❌ Erro - Status Inválido (400)

```json
{
  "success": false,
  "error": "Status inválido. Apenas status 3 ou 4 são considerados pagos.",
  "status": 1
}
```

### ❌ Erro - Validação (422)

```json
{
  "success": false,
  "error": "Dados inválidos",
  "errors": {
    "inscricao_numero": ["The inscricao_numero field is required."],
    "valor": ["The valor must be a number."]
  }
}
```

---

## Valores Válidos

### Status
- `3`: Paga (transação foi paga e PagSeguro recebeu confirmação)
- `4`: Disponível (transação foi paga e chegou ao final do prazo de liberação)

### formaPagamento (opcional)
Valores aceitos (strings):
- `"Cartão de crédito"` (type 1)
- `"Boleto"` (type 2)
- `"Débito online"` (type 3)
- `"Saldo PagSeguro"` (type 4)
- `"Oi Paggo"` (type 5)
- `"PIX"` (type 7)
- `"Desconhecido"` (default)

---

## Campos do Payload

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `inscricao_numero` | integer | Sim | Número da inscrição |
| `pagseguro_code` | string | Sim | Código da transação PagSeguro |
| `valor` | numeric | Sim | Valor bruto da transação (min: 0) |
| `status` | integer | Sim | Status da transação (3 ou 4) |
| `valorLiquido` | numeric | Não | Valor líquido (após taxas) |
| `valorTaxas` | numeric | Não | Valor das taxas |
| `formaPagamento` | string | Não | Forma de pagamento (max: 50 caracteres) |

---

## Notas Importantes

1. **API Key:** Configure `PAGAMENTO_API_KEY` no arquivo `.env` antes de testar
2. **Inscrição:** Use um número de inscrição válido e não pago do seu banco de dados
3. **Status:** Apenas status `3` ou `4` são aceitos como pagos
4. **Duplicação:** A API verifica se a inscrição já está paga para evitar duplicação
5. **Transação:** Todas as operações são executadas dentro de uma transação do banco

---

## Teste Rápido

Para testar rapidamente, você pode usar um número de inscrição de teste do seu banco:

```sql
-- Verificar inscrições não pagas disponíveis para teste
SELECT numero, inscricaoPaga, valorTotal 
FROM inscricoes 
WHERE inscricaoPaga = 0 
  AND numero_inscricao_responsavel IS NULL
LIMIT 5;
```

Use um dos números retornados no campo `inscricao_numero` da requisição.
