# Spec — Inscrições Next Gen

Status: **rascunho com espinha fechada** (drill Q1–Q12). Origem: wiki Next-gen + respostas da rodada 1 + inventário do legado. Itens `[ABERTO]` não podem ser implementados.

## 1. Problema

Substituir o motor de pagamentos **já inoperante** (PagSeguro legado caiu dois dias antes do último evento; os últimos pagamentos foram Pix externo), fechar lacunas de LGPD, sair da stack descontinuada, e criar um sistema novo de inscrições configurável por tipo de evento da Meuc.

## 2. Objetivos

- Permitir inscrever, pagar, alterar (com regras) e cancelar participação em eventos da Meuc.
- Configurar eventos por tipo (desconto, vínculo a grupo, modo de inscrição, itens, lotes, fila, termos, **motor e credenciais** de pagamento).
- Automatizar confirmação, lembrete e cancelamento de inscrição não paga (prazo **configurável no evento**).
- Operar o evento: check-in, crachá, grupos operacionais, equipes de refeição, links seguros.
- Dar à gestão KPIs, edição, inclusão/exclusão, aprovação de mudanças pós-pagamento, fila e relatórios pré/durante/pós evento.
- Cumprir LGPD: aceite versionado e pedido de remoção/anonimização.
- Consultar inscrições de eventos anteriores (após migração opcional).

## 3. Recorte da v1

**Produto v1:** Congresso de Famílias (primeiro consumidor).

**Modelo v1 já genérico**, sem fork, para pelo menos:

| Tipo / evento | Modo de inscrição | Vínculo com grupo |
|---|---|---|
| Congresso de Famílias | Responsável **participa**, com dependentes (Q5-B) | Não exige |
| Evento de menores | Responsável **não participa**; inscreve só menores; não paga taxa própria (Q5-A) | `[ABERTO na wiki]` |
| Congresso de Jovens | `[ABERTO: individual vs núcleo]` | **Exige** vínculo na inscrição |

Unificar de fato outros sistemas no dia 1 **não** é objetivo da v1. App nativo **não** é objetivo.

**Sistema novo (greenfield).** Este repositório não é a base da v1. Migração de pessoas + inscrições históricas existe como **scripts à parte**, que a operação decide rodar ou não.

**Calendário:** janela de inscrições em **janeiro**, evento em **abril**. Stack da v1 fica para rodada própria (Q11-B); domínio primeiro.

## 4. Atores

| Ator | Papel |
|---|---|
| Participante / responsável | Inscreve, paga, consulta, solicita alteração/cancelamento/anonimização |
| Gestor do evento | Configura evento, vê inscrições, aprova, opera fila, check-in, crachá e relatórios |
| Sistema | Cancela não pagas no prazo do evento, e-mails, consulta pagamento |
| Motor de pagamento (plugável) | Cobra, confirma, recusa, reembolsa — o **evento** escolhe o motor e as credenciais |

Papéis admin (financeiro, credenciamento, comitê) **não** estão na wiki. `[ABERTO]` Tesouraria vs qualquer admin na aprovação de reembolso: rodada 2 / wiki.

## 5. Tipos de evento

Configuráveis (não são o mesmo que grupos operacionais):

- Possui desconto
- Exige vínculo com grupo (ex.: Congresso de Jovens; Congresso de Famílias **não**)
- Modo de inscrição — pelo menos:
  - **Responsável participa** (Congresso de Famílias)
  - **Responsável em nome de menores**: responsável não vai ao evento, não paga taxa própria, inscreve dependentes menores (requisito principal de um dos eventos a incluir)
  - Inscrição individual `[ABERTO: entra no modelo v1?]`

`[ABERTO]` Flags combináveis vs um modo exclusivo por tipo. A rodada 1 trata modo de inscrição e “exige vínculo” como dimensões distintas de um tipo.

`[ABERTO]` No modo menores: o responsável é Usuário/Pessoa com login, mas **não** é membro participante da inscrição?

## 6. Evento

Gestão configura:

- Informações gerais: nome, data, descrição, capa, favicon, endereço, etc. `[ABERTO: lista fechada de campos]`
- Tipo de evento
- Termos de uso (versão)
- **Motor de pagamento + credenciais** (conta financeira daquele evento)
- Prazo de cancelamento automático de inscrição não paga
- Itens adicionais
- Lotes
- Fila de espera ativa
- Grupos operacionais (staff, banda, comitê, …) com regras de prioridade/gratuidade `[ABERTO: detalhe na wiki]`
- Deep clone a partir de evento anterior `[ABERTO: o que o clone copia — inscrições? credenciais? termos?]`

