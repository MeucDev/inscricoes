# Modelo de dados — rascunho Next-gen

A wiki só descreveu o agregado **Inscrição** e parou. Este arquivo propõe um
esqueleto para discussão. Nenhuma tabela abaixo é contrato até o drill P0/P1
ser respondido.

## 1. O que a wiki definiu

```
Inscrição
  - Id
  - Evento
  - DataInscricao
  - PrevisaoaChegada
  - Membros
  - Adicionais
```

Implicação importante: a inscrição deixa de ser “uma linha por pessoa”
(legado) e vira um **agregado familiar/grupo** com membros e adicionais.

## 2. Como o legado persiste hoje

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

Pontos de atrito com a wiki:

1. **Membros na inscrição** vs pessoa global reutilizada por CPF entre anos.
2. **Adicionais** vs colunas `alojamento` / `refeicao`.
3. **Lote** não é entidade; está escondido em variação de preço.
4. **PrevisaoChegada** não existe.
5. Não há entidade de pagamento/cobrança própria (só código PagSeguro na inscrição + histórico).
6. Não há termo, aceite, fila, solicitação de alteração, reembolso, OTP.

## 3. Proposta de agregados (hipótese)

A ser confirmada em D9.* do drill.

```
EventType
  flags: hasDiscount, requiresGroupLink, inscriptionMode
         (individual | responsible_with_dependents | responsible_on_behalf)

Event
  type, termsVersion, paymentCredentialRef
  copy-from (deep clone)
  waitlistEnabled
  extras[] (catalog)
  batches[] (lotes)
  participationGroups[]
  linkGroups[]          # “grupos para vínculo de inscrição”

Batch (lote)
  opensAt, closesAt, capacity, feeAmount

CatalogItem (adicional)
  category (lodging | meal | other)
  priceVariations[] (by age range and/or until date)

DiscountRule
  percent | progressiveDependents | byTaxId | byDistance | previousEventCredit

Person (identidade)
  contacts, taxId?, locale (BR/PY), lgpdConsent[]

Inscription (agregado)
  event, batch, status, createdAt, estimatedArrival
  members[]
    personRef or snapshot
    role (holder | spouse | dependent | …)
    selectedExtras[]
    badgeName, specialNeeds
  pricingSnapshot
  payment[] / refund[]
  changeRequests[]
  cancellationRequest?

WaitlistEntry
  event, person/family, status, createdAt

TermsDocument
  version, body, effectiveAt
  acceptances[] (person, version, at, ip)

PaymentCredential
  provider, event-scoped secrets
```

## 4. Estados de inscrição (hipótese)

A wiki lista ações, não a máquina de estados. Hipótese para D6/D3:

```
rascunho? → aguardando_pagamento → paga
                 ↓                    ↓
            cancelada_nao_paga   solicitacao_alteracao
                                 solicitacao_cancelamento
                                      ↓
                                 reembolso_pendente → reembolsada
                                 (ou indeferida → paga)
```

Fila de espera é **outra** entidade, não um status da inscrição — a menos que
D6.1 diga o contrário.

## 5. Lacunas explícitas da seção Dados

Preencher depois do drill (não inventar agora):

- [ ] Pessoa / Membro (campos, CPF opcional, Paraguai, complemento de endereço)
- [ ] Snapshot vs identidade viva
- [ ] Catálogo de adicionais e variações
- [ ] Lote
- [ ] Desconto (incluindo progressivo)
- [ ] Grupo de vínculo × grupo de participação
- [ ] Termo e aceite
- [ ] Credencial de pagamento e cobrança
- [ ] Fila de espera
- [ ] Solicitação de alteração / cancelamento / reembolso
- [ ] OTP / sessão
- [ ] Auditoria LGPD (pedido de anonimização)
- [ ] Presença / equipe / crachá (se v1 incluir operação do evento)
- [ ] KPIs: quais fatos precisam estar no modelo para os três dashboards
