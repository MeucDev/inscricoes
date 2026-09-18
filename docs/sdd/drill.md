# Drill — Inscrições Next Gen

Perguntas em rodadas. Responder por número (`Q13 = A`, texto livre se a opção não couber). Cada resposta fecha o item na `spec.md`.

Convenção: **sugerido** = palpite informado pelo legado/wiki/issues; **não é decisão**.

---

## Rodada 1 — Espinha (**fechada** 2026-09-18)

### Q1. Recorte da primeira entrega — **A**

v1 = só Congresso de Famílias, com tipos de evento já genéricos no modelo.

### Q2. Relação com este repositório — **A**

Sistema novo (greenfield). Legado = referência + fonte de migração.

Calendário informado: próximo evento em **abril**, janela de inscrições em **janeiro**.

### Q3. Migração de dados — **A**

Migrar pessoas + inscrições históricas, **com scripts à parte** que a operação decide rodar ou não.

### Q4. Agregado “Inscrição” — **A**

1 inscrição = núcleo (responsável + membros), 1 cobrança, 1 status de pagamento.

### Q5. “Responsável em nome de dependentes” — **A**, via tipo de evento

- Um dos eventos a incluir (menores de idade) tem isso como requisito principal: responsável **não participa**, só inscreve menores, não paga taxa própria.
- Definido nos **tipos de evento**.
- Congresso de Famílias usa o tipo **B** (responsável **participa**).

### Q6. Os dois “grupos” — distintos; vínculo é flag de tipo

- Vínculo na inscrição é requisito de **tipo de evento**. Congresso de Famílias **não** exige; Congresso de Jovens **exige**.
- Turma/equipe no evento é **outra coisa**: controle operacional (tamanho de staff, banda, comitê). Alguns desses grupos têm **prioridade de inscrição** ou **gratuidades/descontos**.

### Q7. Motor de pagamento — **D**

Camada pluggable. Dúvida entre PagSeguro na versão atual/suportada e Asaas (outro sistema já integra Asaas). **Cada evento** define motor **e** credenciais — contas diferentes para controle financeiro externo (fora do escopo deste sistema).

### Q8. Meios na v1 — **C**

PIX + cartão + boleto.

### Q9. Quando a cobrança nasce — **A**, com prazo

Ao finalizar a inscrição. Cancelamento automático se o pagamento não for efetuado no prazo **configurável no evento**.

### Q10. Features operacionais — **A**

Check-in, crachá, equipes de refeição, BANDA/COMITE/STAFF, links seguros **entram na v1**. Já fazem parte do dia-a-dia desde a última edição e atenderam as expectativas.

### Q11. Stack — **B**

Fechar domínio primeiro; stack numa rodada própria.

### Q12. Deadline — PagSeguro legado **já morto**

A versão legada já não funciona. Dois dias antes do último evento deixou de funcionar; os últimos pagamentos foram via Pix “externo”.

---

## Rodada 2 — disponível (wiki em expansão)

Não precisa responder tudo agora. Várias perguntas abaixo já foram **parcialmente** fechadas pela rodada 1; o resto deve entrar na wiki ou voltar aqui.

### Já encaminhadas pela rodada 1

- **Q23 (prazo):** configurável **no evento**. Resta mecanismo (job vs webhook/vencimento do gateway) e se libera vaga/fila.
- **Q26:** motor **e** credenciais **por evento** (não um wallet MEUC único).
- Operação de evento (check-in, crachá, grupos operacionais, links): **v1**.

### O que mais ajuda a wiki agora (não precisa ser nesta ordem)

Prioridade para não travar janeiro:

