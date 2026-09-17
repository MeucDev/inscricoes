# Drill — Inscrições Next Gen

Perguntas em rodadas. Responder por número (A/B/C ou texto). Cada resposta fecha o item na `spec.md`.

Convenção: **sugerido** = palpite informado pelo legado/wiki; não é decisão.

---

## Rodada 1 — Espinha (em andamento)

Estas respostas definem o recorte da v1, o agregado de inscrição e o pagamento. Sem elas, as próximas rodadas (preço, fila, LGPD, telas) se perdem.

### Q1. Recorte da primeira entrega

A wiki fala em unificar vários sistemas de eventos da Meuc, mas lista jornada só para o Congresso de Famílias.

- **A.** v1 = só Congresso de Famílias, com tipos de evento já genéricos no modelo
- **B.** v1 = plataforma já usada por ≥2 eventos reais da Meuc
- **C.** Outro recorte (descrever)

**Sugerido:** A. Unificação como desenho; Congresso como primeiro consumidor.

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

Motivação original da wiki; ainda sem provedor.

- **A.** PagSeguro (API atual, não o legado)
- **B.** Mercado Pago
- **C.** PIX direto (banco/PSP) + cartão depois
- **D.** Camada interna pluggable; v1 com um provedor concreto (qual?)
- **E.** Outro

**Sugerido:** D com um provedor já usado na Meuc, para não repetir o lock-in atual.

### Q8. Meios de pagamento na v1

- **A.** Só PIX
- **B.** PIX + cartão
- **C.** PIX + cartão + boleto (como o PagSeguro atual cobre)
- **D.** Outro

### Q9. Quando a cobrança nasce

- **A.** Ao finalizar a inscrição (como hoje: gera link na hora)
- **B.** Inscrição fica rascunho; usuário dispara “pagar”
- **C.** Reserva de vaga por N horas; depois cancela se não pagar

Q combinada com o cancelamento automático da wiki.

### Q10. Destino das features operacionais do Congresso que a wiki não lista

Check-in, crachá, equipes de refeição, tipos BANDA/COMITE/STAFF, links seguros de inscrição.

- **A.** Entram na v1 (especificar na rodada 2)
- **B.** v1 é só inscrição+pagamento+gestão da wiki; operação no evento fica para v1.1
- **C.** Mix: listar o que é inegociável no próximo Congresso

**Sugerido:** C — sem isso o Congresso não opera, mesmo com checkout novo.

### Q11. Stack da v1 `[pode ficar para rodada 2 se Q2 = A]`

- **A.** Decidir stack agora (descrever preferência)
- **B.** Fechar domínio primeiro; stack numa rodada própria
- **C.** Já existe decisão fora da wiki (apontar)

**Sugerido:** B, depois de Q1–Q4.

---

## Rodada 2 — (bloqueada)

Preço/lotes/descontos, fila, LGPD, KPIs, e-mails, modelo de conta OTP, campos de Evento/Pessoa.

Só abre quando Q1–Q10 tiverem resposta.

---

## Respostas

*(vazio — aguardando rodada 1)*
