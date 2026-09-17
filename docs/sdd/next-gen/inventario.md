# Inventário Next-gen

Snapshot da wiki em 2026-09-17 e cruzamento com o sistema legado e as issues
do repositório. Nada aqui é decisão nova: é o que já está escrito ou já roda.

## 1. Motivação (wiki)

1. Substituir o motor de pagamentos (PagSeguro legado descontinuado).
2. Preencher lacunas de LGPD.
3. Otimizações e melhorias acumuladas.
4. Stack defasada / descontinuada (risco de segurança).
5. Desejo de sistema **unificado** para vários tipos de evento MEUC,
   cada um com características próprias e um núcleo comum.

Issues que materializam essa motivação:

| Issue | Título | Estado |
|---|---|---|
| #16 | Atualizar Laravel | OPEN |
| #18 | Suportar LGPD | OPEN |
| #25 | Substituir PagSeguro por Asaas | OPEN |

## 2. Funcionalidades da wiki × legado × issues

Legenda de **legado**: `existe` / `parcial` / `não existe`.

### 2.1 Gestão

| Wiki | Legado | Issues / notas |
|---|---|---|
| Gerenciar descontos: percentual | `existe` (`descontos.perc`, prioridade CPF > UF > cidade) | #31 (UF) fechada; #61 prioridade CPF fechada |
| Desconto por CPF e/ou distância | `existe` | Distância hoje = UF ou nome de cidade, não km calculado |
| Desconto progressivo para dependentes adicionais | `não existe` | Novo |
| Crédito de evento anterior (`evento_origem` / `valor_desconto`) | `existe` | **Ausente da wiki** |
| Grupos para vínculo de inscrição | `não existe` | Conceito não modelado. Distinto de equipes de refeição? |
| Itens adicionais: hospedagem com variação por nascimento | `parcial` | `valores` + `valor_variacoes` (idade **e** `data_ate` misturados); alojamento ainda é campo fixo (`CAMPING`, `LAR`, …) |
| Itens adicionais: alimentação com variação por nascimento | `parcial` | Idem; refeição é campo fixo. #17 pede extras genéricos |
| Tipos de evento (flags: desconto, vínculo grupo, individual, resp+dep, resp em nome de dep) | `não existe` | Hoje o produto é um único tipo (família). `tipoInscricao` NORMAL/BANDA/COMITE/STAFF é outra dimensão |
| Termos de uso (aceite + versionamento) | `não existe` | Relacionado a #18 |
| Grupos de participação | `parcial`? | `equipes` / `equipe_membros` existem, mas são operação de evento, não cadastro de grupo de inscrição |
| Credenciais de motor de pagamento | `parcial` | Credenciais globais PagSeguro no `.env`, não por evento |
| Lotes: abertura, encerramento, limite, valor taxa | `parcial` | Lote hoje = `valor_variacoes.data_ate` no código `NORMAL`. Limite é do **evento** (`limite_inscricoes`), não do lote. Flag `aberto` no evento |
| Evento: nome, data, descrição, capa, favicon, endereço | `parcial` | `nome`, `data_inicio`, `data_fim`, `local`, `aberto`, `fila_espera`, `limite_inscricoes`, `limite_refeicoes`, links. Sem capa/favicon/termos/credencial |
| Fila de espera ativa | `parcial` | Flag `fila_espera`. UX atual: mensagem pedindo e-mail para `contato@congressodefamilias.com.br` |
| Duplicar evento (deep clone) | `não existe` | Novo |
| Visualizar inscrições + KPIs | `parcial` | CrudBooster `AdminInscricoesController` (contagens NORMAL/BANDA/COMITE/STAFF, presença, cancelada) |
| Editar / incluir / excluir inscrições | `parcial` | Admin edita; inclusão interna + links seguros. Excluir físico vs `cancelada` é ambíguo (#33, #35, #65) |
| Aprovar alteração em inscrição paga | `não existe` | Novo (wiki + #27 só cobre não paga) |
| Aprovar cancelamento em inscrição paga | `não existe` | Novo; política de reembolso em #28 |
| Gerir fila de espera | `não existe` | Só a mensagem estática |
| Dashboards pré / durante / pós evento | `parcial` | Telas operacionais (Lar, Quiosque, equipes, presença). Sem dashboards nomeados por fase |

### 2.2 Automáticas

| Wiki | Legado | Issues / notas |
|---|---|---|
| Cancelar inscrição não paga | `parcial` | Flag `cancelada` existe; cancelamento automático **não**. #26 (via Asaas). Análise `consultas_pagamento` sugere 7 dias |
| E-mail de confirmação | `existe` | `email_confirmacao_enviado(_at)`. #66: “não responda” + contato congressodefamilias |
| Lembrete de inscrição não paga | `não existe` | Wiki pede; #26 implica notificar a regra na confirmação |

### 2.3 Usuário final (Congresso de Famílias)

| Wiki | Legado | Issues / notas |
|---|---|---|
| Autenticar (OTP sugerido) | `não existe` | Público preenche CPF/dados sem login. Admin = CrudBooster |
| Aceitar termos de uso | `não existe` | #18 |
| Registrar inscrição (titular, dependentes, necessidades especiais, hospedagem, alimentação) | `existe` | Flag `necessidadesEspeciais` (#38). Sem texto livre da necessidade |
| Editar inscrição não paga | `parcial` | Recria/atualiza se CPF já tem inscrição não paga. Sem área logada. #27 |
| Lembrete de pagamento pendente | `não existe` | |
| Consultar status de pagamento | `parcial` | Fluxo PagSeguro: se não paga, redireciona; se paga, informa |
| Solicitar aprovação de alteração paga | `não existe` | |
| Cancelar inscrição não paga | `não existe` no self-service | Admin marca `cancelada` |
| Solicitar cancelamento de inscrição paga | `não existe` | #28 |
| Consultar status de reembolso | `não existe` | #28 |
| Consultar inscrições de eventos anteriores | `parcial` | Pessoa reutilizada por CPF; não há “minha conta” |
| Entrar na fila de espera | `não existe` | Só e-mail manual |
| Consultar status da fila | `não existe` | |
| Solicitar remoção/anonimização | `não existe` | #18 |

### 2.4 Durante o evento (quase ausente na wiki)

O legado tem operação forte que a wiki **não lista** no recorte de usuário
final, só como “dashboard durante evento”:

- Confirmação de presença / check-in (`presencaConfirmada`, `checkin_em`) — #36, #37
- Atribuição de equipe de refeição no check-in (`Inscricao::escolherEquipe`) — `todo.txt`
- Impressão de crachá (`nomecracha`) — #59, #70
- Recalcular idade no check-in — #36 fechada

Decisão necessária: isso entra no next-gen v1 ou fica para fatia posterior?

### 2.5 Presente no legado e **não** citado na wiki

- Links seguros de inscrição (NORMAL/BANDA/COMITE/STAFF, limite, expiração).
- Isenção de camping para COMITE.
- Tipos de pessoa `R` / `C` / dependente; cônjuge vs filhos.
- `limite_refeicoes` no evento.
- Integração C# de consulta PagSeguro (`consultas_pagamento`).
- Histórico de pagamento (`historico_pagamentos`, forma, taxas, líquido).
- Campo `inativo` em pessoa (remoção lógica de dependente) — #35/#65.
- Hospedagem LAR descontinuada no produto (#29), código ainda conhece LAR_*.

## 3. Texto da wiki (snapshot)

Fonte: `https://github.com/MeucDev/inscricoes.wiki.git` / `Next-gen.md`.

```markdown
# Introduçao
Esta página descreve as funcionalidades que farão parte do sistema de
inscrições para eventos da Meuc em sua "next generation".
[…]
# Dados
## Inscrição
- Id
- Evento
- DataInscricao
- PrevisaoaChegada
- Membros
- Adicionais
```

A seção **Dados** está truncada. Não há entidades para Pessoa/Membro, Evento,
Lote, Desconto, Grupo, Termo, Pagamento, Fila, Solicitação, Aceite LGPD.
`PrevisaoaChegada` **não existe** no código atual.

## 4. Stack atual (fato)

- PHP `>=5.6.4`, Laravel `5.3.*`, CrudBooster `v5.4.*`
- PagSeguro via fork `allw/laravel-pagseguro`
- Front: Vue `^2.5`, Gulp 3, Bootstrap Sass 3
- Admin: CrudBooster (módulos gerados + controllers custom)
- Hosting citado no readme: Umbler, clone em `~/public`

Issue #16 sugere Laravel 8/9 e PHP 8. A wiki não escolhe stack.

## 5. O que já está decidido em issues (candidato a herdar)

Estas decisões **não** estão na wiki, mas são específicas o bastante para
entrar no spec se confirmadas no drill:

- **#25 Asaas**, com duas formas (cobrança vs link de pagamento) ainda em aberto.
- **#26** cancelar não paga quando o gateway expirar; avisar na confirmação.
- **#27** usuário autenticado edita/cancela inscrição **não paga**.
- **#28** reembolso: 100% (menos taxas) até 7 dias; 50% da taxa + 100% alimentação até 15 dias antes do evento; regras explícitas no aceite e no cancelamento.
- **#18** página de uso dos dados; remoção; opt-out de guardar dados; dados de anos anteriores só autenticado.
- **#17** itens extras genéricos por categoria (refeição vira um extra, não um campo do evento).
- **#30** famílias do Paraguai (endereço/CPF).
- **#63/#64** gratuidade por substituição de família.