## 7. Lotes

Por lote: data de abertura, data de encerramento, limite de inscrições, valor da taxa.

`[ABERTO]` Relação com o legado, onde “lote” é variação de preço por `data_ate` no valor `NORMAL`, e o limite é do evento inteiro (só responsáveis NORMAL).

`[ABERTO]` O limite conta inscrições (núcleos), pessoas físicas, ou vagas de hospedagem?

`[ABERTO]` Inscrição iniciada no lote A e paga no lote B: qual valor vale?

`[ABERTO]` Grupos operacionais com prioridade de inscrição furam o lote / o limite / só o “ainda não aberto”?

## 8. Itens adicionais

Hospedagem e alimentação, com valores que variam por data de nascimento (idade).

`[ABERTO]` Itens são catálogo global vinculados ao evento, ou só do evento?

`[ABERTO]` Um membro pode ter 0..1 hospedagem e 0..1 alimentação, ou N itens?

`[ABERTO]` Regras atuais (LAR implica refeição NENHUMA; COMITE+CAMPING = R$ 0) seguem ou morrem?

## 9. Descontos

- Percentual
- Aplicação por CPF e/ou distância
- Progressivo para dependentes adicionais
- Gratuidades / descontos ligados a **grupo operacional** (staff, banda, comitê, …) — fechado como necessidade; fórmula `[ABERTO]`

`[ABERTO]` Precedência e empilhamento (hoje CPF vence UF/cidade; desconto de evento anterior é valor, não %).

`[ABERTO]` Distância = UF/cidade como hoje, ou km a partir do endereço do evento?

`[ABERTO]` Progressivo: a partir de qual dependente, qual curva, aplica na taxa e/ou nos itens?

`[ABERTO]` Desconto de evento anterior (crédito do valor pago) entra na Next Gen?

## 10. Grupos (dois conceitos distintos)

### 10.1 Vínculo de inscrição

Atributo do **tipo de evento**. Ex.: Congresso de Jovens exige vínculo (igreja/núcleo/grupo de origem) na inscrição. Congresso de Famílias não exige.

### 10.2 Grupos operacionais (participação / diferenciação)

Não são o vínculo da inscrição. Servem para **controle operacional**: tamanho de staff, banda, comitê, etc. Alguns desses grupos têm **prioridade de inscrição** e **gratuidades/descontos**.

No legado isso aparece como `tipoInscricao` NORMAL|BANDA|COMITE|STAFF + links seguros que furam limite.

`[ABERTO]` Cadastro desses grupos por evento vs tipos fixos. Como uma inscrição entra no grupo (link seguro, convite, gestão). Como a prioridade interage com lote/limite/fila. Equipes de refeição (QUIOSQUE_A/B, LAR_A/B) são um terceiro conceito (alocação no check-in) ou o mesmo?

## 11. Identidade e autenticação (participante)

- OTP “parece ser a forma de menor atrito”.
- Aceite de termos de uso (marcar + versionar).

`[ABERTO]` Canal do OTP: e-mail, SMS, WhatsApp.

`[ABERTO]` Identificador da conta: e-mail, telefone, CPF, ou combinação.

`[ABERTO]` Pessoa (cadastro) vs Usuário (login): um usuário gerencia várias pessoas? Cônjuge tem login próprio? No modo menores, o login é só do responsável.

## 12. Jornada do participante (Congresso de Famílias)

Fluxo listado na wiki, agora com cobrança na finalização:

1. Autenticar
2. Aceitar termos
3. Registrar inscrição em evento ativo (titular, dependentes, necessidades especiais, hospedagem, alimentação)
4. **Ao finalizar, nasce a cobrança** (PIX, cartão ou boleto, via motor do evento)
5. Editar inscrição **não paga**
6. Receber lembrete de pagamento
7. Consultar status de pagamento
8. Se o prazo do evento expirar sem pagamento → cancelamento automático
9. Solicitar alteração de inscrição **já paga** (gestão aprova)
10. Cancelar inscrição não paga
11. Solicitar cancelamento de inscrição já paga
12. Consultar status de reembolso
13. Consultar inscrições de eventos anteriores
14. Entrar na fila de espera se esgotado
15. Consultar status da fila
16. Solicitar remoção/anonimização de dados

