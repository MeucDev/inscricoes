# Plano de Implementação - Consultas Assíncronas PagSeguro

## 📋 Resumo das Decisões

### Estratégia de Intervalos
- Progressão: **0 (imediato), 15min, 30min, 1h, 4h, 24h**
- Primeira consulta: Imediata na primeira execução após encontrar inscrição
- Intervalo máximo: 24h
- Tempo limite: 7 dias (cancelamento automático, configurável)

### Persistência e Concorrência
- Tabela `consultas_pagamento` no MySQL
- Campo `processando` (BOOLEAN) para lock
- Processo C# cria registros quando encontra inscrição elegível

### Filtros
- Apenas responsáveis (`numero_inscricao_responsavel IS NULL`)
- Criadas há mais de 3 minutos
- Não pagas (`inscricaoPaga = 0`) E sem registro APROVADO no histórico
- Não canceladas (`cancelada = 0`)
- Apenas eventos abertos (`eventos.aberto = 1`)

### Autenticação
- API Key via header `X-API-Key`

### Execução
- Console Application (roda uma vez e encerra)
- Execução manual via linha de comando
- Frequência: A cada hora (agendamento externo)

### Logs
- Formato: JSON
- Rotação: Por dia (`pagseguro-consultas-YYYY-MM-DD.log`)
- Nível: Error (configurável)
- Localização: `logs/` relativo ao executável

### Retry
- API PHP: 3 tentativas com backoff exponencial (1s, 2s, 4s)
- API PagSeguro: 1 retry, senão aguarda próxima iteração

### Payload API
- Campos opcionais: `valorLiquido`, `valorTaxas`, `formaPagamento`
- `formaPagamento`: Valores do código C# atual (strings):
  - "Cartão de crédito" (type 1)
  - "Boleto" (type 2)
  - "Débito online" (type 3)
  - "Saldo PagSeguro" (type 4)
  - "Oi Paggo" (type 5)
  - "PIX" (type 7)
  - "Desconhecido" (default)
- PHP infere pagamento pelo status (3 ou 4)
- Campo `formaPagamento`: VARCHAR(50) na tabela historico_pagamentos

---

## 🎯 Fases de Implementação

### **FASE 1: Estrutura de Dados (MySQL)**
**Objetivo:** Criar tabelas e migrations necessárias

#### Tarefa 1.1: Migration `consultas_pagamento`
- [ ] Criar migration `create_consultas_pagamento_table.php`
- [ ] Estrutura conforme aprovada (com campo `processando`)
- [ ] Testar migration (up/down)

#### Tarefa 1.2: Migration `historico_pagamentos` (adicionar colunas)
- [ ] Criar migration `add_campos_historico_pagamentos.php`
- [ ] Adicionar: `valorLiquido`, `valorTaxas`, `formaPagamento`
- [ ] Testar migration (up/down)

#### Tarefa 1.3: Atualizar Model `HistoricoPagamento`
- [ ] Adicionar campos ao fillable/mass assignment
- [ ] Atualizar método `registrar()` para aceitar novos campos opcionais

---

### **FASE 2: API REST no Laravel**
**Objetivo:** Endpoint para receber confirmações de pagamento do processo C#

#### Tarefa 2.1: Middleware de Autenticação
- [ ] Criar middleware `ApiKeyAuth`
- [ ] Validar header `X-API-Key`
- [ ] Comparar com valor em `.env` ou config
- [ ] Retornar 401 se inválido

#### Tarefa 2.2: Controller `PagamentoApiController`
- [ ] Criar controller
- [ ] Método `confirmar()` para receber POST
- [ ] Validação de payload (Request Validation)
- [ ] Sanitização de inputs

#### Tarefa 2.3: Validações e Lógica de Negócio
- [ ] Validar formato dos dados (tipos, ranges)
- [ ] Verificar se inscrição existe
- [ ] Verificar se já está paga (evitar duplicação)
- [ ] Verificar status (3 ou 4 = pago)
- [ ] Infere pagamento pelo status

#### Tarefa 2.4: Integração com Código Existente
- [ ] Reutilizar lógica de `PagSeguroNotificacao::notificar()`
- [ ] Atualizar `HistoricoPagamento::registrar()` com novos campos
- [ ] Enviar email (reutilizar `PagSeguroNotificacao::enviarEmail()`)
- [ ] Atualizar inscrição (marcar como paga, valores, código)

#### Tarefa 2.5: Rota API
- [ ] Adicionar rota `POST /api/pagamento/confirmar`
- [ ] Aplicar middleware de autenticação
- [ ] Aplicar middleware de API (sem CSRF)
- [ ] Testar endpoint manualmente

