# Confirmações e Questões Adicionais - Antes da Implementação

## ✅ Respostas Recebidas - Resumo

### 1. Estratégia de Intervalos
- Progressão: **0, 15min, 30min, 1h, 4h, 24h**
- Intervalo máximo: **24h**
- Tempo limite: **7 dias** (após isso, marcar como cancelada)

### 2. Persistência
- **Tabela `consultas_pagamento`** no MySQL
- Lógica para permitir concorrência

### 3. Filtros
- Apenas **responsáveis** (não dependentes)
- Criadas há **mais de 3 minutos**
- Não pagas (inscricaoPaga = 0) E sem registro APROVADO no histórico
- Não canceladas (cancelada = 0)
- Apenas eventos marcados como **"aberto"**

### 4. Autenticação
- **API Key** (header X-API-Key)

### 5. Múltiplas Transações
- Ordenar por mais recente
- Considerar paga quando encontrar status 3 ou 4

### 6. Concorrência
- Filtros no C# garantem não consultar já pagas
- PHP valida antes de enviar email (webhook)
- **Pendente:** Confirmar proposta de lock/flag na tabela

### 7. Execução
- Console Application
- Execução periódica (a cada hora, início manual)

### 8. Logs
- Arquivo de log local
- Sem dashboard

### 9. Retry
- API PHP: Backoff exponencial
- API PagSeguro: 1 retry, senão aguarda próxima iteração

### 10. Configuração
- env vars + appsettings.json (prioridade para env vars)
- Credenciais em ambos

### 11. Requisitos Adicionais
- Adicionar colunas: `valorLiquido`, `valorTaxas`, `formaPagamento` na tabela `historico_pagamentos`
- API PHP deve receber esses campos no payload

---

## ❓ Questões Adicionais para Confirmação

### **Q1: Progressão "0" na primeira consulta**

A progressão mencionada é: **0, 15min, 30min, 1h, 4h, 24h**

**Pergunta:** O "0" significa:
- **Opção A:** Consulta imediata quando a inscrição é criada? (registro criado com próxima consulta = NOW())
- **Opção B:** Primeira consulta acontece após 15 minutos? (o "0" é apenas uma marcação)
- **Opção C:** Consulta inicial imediata, depois segue a progressão?

**Minha sugestão:** Opção C (consulta imediata na primeira execução após criação, depois segue progressão)

---

### **Q2: Criação de registros na tabela `consultas_pagamento`**

**Pergunta:** Quando criar o registro na tabela `consultas_pagamento`?

- **Opção A:** Na criação da inscrição (no PHP, quando `PagSeguroIntegracao::gerarPagamento()` é chamado)
- **Opção B:** Quando o processo C# encontra uma inscrição não paga (primeira consulta)
- **Opção C:** Criar registros em lote quando o processo C# inicia

