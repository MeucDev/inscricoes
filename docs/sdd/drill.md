# Drill — Inscrições Next Gen

Perguntas em rodadas. Responder por número (`Q1 = A`, texto livre se a opção não couber). Cada resposta fecha o item na `spec.md`.

Convenção: **sugerido** = palpite informado pelo legado/wiki/issues; **não é decisão**.

---

## Rodada 1 — Espinha (em andamento)

Estas respostas definem o recorte da v1, o agregado de inscrição e o pagamento. Sem elas, as próximas rodadas (preço, fila, LGPD, telas) se perdem.

### Q1. Recorte da primeira entrega

A wiki fala em unificar vários sistemas de eventos da Meuc, mas lista jornada só para o Congresso de Famílias.

- **A.** v1 = só Congresso de Famílias, com tipos de evento já genéricos no modelo
- **B.** v1 = plataforma já usada por ≥2 eventos reais da Meuc
- **C.** Outro recorte (descrever)

**Sugerido:** A. Unificação como desenho; Congresso como primeiro consumidor.

Quais outros eventos/sistemas entram no horizonte (mesmo que não no v1)? A wiki não nomeia. Issue #17 cita Retiro de Crianças e Congresso de Jovens; #25 cita DI.

### Q2. Relação com este repositório

- **A.** Sistema novo (greenfield), legado só como referência e fonte de migração
- **B.** Evolução deste Laravel (strangler / módulos novos no mesmo app)
- **C.** Novo app + este legado continua até o próximo Congresso migrar

**Sugerido:** C, se o próximo Congresso tem data próxima demais para big-bang.

### Q3. Migração de dados

- **A.** Migrar pessoas + inscrições históricas (eventos anteriores consultáveis)
- **B.** Migrar só cadastro de pessoas (CPF/contato); inscrições antigas ficam no legado
- **C.** Sem migração; Next Gen começa zerado

A wiki pede “consultar detalhes de inscrições em eventos anteriores”.

**Sugerido:** A se Q1 = Congresso; senão B.

### Q4. Agregado “Inscrição”

Hoje: 1 linha por pessoa, responsável amarra dependentes. Wiki: Inscrição tem Membros e Adicionais.

- **A.** 1 inscrição = núcleo (responsável + membros), 1 cobrança, 1 status de pagamento
- **B.** Manter 1 inscrição por pessoa, com agrupamento só na UI/cobrança
- **C.** Híbrido (descrever)

**Sugerido:** A — alinha com alteração/cancelamento/reembolso da wiki.

### Q5. “Responsável em nome de dependentes”

Modo de tipo de evento que não existe no legado.

- **A.** Responsável não participa do evento; só inscreve menores/dependentes; não paga taxa própria
- **B.** Responsável participa e também pode inscrever pessoas que não vão com ele (ex.: pastor inscreve jovens)
- **C.** Ainda não precisa na v1; tirar da spec até ter evento real

**Sugerido:** C até existir evento-alvo; ou A se for o caso de menores.

### Q6. Os dois “grupos” da wiki

- **A.** Vínculo = igreja/núcleo/igreja local obrigatório em alguns eventos; participação = turma/equipe no evento (refeição, voluntariado)
- **B.** É a mesma entidade
- **C.** Explicar com exemplos reais da Meuc

**Sugerido:** A, com participação podendo ficar fora da v1 se for só operação de Congresso (equipes de refeição).

### Q7. Motor de pagamento da v1

Motivação original da wiki; ainda sem provedor no texto. Issue **#25** propõe **Asaas**, com duas formas (link de pagamento vs cobrança+customer) em aberto.

- **A.** PagSeguro (API atual, não o legado)
- **B.** Mercado Pago
- **C.** PIX direto (banco/PSP) + cartão depois
- **D.** Camada interna pluggable; v1 com um provedor concreto (qual? Asaas?)
- **E.** Outro

**Sugerido:** D com Asaas, para não repetir o lock-in atual e aproveitar o trabalho já pensado em #25.

### Q8. Meios de pagamento na v1

- **A.** Só PIX
- **B.** PIX + cartão
- **C.** PIX + cartão + boleto (como o PagSeguro atual cobre)
- **D.** Outro

### Q9. Quando a cobrança nasce

- **A.** Ao finalizar a inscrição (como hoje: gera link na hora)
- **B.** Inscrição fica rascunho; usuário dispara “pagar”
- **C.** Reserva de vaga por N horas; depois cancela se não pagar

Combina com o cancelamento automático da wiki e com #26.

### Q10. Destino das features operacionais do Congresso que a wiki não lista

Check-in, crachá, equipes de refeição, tipos BANDA/COMITE/STAFF, links seguros de inscrição.

- **A.** Entram na v1 (especificar na rodada 2)
- **B.** v1 é só inscrição+pagamento+gestão da wiki; operação no evento fica para v1.1
- **C.** Mix: listar o que é inegociável no próximo Congresso