1. **Primeiro adapter de pagamento** (PagSeguro atual vs Asaas) e se a v1 já troca o motor por evento na UI.
2. **Grupos operacionais:** cadastro, como entra (link seguro?), prioridade vs lote/limite/fila, gratuidade vs desconto % vs isenção de item (camping COMITE).
3. **Modo menores:** responsável tem login/Pessoa mas não é membro? Quais dados do menor? Consentimento?
4. **Lotes e limite:** o que conta (núcleo vs cabeça); valor se pagar depois da virada de lote.
5. **Desconto progressivo** e empilhamento com CPF/distância/grupo operacional.
6. **Reembolso** (candidato #28) e alteração paga que muda valor.

### Preço e lotes

- **Q13.** Lote first-class (abertura, encerramento, limite, taxa) vs variação `data_ate` de hoje? O limite é por lote, por evento, ou os dois?
- **Q14.** “Valor taxa” do lote: só o titular (legado: dependente taxa R$ 0), por membro, ou pacote familiar? No modo menores o responsável não paga taxa — a taxa é por menor?
- **Q15.** Itens adicionais: catálogo genérico por evento (#17) ou campos nativos hospedagem/refeição no v1?
- **Q16.** Variação de extra: só idade, ou também por data/lote? Idade na data da inscrição ou no início do evento? (#36 recalcula no check-in.)
- **Q17.** Extra é por membro, pela inscrição, ou flag `per_member` no catálogo?
- **Q18.** Desconto progressivo de dependentes: fórmula, a partir de qual dependente, aplica em taxa e/ou extras, empilha com CPF/distância **e com grupo operacional**?
- **Q19.** Crédito de evento anterior (existe no legado, some da wiki): entra, vira desconto manual, ou fora?
- **Q20.** Distância = UF/cidade (hoje) ou km? (#62 CEP)
- **Q21.** Inscrição iniciada no lote A e paga no lote B: qual valor vale?

### Pagamento (detalhe)

- **Q22.** Primeiro adapter: PagSeguro API atual, Asaas, ou os dois na v1? Se Asaas: link de pagamento vs cobrança+customer (#25)?
- **Q23.** Cancelamento automático: webhook/vencimento do gateway, job interno, ou o que ocorrer primeiro? Avisa o inscrito? Libera vaga para a fila?
- **Q24.** Cadência do e-mail de lembrete.
- **Q25.** Política #28 (100%−taxas / 7 dias; 50% taxa + 100% alimentação / 15 dias antes) continua? E camping? E depois da janela 2?
- **Q27.** Alteração paga que **aumenta** valor gera 2ª cobrança? Que **diminui** gera reembolso parcial na aprovação?

### Identidade, LGPD, fila

- **Q28.** OTP: e-mail, SMS, WhatsApp, magic link? Identificador da conta (e-mail vs CPF) — Paraguai não tem CPF (#30).
- **Q29.** Um login gerencia várias pessoas? Cônjuge tem login? No modo menores o login é só do responsável?
- **Q30.** Termos do evento vs privacidade MEUC: um documento ou dois? Reaceite em nova versão?
- **Q31.** Anonimizar vs apagar; retenção fiscal; escopo família vs só o solicitante. Default de “guardar dados para o próximo evento” (#18): opt-in ou opt-out?
- **Q32.** Fila: por núcleo ou por pessoa? FIFO vs prioridade de grupo operacional? Auto-promove em cancelamento não pago e/ou reembolso? Prazo para pagar a vaga?

### Jornada, operação e admin

- **Q33.** O que é editável sem aprovação (não paga)? Recalcula e **substitui** a cobrança já emitida?
- **Q34.** O que exige aprovação depois de paga (membros/extras vs crachá/endereço)?
- **Q35.** `PrevisaoChegada` (wiki; não existe no legado): data/hora por inscrição, por membro, só o dia, ou fora do v1? Para que serve (cozinha, recepção)?
- **Q36.** Necessidades especiais: boolean, boolean+texto, ou catálogo? Por membro?
- **Q37.** Deep clone: o que copia? Zera datas? **Não** clona segredo de pagamento (credenciais são por evento/conta)?
- **Q38.** Excluir vs cancelar inscrição paga. Quem aprova reembolso (tesouraria vs qualquer admin)?
- **Q39.** KPIs mínimos pré/durante/pós.
- **Q40.** Pessoa global + snapshot na inscrição, só snapshot, ou identidade viva (legado)?
- **Q41.** Gratuidade por substituição de família (#63): v1 ou processo manual?
- **Q42.** Complemento de endereço, CEP, Paraguai, limite de crachá: v1 público ou depois?
- **Q43.** Grupos operacionais: lista configurável por evento ou tipos fixos? Inscrição entra por link seguro, convite, ou só gestão? Prioridade fura lote fechado, só o limite, ou também a fila?
- **Q44.** Equipes de refeição (QUIOSQUE/LAR A/B): mesmo mecanismo dos grupos operacionais ou alocação no check-in?
- **Q45.** Inscrição individual como modo de tipo: entra no modelo v1 (mesmo que o Congresso não use)?

---

## Respostas

### Rodada 1 (2026-09-18)

| # | Resposta | Notas |
|---|---|---|
| Q1 | A | v1 = Congresso; modelo já genérico |
| Q2 | A | Greenfield. Evento abril; inscrições janeiro |
| Q3 | A | Scripts de migração à parte, opcionais na operação |
| Q4 | A | 1 inscrição = núcleo; 1 cobrança; 1 status |
| Q5 | A + tipo B no Congresso | Menores: responsável não participa. Congresso: responsável participa. Definido no tipo de evento |
| Q6 | Distintos | Vínculo = tipo (Jovens sim, Famílias não). Operacional = staff/banda/comitê, com prioridade e gratuidade |
| Q7 | D | Pluggable; PagSeguro atual **ou** Asaas; motor+credenciais **por evento** |
| Q8 | C | PIX + cartão + boleto |
| Q9 | A | Cobrança na finalização; cancelamento automático com prazo no evento |
| Q10 | A | Operação do Congresso na v1 |
| Q11 | B | Stack depois do domínio |
| Q12 | Já passou | PagSeguro legado morto; último evento usou Pix externo |

### Rodada 2

*(em andamento via wiki + este arquivo)*
