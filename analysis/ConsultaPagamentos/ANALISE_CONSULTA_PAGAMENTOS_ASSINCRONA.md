# Análise: Consultas Assíncronas de Transações PagSeguro

## 📋 Resumo Executivo

Este documento apresenta uma análise do sistema atual e propõe soluções para implementar consultas assíncronas de transações do PagSeguro para inscrições não pagas, com intervalos crescentes até a identificação do pagamento.

---

## 🔍 Análise do Sistema Atual

### Estrutura Existente

#### Sistema PHP (Laravel)
- **Framework**: Laravel (versão antiga, usando CRUDBooster)
- **Integração PagSeguro**: Via package `laravel/pagseguro`
- **Sistema de Filas**: Configurado, mas atualmente em modo `sync` (síncrono)
- **Notificações**: Webhook do PagSeguro já funcional via `PagSeguroNotificacao::notificar()`

#### Tabela `inscricoes`
Campos relevantes:
- `numero` (PK): Reference usado no PagSeguro
- `inscricaoPaga` (boolean): Flag de pagamento
- `dataInscricao` (datetime): Data da criação da inscrição
- `pagseguroCode`: Código da transação quando paga
- `pagseguroLink`: Link de pagamento gerado
- `valorInscricao`, `valorInscricaoPago`, `valorTotalPago`: Valores financeiros

#### Sistema C# (Referência)
O repositório `Meuc.CongressoDeFamilias.PagSeguroReader` implementa:
- Consulta à API do PagSeguro: `GET /v2/transactions?email={email}&token={token}&reference={reference}`
- Processamento de XML retornado
- Extração de informações: código, status, valores (bruto, taxa, líquido), método de pagamento

---

## 🎯 Requisitos da Solução

1. **Consultas Assíncronas**: Não bloquear requisições HTTP
2. **Foco**: Apenas inscrições `efetuadas` (existem) e `não pagas` (`inscricaoPaga = 0`)
3. **Intervalo Crescente**: Tempo entre consultas aumenta progressivamente
4. **Parar ao Identificar**: Quando pagamento for encontrado, parar as consultas
5. **Performance**: Não impactar a performance do sistema

---

## 💡 Propostas de Solução

### **Opção 1: Laravel Jobs + Scheduled Commands (RECOMENDADA)**

**Arquitetura:**
- **Job**: `ConsultarTransacaoPagSeguroJob` - executa consulta única
- **Command Agendado**: `ConsultarInscricoesNaoPagas` - roda periodicamente e despacha jobs
- **Tabela de Controle**: Nova tabela `consultas_pagamento` para rastrear tentativas e intervalos

**Fluxo:**
1. Command agendado (ex: a cada 15 minutos) busca inscrições não pagas
2. Para cada inscrição, verifica se precisa consultar (baseado no intervalo crescente)
3. Despacha Job assíncrono para consultar
4. Job consulta API do PagSeguro
5. Se pagamento encontrado → atualiza inscrição e marca consulta como concluída
6. Se não encontrado → agenda próxima consulta com intervalo maior

**Vantagens:**
- ✅ Processamento totalmente assíncrono
- ✅ Controle fino sobre intervalo crescente
- ✅ Escalável (múltiplos workers)
- ✅ Retry automático em caso de falha
- ✅ Integrado ao Laravel (jobs, filas, scheduler)

**Desvantagens:**
- ⚠️ Requer configuração de queue worker
- ⚠️ Necessita tabela adicional para controle

---

### **Opção 2: Scheduled Command Simples**

**Arquitetura:**
- **Command Agendado**: Executa consultas diretamente (síncrono no cron)
- **Tabela de Controle**: `consultas_pagamento` para rastrear tentativas

**Fluxo:**
1. Command roda periodicamente (ex: a cada 30 minutos)
2. Busca inscrições não pagas que precisam ser consultadas
3. Executa consultas em lote (com delay entre elas)
4. Atualiza registros conforme resultados

**Vantagens:**
- ✅ Mais simples de implementar
- ✅ Não requer queue worker
- ✅ Menos dependências

**Desvantagens:**
- ⚠️ Execução síncrona (pode demorar se muitas inscrições)
- ⚠️ Limitado pelo tempo de execução do cron
- ⚠️ Menos controle sobre falhas

---

### **Opção 3: Híbrida - Command + Jobs sob Demanda**

**Arquitetura:**
- **Command Agendado**: Identifica inscrições que precisam consulta
- **Jobs**: Despachados dinamicamente pelo command
- **Tabela de Controle**: `consultas_pagamento`

**Fluxo:**
1. Command roda periodicamente
2. Identifica inscrições elegíveis
3. Despacha Job para cada uma
4. Jobs executam assincronamente

**Vantagens:**
- ✅ Combina simplicidade do command com assíncronismo dos jobs
- ✅ Melhor que Opção 2, mais simples que Opção 1