**Minha sugestão:** Opção B (processo C# cria quando encontra inscrição elegível pela primeira vez)

**Racional:** Mantém o sistema PHP simples, e o processo C# tem controle total sobre quando criar os registros.

---

### **Q3: Lock/Flag na tabela `consultas_pagamento` para concorrência**

**Proposta de Lock/Flag:**

Adicionar campo `processando` (BOOLEAN) ou `locked_at` (DATETIME NULL) na tabela:

**Opção A - Campo `processando` (BOOLEAN):**
```sql
processando BOOLEAN DEFAULT FALSE
```
- Processo C# faz `UPDATE consultas_pagamento SET processando = TRUE WHERE id = ? AND processando = FALSE`
- Se afetou 1 linha, processa; senão, pula (outro processo está processando)
- Após processar, faz `UPDATE ... SET processando = FALSE`

**Opção B - Campo `locked_at` (DATETIME):**
```sql
locked_at DATETIME NULL
```
- Processo C# faz `UPDATE ... SET locked_at = NOW() WHERE id = ? AND (locked_at IS NULL OR locked_at < DATE_SUB(NOW(), INTERVAL 10 MINUTE))`
- Se afetou 1 linha, processa; senão, pula
- Após processar, faz `UPDATE ... SET locked_at = NULL`

**Minha sugestão:** Opção A (mais simples, campo booleano)

**Pergunta:** Qual abordagem prefere? Ou tem outra sugestão?

---

### **Q4: Campo "aberto" na tabela eventos**

✅ **CONFIRMADO:** Campo `aberto` (TINYINT) existe na tabela `eventos`
- Valor 1 = aberto
- Valor 0 = fechado
- Filtro SQL: `eventos.aberto = 1`

---

### **Q5: Marcar como cancelada após 7 dias**

**Pergunta:** Onde implementar a lógica de cancelamento após 7 dias?

- **Opção A:** No processo C# (verifica na execução periódica)
- **Opção B:** No PHP (command agendado ou quando consultas são feitas)
- **Opção C:** Em ambos (C# marca flag, PHP valida)

**Minha sugestão:** Opção A (processo C# faz o cancelamento quando detecta 7 dias sem pagamento)

**Pergunta adicional:** Quando marcar como cancelada:
- Apenas a inscrição do responsável?
- Inscrição do responsável + todos os dependentes?
- Outra regra?

**Minha sugestão:** Responsável + todos dependentes (como mencionado na resposta Q1.c)

---

### **Q6: Estrutura da tabela `consultas_pagamento`**

**Proposta de estrutura:**

```sql
CREATE TABLE consultas_pagamento (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    inscricao_numero INT NOT NULL,
    ultima_consulta DATETIME NULL,
    proxima_consulta DATETIME NOT NULL,
    tentativas INT DEFAULT 0,
    intervalo_minutos INT DEFAULT 0, -- 0, 15, 30, 60, 240, 1440
    status ENUM('pendente', 'processando', 'pago', 'cancelado', 'expirado') DEFAULT 'pendente',
    processando BOOLEAN DEFAULT FALSE, -- Lock para concorrência
    ultimo_erro TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (inscricao_numero) REFERENCES inscricoes(numero) ON DELETE CASCADE,
    UNIQUE KEY uk_inscricao (inscricao_numero),
    INDEX idx_proxima_consulta (proxima_consulta, status),
    INDEX idx_status_processando (status, processando),
    INDEX idx_proxima_consulta_status (proxima_consulta, status, processando)
);
```

**Perguntas:**
1. Estrutura está adequada?
2. `UNIQUE KEY uk_inscricao` - garantir apenas 1 registro por inscrição? (evita duplicatas)
3. Campos adicionais necessários?

---

### **Q7: Migration para `historico_pagamentos`**

**Campos a adicionar:**
- `valorLiquido` DECIMAL(10,2) NULL
- `valorTaxas` DECIMAL(10,2) NULL  
- `formaPagamento` VARCHAR(100) NULL

**Perguntas:**
1. `valorLiquido` e `valorTaxas` podem ser NULL? (para registros antigos)
2. `formaPagamento` - qual tamanho máximo? Alguma enum/validação?
3. Ordem das colunas? (sugestão: após `valor`)

**Proposta de migration:**
```php
Schema::table('historico_pagamentos', function (Blueprint $table) {
    $table->decimal('valorLiquido', 10, 2)->nullable()->after('valor');
    $table->decimal('valorTaxas', 10, 2)->nullable()->after('valorLiquido');
    $table->string('formaPagamento', 100)->nullable()->after('valorTaxas');
});
```

---

### **Q8: Payload da API PHP**

**Payload atual proposto:**
```json
{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 150.00,
  "status": 3
}
```

**Payload atualizado (com novos campos):**
```json
{
  "inscricao_numero": 12345,
  "pagseguro_code": "ABC123DEF456",
  "valor": 150.00,
  "valorLiquido": 145.50,
  "valorTaxas": 4.50,
  "status": 3,
  "formaPagamento": "Cartão de Crédito"
}
```

**Perguntas:**
1. Campos `valorLiquido`, `valorTaxas`, `formaPagamento` são obrigatórios ou opcionais?
2. `formaPagamento` - valores esperados? (ex: "Cartão de Crédito", "Boleto", "PIX", etc.)
3. Ordem dos campos está adequada?

---

### **Q9: Execução periódica - Frequência e Lógica**

**Confirmação:** Execução a cada hora, início manual

**Perguntas:**
1. O processo C# deve:
   - **Opção A:** Rodar uma vez e encerrar (cron/task chama novamente a cada hora)
   - **Opção B:** Ficar em loop, aguardando 1 hora entre execuções

2. Para o MVP, início manual significa:
   - Executar via linha de comando quando necessário?
   - Ou configurar como task agendada no Windows?

**Minha sugestão:** Opção A (processo roda uma vez, cron/task agenda próximas execuções)

---

### **Q10: Backoff exponencial para API PHP**

**Pergunta:** Detalhes do backoff exponencial:

- Quantas tentativas máximas?
- Intervalos sugeridos: 1s, 2s, 4s, 8s, 16s?
- Após esgotar tentativas, aguardar próxima iteração do processo?

**Minha sugestão:**
- Máximo 3 tentativas
- Backoff: 1s, 2s, 4s
- Após 3 falhas, aguardar próxima iteração

---

### **Q11: Logs do processo C#**

**Perguntas:**
1. Formato do log: texto simples, JSON, ou outro?
2. Rotação de logs: por tamanho, por dia, ou outro?
3. Nível de log: Information, Warning, Error, Debug?
4. Localização: `logs/` no diretório da aplicação?

**Minha sugestão:**
- Formato: texto simples com timestamp
- Rotação: por dia (arquivo: `pagseguro-consultas-YYYY-MM-DD.log`)
- Nível: Information (padrão), Warning, Error
- Localização: `logs/` relativo ao executável

---

### **Q12: Validações adicionais na API PHP**

**Perguntas:**
1. Validações de segurança:
   - Rate limiting? (quantas chamadas por minuto/hora?)
   - Validação de formato de dados?
   - Sanitização de inputs?

2. Validações de negócio:
   - Verificar se inscrição existe?
   - Verificar se já está paga? (como mencionado)
   - Verificar valores (valor >= valorLiquido + valorTaxas)?
   - Outras validações?

---

## ✅ Informações Confirmadas do Código

- ✅ Campo `aberto` (TINYINT) existe na tabela `eventos` (valor 1 = aberto)
- ✅ Campo `cancelada` (TINYINT, default 0) existe na tabela `inscricoes`
- ✅ Inscrições responsáveis: `numero_inscricao_responsavel IS NULL`
- ✅ Dependentes: `numero_inscricao_responsavel = numero_do_responsavel`

---

## 📝 Checklist de Confirmação

Antes de iniciar a implementação, preciso das seguintes confirmações:

- [ ] Q1: Progressão "0" - qual interpretação?
- [ ] Q2: Quando criar registros em `consultas_pagamento`?
- [ ] Q3: Lock/flag para concorrência (proposta A ou B)?
- [x] Q4: Nome do campo "aberto" na tabela eventos ✅ CONFIRMADO
- [ ] Q5: Onde implementar cancelamento após 7 dias + confirmação
- [ ] Q6: Estrutura da tabela `consultas_pagamento` (aprovada ou ajustes?)
- [ ] Q7: Migration `historico_pagamentos` (campos e tipos)
- [ ] Q8: Payload da API (campos obrigatórios/opcionais)
- [ ] Q9: Detalhes da execução periódica
- [ ] Q10: Detalhes do backoff exponencial
- [ ] Q11: Formato e rotação de logs
- [ ] Q12: Validações adicionais na API

---

## 🎯 Após Confirmações

Após suas respostas, criarei:
1. **Plano de Implementação Detalhado** (com fases e tarefas)
2. **Iniciar implementação gradual** conforme o plano