**Sugerido:** C — sem isso o Congresso não opera, mesmo com checkout novo.

### Q11. Stack da v1 `[pode ficar para rodada 2 se Q2 = A]`

- **A.** Decidir stack agora (descrever preferência)
- **B.** Fechar domínio primeiro; stack numa rodada própria
- **C.** Já existe decisão fora da wiki (apontar — há `docs/ANALISE_TRADEOFF_EVOLUCAO_STACK.md` só na máquina local)

**Sugerido:** B, depois de Q1–Q4.

### Q12. Deadline real

Qual a data do próximo evento que **não** pode mais usar PagSeguro legado? Há um Congresso intermediário ainda no sistema atual?

Sem isso, Q2/Q10/Q11 são chute.

---

## Rodada 2 — (bloqueada até Q1–Q12)

Preço/lotes/descontos, fila, LGPD, KPIs, e-mails, modelo de conta OTP, campos de Evento/Pessoa. Perguntas já preparadas abaixo para a próxima sessão; **não precisam ser respondidas agora**.

### Preço e lotes

- **Q13.** Lote first-class (abertura, encerramento, limite, taxa) vs variação `data_ate` de hoje? O limite é por lote, por evento, ou os dois?
- **Q14.** “Valor taxa” do lote: só o titular (legado: dependente taxa R$ 0), por membro, ou pacote familiar?
- **Q15.** Itens adicionais: catálogo genérico por evento (#17) ou campos nativos hospedagem/refeição no v1?
- **Q16.** Variação de extra: só idade, ou também por data/lote? Idade na data da inscrição ou no início do evento? (#36 recalcula no check-in.)
- **Q17.** Extra é por membro, pela inscrição, ou flag `per_member` no catálogo?
- **Q18.** Desconto progressivo de dependentes: fórmula, a partir de qual dependente, aplica em taxa e/ou extras, empilha com CPF/distância?
- **Q19.** Crédito de evento anterior (existe no legado, some da wiki): entra, vira desconto manual, ou fora?
- **Q20.** Distância = UF/cidade (hoje) ou km? (#62 CEP)
- **Q21.** Inscrição iniciada no lote A e paga no lote B: qual valor vale?

### Pagamento (detalhe)

- **Q22.** Asaas: link de pagamento (UX atual) vs cobrança+customer (#25)?
- **Q23.** Cancelamento automático não pago: webhook do gateway, job interno (7 dias da análise), ou o que ocorrer primeiro? Prazo configurável por evento/lote?
- **Q24.** Cadência do e-mail de lembrete.
- **Q25.** Política #28 (100%−taxas / 7 dias; 50% taxa + 100% alimentação / 15 dias antes) continua? E camping? E depois da janela 2?
- **Q26.** Credenciais de pagamento: um wallet por evento (wiki), por marca, ou um só MEUC?
- **Q27.** Alteração paga que **aumenta** valor gera 2ª cobrança? Que **diminui** gera reembolso parcial na aprovação?

### Identidade, LGPD, fila

- **Q28.** OTP: e-mail, SMS, WhatsApp, magic link? Identificador da conta (e-mail vs CPF) — Paraguai não tem CPF (#30).
- **Q29.** Um login gerencia várias pessoas? Cônjuge tem login?
- **Q30.** Termos do evento vs privacidade MEUC: um documento ou dois? Reaceite em nova versão?
- **Q31.** Anonimizar vs apagar; retenção fiscal; escopo família vs só o solicitante. Default de “guardar dados para o próximo evento” (#18): opt-in ou opt-out?
- **Q32.** Fila: por família ou por pessoa? FIFO? Auto-promove em cancelamento não pago e/ou reembolso? Prazo para pagar a vaga?

### Jornada e admin

- **Q33.** O que é editável sem aprovação (não paga)? Recalcula e substitui a cobrança?
- **Q34.** O que exige aprovação depois de paga (membros/extras vs crachá/endereço)?
- **Q35.** `PrevisaoChegada` (wiki; não existe no legado): data/hora por inscrição, por membro, só o dia, ou fora do v1? Para que serve (cozinha, recepção)?
- **Q36.** Necessidades especiais: boolean, boolean+texto, ou catálogo? Por membro?
- **Q37.** Deep clone: o que copia? Zera datas? Não clona segredo de pagamento?
- **Q38.** Excluir vs cancelar inscrição paga. Quem aprova reembolso (tesouraria vs qualquer admin)?
- **Q39.** KPIs mínimos pré/durante/pós. Sem isso os dashboards da wiki não são implementáveis.
- **Q40.** Pessoa global + snapshot na inscrição, só snapshot, ou identidade viva (legado)?
- **Q41.** Gratuidade por substituição de família (#63): v1 ou processo manual?
- **Q42.** Complemento de endereço, CEP, Paraguai, limite de crachá: v1 público ou depois?

---

## Respostas

*(vazio — aguardando rodada 1)*