**Desvantagens:**
- ⚠️ Ainda requer queue worker

---

## 🏗️ Questões de Design e Arquitetura

Para definirmos a melhor abordagem, preciso de orientações sobre:

### **1. Infraestrutura e Deploy**

- **A)** O servidor possui capacidade para rodar queue workers (supervisor/systemd)?
- **B)** Preferência por processamento síncrono (mais simples) ou assíncrono (melhor performance)?
- **C)** Existe Redis ou outro sistema de filas disponível, ou usar fila de banco de dados?

### **2. Estratégia de Intervalos Crescentes**

- **A)** Qual a progressão desejada? Exemplos:
  - Progressão linear: 30min → 1h → 2h → 4h → 8h → 12h → 24h
  - Progressão exponencial: 15min → 30min → 1h → 2h → 4h → 8h → 16h → 24h
  - Progressão customizada?
- **B)** Qual o intervalo máximo? (ex: 24 horas, 48 horas)
- **C)** Após quanto tempo sem pagamento devemos parar de consultar? (ex: 30 dias)

### **3. Filtros e Escopo**

- **A)** Consultar apenas inscrições criadas há mais de X minutos? (evitar consultar recém-criadas)
- **B)** Existe campo `cancelada` na tabela? Devemos excluir inscrições canceladas?
- **C)** Deve considerar algum filtro por evento ou data?

### **4. Tratamento de Resultados**

- **A)** Quando pagamento for identificado, além de marcar como pago, devemos:
  - Enviar email de confirmação? (já existe lógica em `PagSeguroNotificacao`)
  - Registrar no histórico? (já existe `HistoricoPagamento`)
- **B)** Como tratar múltiplas transações para a mesma reference? (pegar a primeira paga? a mais recente?)

### **5. Monitoramento e Logs**

- **A)** Devemos registrar tentativas de consulta (sucesso/falha) em log ou tabela?
- **B)** Precisa de dashboard/admin para ver status das consultas?
- **C)** Alertas para falhas repetidas?

### **6. Integração com Código Existente**

- **A)** Posso criar um Service/ServiceProvider para encapsular a lógica de consulta?
- **B)** Reutilizar código existente do package `laravel/pagseguro` ou fazer requisição HTTP direta (como no C#)?
- **C)** Preferência por manter compatibilidade com notificações webhook existentes ou substituir?

### **7. Performance e Limites**

- **A)** Quantas inscrições não pagas são esperadas simultaneamente? (10? 100? 1000+?)
- **B)** Rate limits da API PagSeguro conhecidos? (quantas requisições/minuto/hora?)
- **C)** Precisa de throttle/rate limiting nas consultas?

---

## 📊 Comparação Rápida das Opções

| Critério | Opção 1 (Jobs) | Opção 2 (Command) | Opção 3 (Híbrida) |
|----------|----------------|-------------------|-------------------|
| Complexidade | Média-Alta | Baixa | Média |
| Performance | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Escalabilidade | ⭐⭐⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐ |
| Dependências | Queue Worker | Apenas Cron | Queue Worker |
| Controle de Intervalos | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Retry/Falhas | ⭐⭐⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐ |

---

## 🔧 Estrutura de Dados Proposta

### Nova Tabela: `consultas_pagamento`

```sql
CREATE TABLE consultas_pagamento (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    inscricao_numero INT NOT NULL,
    ultima_consulta DATETIME NULL,
    proxima_consulta DATETIME NULL,
    tentativas INT DEFAULT 0,
    intervalo_minutos INT DEFAULT 30,
    status ENUM('pendente', 'processando', 'pago', 'cancelado', 'expirado') DEFAULT 'pendente',
    ultimo_erro TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (inscricao_numero) REFERENCES inscricoes(numero),
    INDEX idx_proxima_consulta (proxima_consulta),
    INDEX idx_status (status)
);
```

**Campos:**
- `inscricao_numero`: Referência à inscrição
- `ultima_consulta`: Timestamp da última tentativa
- `proxima_consulta`: Quando deve ser consultada novamente
- `tentativas`: Contador de tentativas
- `intervalo_minutos`: Intervalo atual (cresce a cada tentativa)
- `status`: Estado da consulta
- `ultimo_erro`: Mensagem de erro se houver falha

---

## 📝 Próximos Passos

Após responder as questões acima, seguiremos com:

1. ✅ Definição da arquitetura final
2. ✅ Criação da migration da tabela de controle
3. ✅ Implementação do Service/Job/Command
4. ✅ Integração com código existente
5. ✅ Testes e validação
6. ✅ Documentação

---

## 📚 Referências

- Código C#: `Meuc.CongressoDeFamilias.PagSeguroReader/Program.cs`
- API PagSeguro: `/v2/transactions?email={email}&token={token}&reference={reference}`
- Laravel Jobs: https://laravel.com/docs/queues
- Laravel Scheduling: https://laravel.com/docs/scheduling