#### Tarefa 2.6: Testes Manuais da API
- [ ] Testar com payload válido
- [ ] Testar com API key inválida
- [ ] Testar com inscrição já paga
- [ ] Testar com inscrição inexistente
- [ ] Verificar emails enviados
- [ ] Verificar histórico de pagamentos

---

### **FASE 3: Processo C# - Estrutura Base**
**Objetivo:** Preparar estrutura do projeto C#

#### Tarefa 3.1: Dependências NuGet
- [ ] Adicionar `MySqlConnector` ou `MySql.Data`
- [ ] Adicionar `Microsoft.Extensions.Configuration.EnvironmentVariables`
- [ ] Adicionar `Microsoft.Extensions.Logging.Console` (se necessário)
- [ ] Verificar/atualizar dependências existentes

#### Tarefa 3.2: Estrutura de Pastas e Arquivos
- [ ] Criar pasta `Models/`
- [ ] Criar pasta `Services/`
- [ ] Criar pasta `Data/` (Repositories)
- [ ] Criar pasta `Config/`
- [ ] Organizar estrutura conforme proposta

#### Tarefa 3.3: Configuração (appsettings.json)
- [ ] Criar estrutura completa de configuração
- [ ] Database connection string
- [ ] PagSeguro (BaseUrl, Email, Token)
- [ ] API PHP (BaseUrl, Endpoint, ApiKey, Timeout)
- [ ] Consulta (intervalos, progressão, tempo máximo, cancelamento)
- [ ] Logging (Level, LogFile)

#### Tarefa 3.4: Modelos de Dados
- [ ] Criar `InscricaoNaoPaga.cs` (modelo para consulta MySQL)
- [ ] Criar `TransacaoPagSeguro.cs` (modelo XML resposta)
- [ ] Criar `ConsultaPagamento.cs` (modelo tabela consultas_pagamento)
- [ ] Criar `AppConfig.cs` (configurações tipadas)

---

### **FASE 4: Processo C# - Serviços Core**
**Objetivo:** Implementar lógica principal de consultas

#### Tarefa 4.1: Repository `ConsultaPagamentoRepository`
- [ ] Método para buscar inscrições elegíveis (filtros)
- [ ] Método para criar registro (INSERT)
- [ ] Método para atualizar registro (UPDATE)
- [ ] Método para lock (`processando = TRUE`)
- [ ] Método para unlock (`processando = FALSE`)
- [ ] Método para buscar próximas consultas (`proxima_consulta <= NOW()`)

#### Tarefa 4.2: Service `InscricaoService`
- [ ] Buscar inscrições não pagas do MySQL
- [ ] Aplicar filtros (responsável, tempo, não paga, não cancelada, evento aberto)
- [ ] Verificar histórico (sem APROVADO)
- [ ] Retornar lista de referências

#### Tarefa 4.3: Service `PagSeguroService`
- [ ] Consultar API PagSeguro por reference
- [ ] Parse XML de resposta
- [ ] Identificar transações com status 3 ou 4
- [ ] Ordenar por mais recente
- [ ] Retry (1 tentativa)
- [ ] Extrair: código, status, valores (bruto, líquido, taxas), forma pagamento

#### Tarefa 4.4: Service `ApiService`
- [ ] Chamar API PHP (`POST /api/pagamento/confirmar`)
- [ ] Construir payload JSON
- [ ] Backoff exponencial (3 tentativas: 1s, 2s, 4s)
- [ ] Tratamento de erros HTTP
- [ ] Logging de chamadas

---

### **FASE 5: Processo C# - Lógica de Intervalos e Orquestração**
**Objetivo:** Implementar lógica de intervalos crescentes e orquestração

#### Tarefa 5.1: Service `ConsultaPagamentoService`
- [ ] Calcular próximo intervalo (progressão: 0, 15, 30, 60, 240, 1440)
- [ ] Criar registro na primeira consulta (intervalo 0)
- [ ] Atualizar `proxima_consulta` após consulta
- [ ] Incrementar `tentativas`
- [ ] Gerenciar status (pendente, pago, cancelado)

#### Tarefa 5.2: Lógica de Cancelamento
- [ ] Verificar inscrições com mais de 7 dias (configurável)
- [ ] Cancelar responsável + dependentes
- [ ] Atualizar status para 'cancelado'
- [ ] Marcar `cancelada = 1` no MySQL
- [ ] Respeitar flag de configuração (cancelar após X dias)

#### Tarefa 5.3: Orquestração Principal (`Program.cs`)
- [ ] Loop principal (buscar próximas consultas)
- [ ] Lock/unlock de registros
- [ ] Chamar serviços na ordem correta
- [ ] Tratamento de exceções
- [ ] Logging estruturado (JSON)
- [ ] Encerrar após processar tudo

