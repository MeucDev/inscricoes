# Debug - Erro 422 na API

## Para Ver o Erro Completo no PowerShell

Quando receber erro 422, use este comando para ver a mensagem de erro completa:

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
    } | ConvertTo-Json

    Invoke-RestMethod -Uri "http://localhost:8000/api/pagamento/confirmar" `
      -Method Post `
      -Headers @{
        "Content-Type" = "application/json"
        "X-API-Key" = "seu-token-secreto-aqui"
      } `
      -Body $body
} catch {
    $_.Exception.Response
    $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
    $reader.BaseStream.Position = 0
    $reader.DiscardBufferedData()
    $responseBody = $reader.ReadToEnd()
    Write-Host "Resposta do servidor:" -ForegroundColor Red
    Write-Host $responseBody -ForegroundColor Yellow
}
```

## Problemas Comuns no PowerShell

### 1. ConvertTo-Json adiciona espaços/chars extras

O PowerShell `ConvertTo-Json` pode adicionar formatação extra. Use `-Depth` e `-Compress`:

```powershell
$body = @{
    inscricao_numero = 12345
    pagseguro_code = "ABC123DEF456"
    valor = 150.00
    status = 3
} | ConvertTo-Json -Depth 10 -Compress

Write-Host "Body sendo enviado:" -ForegroundColor Cyan
Write-Host $body -ForegroundColor White

Invoke-RestMethod -Uri "http://localhost:8000/api/pagamento/confirmar" `
  -Method Post `
  -Headers @{
    "Content-Type" = "application/json"
    "X-API-Key" = "seu-token-secreto-aqui"
  } `
  -Body $body
```

### 2. Valores numéricos como strings

Certifique-se de que números são enviados como números, não strings:

```powershell
# ❌ ERRADO - valor será string
$body = '{"inscricao_numero": "12345", "valor": "150.00"}'

# ✅ CORRETO - valor será número
$body = '{"inscricao_numero": 12345, "valor": 150.00}'
```

### 3. Versão Mais Simples (JSON Manual)

Se o ConvertTo-Json estiver causando problemas, use JSON manual:

```powershell
$body = '{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 150.00,
  "status": 3
}'

Invoke-RestMethod -Uri "http://localhost:8000/api/pagamento/confirmar" `
  -Method Post `
  -ContentType "application/json" `
  -Headers @{"X-API-Key" = "seu-token-secreto-aqui"} `
  -Body $body
```

## Validações que Podem Falhar

A API valida:

1. **inscricao_numero**: `required|integer|min:1`
2. **pagseguro_code**: `required|string|max:255`
3. **valor**: `required|numeric|min:0`
4. **status**: `required|integer|in:3,4` (deve ser exatamente 3 ou 4)
5. **valorLiquido**: `nullable|numeric|min:0` (opcional)
6. **valorTaxas**: `nullable|numeric|min:0` (opcional)
7. **formaPagamento**: `nullable|string|max:50` (opcional)

## Teste Simplificado

Teste primeiro com payload mínimo:

```powershell
$body = '{"inscricao_numero":12345,"pagseguro_code":"ABC123","valor":150.00,"status":3}'

Invoke-RestMethod -Uri "http://localhost:8000/api/pagamento/confirmar" `
  -Method Post `
  -ContentType "application/json" `
  -Headers @{"X-API-Key" = "seu-token-secreto-aqui"} `
  -Body $body
```