`[ABERTO]` “Evento ativo” = lote vigente + evento aberto + não encerrado?

`[ABERTO]` Necessidades especiais: flag ou texto? Por membro?

`[ABERTO]` Previsão de chegada (campo na seção Dados) é obrigatória? Por inscrição ou por membro?

## 13. Pagamento

### Fechado (rodada 1)

- Camada **pluggable**. Cada evento define **qual motor** e **quais credenciais** (contas diferentes para controle financeiro externo).
- Candidatos concretos (primeiro adapter ainda `[ABERTO]`): PagSeguro na versão atual/suportada **ou** Asaas (já há integração Asaas em outro sistema da Meuc; issue #25).
- Meios na v1: **PIX + cartão + boleto**.
- Cobrança **ao finalizar** a inscrição (não há rascunho sem cobrança).
- Inscrição não paga é cancelada automaticamente se o pagamento não ocorrer no **prazo configurável no evento**.
- Após pago, alteração/cancelamento são solicitações; reembolso tem status consultável.

### Ainda aberto

- Qual adapter implementar primeiro (PagSeguro atual vs Asaas), e se a v1 já precisa dos dois.
- Asaas: link de pagamento vs cobrança+customer (#25).
- Mecanismo do cancelamento automático: job interno, vencimento/webhook do gateway, ou o que ocorrer primeiro.
- Cadência do e-mail de lembrete.
- Política de reembolso (#28 e wiki).
- Alteração paga que aumenta/diminui valor: 2ª cobrança / reembolso parcial?
- Split, nota fiscal: fora, até a wiki dizer o contrário.

## 14. Fila de espera

- Evento pode ter fila ativa
- Participante registra interesse quando esgotado
- Consulta status da solicitação
- Gestão gere a fila

`[ABERTO]` Ordenação (FIFO vs prioridade de grupo operacional), conversão em inscrição (prazo para pagar?), limite da fila, dados coletados no interesse.

## 15. LGPD

- Termos versionados + aceite
- Solicitação de remoção/anonimização

`[ABERTO]` Anonimizar vs apagar. Inscrição paga/fiscal pode ser apagada? Prazo de retenção. Quem executa o pedido (automático vs gestão). Escopo: só o solicitante ou o núcleo familiar.

## 16. Gestão de inscrições e operação do evento (v1)

Gestão:

- Visualizar + KPIs `[ABERTO: quais KPIs]`
- Editar / incluir / excluir `[ABERTO: excluir paga?]`
- Aprovar alteração e cancelamento pós-pagamento
- Relatórios pré, durante e pós evento `[ABERTO: conteúdo de cada um]`
- Gerir fila de espera
- Gerir grupos operacionais e links seguros de inscrição

Operação no evento (aceito na v1; detalhe na wiki/rodada 2):

- Check-in / presença
- Crachá
- Equipes de refeição
- Tipos/grupos BANDA, COMITE, STAFF (e equivalentes configuráveis)

## 17. Automações

- Cancelar inscrição não paga no prazo **configurável no evento**. `[ABERTO: se libera vaga/lote/fila, se avisa, job vs gateway]`
- E-mail de confirmação `[ABERTO: após criar ou após pagar?]`
- E-mail de lembrete de não paga `[ABERTO: cadência]`

## 18. Modelo de dados

**Agregado fechado (Q4):** 1 inscrição = núcleo (responsável + membros), **1 cobrança**, **1 status de pagamento**.

Campos iniciados na wiki:

- Id, Evento, DataInscricao, PrevisaoChegada, Membros, Adicionais

No modo “responsável em nome de menores”, o responsável autenticado pode **não** ser membro participante.

Ver `modelo-dados.md`.

## 19. Requisitos não funcionais

- **Greenfield.** Stack `[ABERTO — rodada própria]`.
- Go-live: inscrições em janeiro, evento em abril.
- PagSeguro legado não é opção.
- Volume, hospedagem, SLA do checkout, e-mail, PII, backup: `[ABERTO]`.

## 20. Migração

Scripts à parte, **não** acoplados ao deploy da v1:

- Pessoas
- Inscrições históricas (para “consultar eventos anteriores”)

A operação decide se e quando roda. `[ABERTO]` mapeamento legado (1 linha/pessoa) → agregado núcleo; o que fazer com pagamentos Pix externo do último evento.