#### Tarefa 5.4: Sistema de Logging
- [ ] Configurar logging JSON
- [ ] Rotação por dia
- [ ] Nível configurável (default: Error)
- [ ] Localização: `logs/pagseguro-consultas-YYYY-MM-DD.log`
- [ ] Logs de: consultas, erros, sucessos, cancelamentos

---

### **FASE 6: Integração e Testes**
**Objetivo:** Integrar componentes e testar end-to-end

#### Tarefa 6.1: Testes Unitários (C#)
- [ ] Testes de serviços (mocks)
- [ ] Testes de lógica de intervalos
- [ ] Testes de cálculo de progressão
- [ ] Testes de cancelamento

#### Tarefa 6.2: Testes de Integração
- [ ] Teste completo: MySQL → PagSeguro → API PHP
- [ ] Teste com múltiplas inscrições
- [ ] Teste de concorrência (lock/unlock)
- [ ] Teste de retry e backoff
- [ ] Teste de cancelamento após 7 dias

#### Tarefa 6.3: Testes de Carga
- [ ] Teste com pico inicial (450 inscrições)
- [ ] Verificar performance
- [ ] Verificar rate limits PagSeguro
- [ ] Ajustar delays se necessário

#### Tarefa 6.4: Testes End-to-End (Cenários Reais)
- [ ] Criar inscrição de teste
- [ ] Executar processo C#
- [ ] Verificar consulta no PagSeguro
- [ ] Verificar atualização no PHP
- [ ] Verificar email enviado
- [ ] Verificar histórico
- [ ] Testar múltiplas execuções (intervalos)

---

### **FASE 7: Documentação e Deployment**
**Objetivo:** Documentar e preparar para produção

#### Tarefa 7.1: Documentação Técnica
- [ ] README do processo C#
- [ ] Instruções de configuração (appsettings.json, env vars)
- [ ] Instruções de execução
- [ ] Documentação da API PHP
- [ ] Diagrama de fluxo

#### Tarefa 7.2: Documentação de Deployment
- [ ] Como configurar variáveis de ambiente
- [ ] Como executar manualmente
- [ ] Como configurar agendamento (Windows Task Scheduler exemplo)
- [ ] Como monitorar logs
- [ ] Troubleshooting comum

#### Tarefa 7.3: Validação Final
- [ ] Revisar todas as tarefas concluídas
- [ ] Testes finais em ambiente de staging
- [ ] Checklist de deployment
- [ ] Preparar rollback plan

---

## 📊 Ordem de Execução Recomendada

A implementação pode ser feita em paralelo em alguns pontos:

```
FASE 1 (MySQL) ──────────┐
                         │
FASE 2 (API PHP) ────────┼──► FASE 6 (Testes)
                         │
FASE 3 (C# Base) ────────┤
FASE 4 (C# Services) ────┤
FASE 5 (C# Orquestração)─┘

FASE 7 (Documentação) ← Pode começar após FASE 2 e FASE 5
```

**Recomendação de Execução:**
1. **FASE 1** primeiro (fundação)
2. **FASE 2** em paralelo com **FASE 3** (independentes)
3. **FASE 4** depois de FASE 3
4. **FASE 5** depois de FASE 4
5. **FASE 6** após todas as fases anteriores
6. **FASE 7** pode começar após FASE 2 e FASE 5

---

## ✅ Checklist Geral

### Preparação
- [ ] Ambiente de desenvolvimento configurado
- [ ] Acesso ao banco de dados MySQL
- [ ] Credenciais PagSeguro (sandbox/produção)
- [ ] URL do sistema PHP configurada

### Migrations
- [ ] Migration `consultas_pagamento` criada e testada
- [ ] Migration `historico_pagamentos` criada e testada
- [ ] Migrations aplicadas em ambiente de desenvolvimento

### API PHP
- [ ] Controller criado e funcionando
- [ ] Autenticação funcionando
- [ ] Validações implementadas
- [ ] Integração com código existente
- [ ] Testes manuais passando

### Processo C#
- [ ] Estrutura criada
- [ ] Configuração funcionando
- [ ] Serviços implementados
- [ ] Lógica de intervalos funcionando
- [ ] Logging funcionando
- [ ] Testes passando

### Integração
- [ ] Testes end-to-end passando
- [ ] Testes de carga realizados
- [ ] Performance validada

### Documentação
- [ ] README criado
- [ ] Instruções de deployment
- [ ] Troubleshooting documentado

---

## 🚀 Próximos Passos

Aguardando suas instruções para iniciar a implementação gradual.

**Sugestão de início:**
1. Começar pela **FASE 1** (Migrations) - fundamento para tudo
2. Depois **FASE 2** (API PHP) - pode ser testada independentemente
3. Em paralelo ou depois, **FASE 3** (Estrutura C#)

Qual fase você gostaria que eu inicie primeiro?
