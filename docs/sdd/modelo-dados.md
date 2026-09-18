# Modelo de dados — rascunho Next-gen

Status: espinha alinhada à rodada 1. Campos e catálogos ainda `[ABERTO]`.

## 1. Fechado na rodada 1

- **Inscrição** é agregado de **núcleo** (não 1 linha por pessoa).
- 1 inscrição → 1 cobrança → 1 status de pagamento.
- **Tipo de evento** define modo de inscrição e se exige vínculo.
- **Dois grupos distintos:** vínculo (origem/igreja) vs operacional (staff/banda/comitê, com prioridade/gratuidade).
- **PaymentCredential** é por evento: `provider` + segredos da conta.
- Operação v1: presença, crachá, equipes de refeição, links seguros, grupos operacionais.
- Não há estado `rascunho` sem cobrança: ao finalizar já existe cobrança.

## 2. O que a wiki definiu

```
Inscrição
  - Id
  - Evento
  - DataInscricao
  - PrevisaoChegada
  - Membros
  - Adicionais
```

## 3. Como o legado persiste hoje

```
pessoas 1 ──< inscricoes (uma linha por pessoa, por evento)
   │                │
   │                ├── numero_inscricao_responsavel (auto-relação)
   │                ├── tipoInscricao NORMAL|BANDA|COMITE|STAFF
   │                ├── alojamento, refeicao, equipeRefeicao (campos)
   │                ├── valores copiados (valorInscricao, valorAlojamento, …)
   │                ├── inscricaoPaga, cancelada, presencaConfirmada, checkin_em
   │                └── link_inscricao_id
   │
   ├── conjuge_id
   └── responsavel_id

eventos 1 ──< valores 1 ──< valor_variacoes (data_ate OU faixa etária)
eventos 1 ──< descontos (perc | valor_desconto, cpf, nome=UF/cidade, evento_origem)
eventos 1 ──< link_inscricoes
eventos 1 ──< equipes 1 ──< equipe_membros
inscricoes 1 ──< historico_pagamentos
```

Mapeamento de migração (script à parte): N linhas de inscrição do mesmo responsável → 1 agregado núcleo. `[ABERTO]` regras de conflito (dependente pago vs responsável não, Pix externo, etc.).

## 4. Agregados (hipótese atualizada)

```
EventType
  hasDiscount: bool
  requiresGroupLink: bool          # Jovens = true; Famílias = false
  inscriptionMode:
    responsible_participates       # Congresso de Famílias
    | responsible_on_behalf_minors # responsável não é membro participante
    | individual                   # [ABERTO se v1]

Event
  type
  termsVersion
  paymentProvider                  # asaas | pagseguro | …
  paymentCredentialRef             # conta daquele evento
  unpaidCancelAfter                # prazo configurável
  waitlistEnabled
  extras[]
  batches[]
  operationalGroups[]              # staff, banda, comitê, … + prioridade/gratuidade
  linkGroups[]                     # vínculo de origem, se o tipo exigir
  mealTeams[]                      # [ABERTO: separado de operationalGroups?]

Batch
  opensAt, closesAt, capacity, feeAmount

CatalogItem
  category (lodging | meal | other)
  priceVariations[] (age and/or until date)

DiscountRule
  percent | progressiveDependents | byTaxId | byDistance
  | previousEventCredit | operationalGroupBenefit

Person
  contacts, taxId?, locale (BR/PY), lgpdConsent[]

User
  login (OTP) → Person?
  # modo menores: User é o responsável; pode não haver Member correspondente

Inscription
  event, batch, status, createdAt, estimatedArrival
  accountableUser                  # quem se autentica / paga
  groupLink?                       # se o tipo exigir
  operationalGroup?                # staff/banda/comitê; pode vir de SecureLink
  members[]
    personRef or snapshot
    role (holder | spouse | dependent | minor | …)
    participates: bool             # false para responsável no modo menores
    selectedExtras[]
    badgeName, specialNeeds, checkInAt?
  pricingSnapshot
  charges[]                        # 1 no fluxo feliz; 2ª se alteração aumentar valor [ABERTO]
  refunds[]
  changeRequests[]
  cancellationRequest?

SecureLink
  event, operationalGroup?, usageLimit, expiresAt
  # legado: fura limite do evento

WaitlistEntry
  event, inscription-or-user, status, createdAt

TermsDocument
  version, body, effectiveAt
  acceptances[] (user/person, version, at, ip)

PaymentCredential
  provider, event-scoped secrets
```

## 5. Estados de inscrição

```
aguardando_pagamento → paga
        │                 ├── solicitacao_alteracao → (aprovada: paga | 2ª cobrança [ABERTO])
        │                 └── solicitacao_cancelamento
        │                          ↓
        │                    reembolso_pendente → reembolsada
        │                    (ou indeferida → paga)
        ↓
cancelada_nao_paga   (prazo do evento esgotou, ou cancelamento pelo usuário)
```

Fila de espera é **outra** entidade.

## 6. Lacunas explícitas

- [ ] Pessoa / Membro (campos, CPF opcional, Paraguai, complemento)
- [ ] Snapshot vs identidade viva (Q40)
- [ ] Catálogo de adicionais e variações
- [ ] Lote / o que o `capacity` conta
- [ ] Desconto progressivo e empilhamento com grupo operacional
- [ ] Vínculo vs grupo operacional vs equipe de refeição
- [ ] Termo e aceite
- [ ] Primeiro `provider` e formato da cobrança
- [ ] Fila de espera
- [ ] Solicitação de alteração / cancelamento / reembolso
- [ ] OTP / sessão
- [ ] Auditoria LGPD
- [ ] KPIs: fatos para os três dashboards
- [ ] Mapeamento do script de migração
