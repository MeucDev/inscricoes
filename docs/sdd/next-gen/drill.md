# Drill Next-gen

Esclarecer o que a wiki ainda não fecha, para que sessões futuras de spec/código
não inventem produto.

Como responder: editar o campo **Resposta** ou citar o ID na conversa
(`D0.2 = B, com ressalva …`). Hipóteses vêm do legado e das issues; não são
decisão até você confirmar.

Prioridade:

- **P0** — sem isso não dá para escrever spec nem escolher fatia v1
- **P1** — bloqueia o desenho daquele subdomínio
- **P2** — pode esperar a fatia correspondente

Status inicial de todas: `PENDENTE`.

---

## D0. Estratégia e recorte v1

### D0.1 — Natureza da mudança (P0)

A wiki fala em “nova versão”. O legado é Laravel 5.3 + CrudBooster + Vue 2 +
PagSeguro, com dados de vários congressos.

- **A.** Greenfield: app novo, modelo novo, importar só o necessário.
- **B.** Evolução no mesmo repositório: migrar stack e ir substituindo módulos.
- **C.** Strangler: next-gen ao lado do legado; Congresso N usa o novo,
  eventos passados ficam no antigo.

**Hipótese:** A ou C. B no Laravel 5.3/CrudBooster é caro e não atende
unificação. Issue #16 sozinha não chega no produto da wiki.

**Resposta:**

### D0.2 — Qual evento o v1 precisa atender? (P0)

A wiki descreve gestão genérica e, em seguida, “funcionalidades para o usuário
final **do Congresso de Famílias**”. Também cita conversas com outros sistemas
MEUC.

- **A.** v1 = só Congresso de Famílias, com o modelo já genérico o bastante
  para outros eventos depois.
- **B.** v1 já unifica pelo menos dois eventos reais (quais?).
- **C.** v1 é só o núcleo (evento + inscrição + pagamento + LGPD), sem UX
  específica de Congresso.

**Hipótese:** A. Unificar no v1 sem os outros produtos especificados explode o
escopo.

**Resposta:**

### D0.3 — Quais outros sistemas/eventos entram no horizonte? (P0)

A wiki não nomeia os sistemas. Issue #17 cita Retiro de Crianças e Congresso
de Jovens. #25 cita DI / retiro de crianças no PagSeguro.

Liste, mesmo que informalmente: nome do evento, sistema atual, dono, o que
é diferente do Congresso (individual vs família, refeição, hospedagem, grupo).

**Hipótese:** precisamos de 1 parágrafo por evento-alvo, senão “tipo de evento”
fica especulativo.

**Resposta:**

### D0.4 — Motor de pagamento do v1 (P0)

Wiki: “gerenciar credenciais de motor de pagamento” (abstração).
Issue #25: Asaas, ainda em aberto cobrança vs link.

- **A.** Só Asaas no v1; interface interna para outro provedor depois.
- **B.** Abstração real no v1 (Asaas + 1 segundo, ou Asaas + PagSeguro residual).
- **C.** Continuar PagSeguro no next-gen (descarta a motivação principal).

**Hipótese:** A, com checkout por **link de pagamento** Asaas (menor atrito de
UX, igual ao fluxo atual de redirecionamento). Cobrança (customer Asaas) só se
D3.2 escolher controle de cliente.

**Resposta:**

### D0.5 — Stack alvo (P0)

Wiki não escolhe. Legado: PHP 5.6 / Laravel 5.3. #16: Laravel 8/9 + PHP 8.
Há `docs/ANALISE_TRADEOFF_EVOLUCAO_STACK.md` só na máquina local do mantenedor
(não está neste clone).

- **A.** Continuar Laravel (versão suportada) + admin próprio ou Filament.
- **B.** Laravel API + front SPA (Vue/React).
- **C.** Outra stack (qual?).

**Hipótese:** A ou B. Decidir agora evita dois specs. Time atual é PHP/Vue.

**Resposta:**

### D0.6 — Operação durante o evento no v1? (P0)

Wiki só menciona “dashboard durante evento”. O legado vive de check-in,
equipe de refeição, crachá, Lar/Quiosque.

- **A.** v1 inclui check-in, equipes de refeição, crachá.
- **B.** v1 é pré-evento (inscrição, pagamento, fila, LGPD). Operação entra
  no v1.1.
- **C.** v1 inclui check-in mínimo (presente/ausente) sem crachá/equipes.

**Hipótese:** C se o próximo Congresso for o deadline; A se o next-gen só
estreia quando substituir o dia do evento também.

**Resposta:**

### D0.7 — Cutover dos dados (P0)

- **A.** Importar pessoas + inscrições históricas (necessário para desconto
  de evento anterior e “inscrições anteriores”).
- **B.** Importar só pessoas (CPF/contato) sem histórico financeiro.
- **C.** Começar zerado; desconto de evento anterior vira regra manual.

**Hipótese:** A se a regra de crédito de evento anterior continuar. A wiki
não cita essa regra; o legado depende dela.

**Resposta:**

---

## D1. Tipos de evento, grupos, papéis

### D1.1 — Três modos de inscrição (P0)

Wiki:

1. Inscrição individual
2. Inscrição de responsável e dependentes
3. Inscrição de responsável **em nome de** dependentes

Qual a diferença real entre 2 e 3?

- **A.** 2 = responsável também participa (Congresso). 3 = responsável só
  cadastra (ex.: pais inscrevem filhos no retiro, sem se inscrever).
- **B.** 3 é inscrição feita por admin/comitê em nome da família.
- **C.** Outra distinção (descrever).

**Hipótese:** A. É o que justifica “tipos de evento” além do Congresso.

**Resposta:**

### D1.2 — “Grupos para vínculo” vs “grupos de participação” (P0)

A wiki lista os dois, separados.

- **A.** Vínculo = igreja/núcleo/congregação obrigatória para inscrever.
  Participação = equipe no evento (cozinha, recepção, banda).
- **B.** São o mesmo conceito com dois nomes.
- **C.** Vínculo = turma/célula; participação = faixa (crianças, jovens, casais).

**Hipótese:** A. Precisa de exemplo de um evento que **exige** vínculo.

**Resposta:**

### D1.3 — BANDA / COMITE / STAFF (P1)

Hoje são `tipoInscricao` via admin ou link seguro (bypass de limite, camping
grátis para COMITE). A wiki não menciona.

- **A.** Viram flags/papéis no tipo de evento + links com cota.
- **B.** Viram “grupos de participação” com regra de preço.
- **C.** Fora do next-gen v1 (só inscrição NORMAL pública).

**Hipótese:** A, porque o Congresso depende disso todo ano.

**Resposta:**

### D1.4 — Links seguros de inscrição (P1)

Módulo recente (`link_inscricoes`), não está na wiki.

- **A.** Manter (token, validade, cota, tipo).
- **B.** Substituir por convite autenticado (OTP) a um grupo.
- **C.** Fora do v1.

**Hipótese:** A se D1.3 = A.

**Resposta:**

---

## D2. Preço, lotes, descontos, extras

### D2.1 — Lote como entidade (P0)

Wiki: lote tem abertura, encerramento, limite, valor taxa.
Legado: `data_ate` na variação do valor `NORMAL` + `limite_inscricoes` no evento
+ flag `aberto`.

- **A.** Lote first-class: N lotes por evento, cada um com capacidade e taxa.
  Inscrição gruda no lote vigente.
- **B.** Um lote “aberto” por vez (igual hoje, só modelado direito).
- **C.** Sem lote: só datas do evento + tabela de preços.

**Hipótese:** A, porque a wiki lista limite **por lote**. Confirmar se o
limite global do evento permanece.

**Resposta:**

### D2.2 — O que é “valor taxa” do lote? (P0)

- **A.** Só a taxa de inscrição do titular (dependentes = R$ 0 de taxa, como
  hoje quando `TIPO != R`).
- **B.** Taxa por membro, podendo ser 0 em alguns papéis.
- **C.** Pacote familiar fechado, independente do número de membros.

**Hipótese:** A, compatível com o Congresso. Eventos individuais usam B.

**Resposta:**

### D2.3 — Itens adicionais genéricos (P0)

Wiki + #17: hospedagem e alimentação são extras com variação por nascimento.
Outros eventos teriam extras diferentes (devocional, hinário).

- **A.** Catálogo genérico por evento (categoria + itens + variações).
  Hospedagem/refeição são só itens desse catálogo.
- **B.** Extras genéricos **e** campos nativos hospedagem/refeição.
- **C.** Só hospedagem e alimentação no v1; genérico depois.

**Hipótese:** A. É o ponto que permite unificar eventos. Campos nativos
travam o modelo.

**Resposta:**

### D2.4 — Dimensões de variação de preço (P1)

Legado mistura na mesma tabela: `data_ate` (lote) e faixa etária.

- **A.** Separar: lote define taxa; extra varia só por idade (nascimento).
- **B.** Extra pode variar por idade **e** por data (preço de camping sobe
  no 2º lote).
- **C.** Variação livre (idade, data, papel, grupo) via motor de regras.

**Hipótese:** B cobre o Congresso sem over-engineering. A é mais simples se
camping/refeição não mudam entre lotes.

**Pergunta extra:** a idade é a da **data da inscrição** ou a do **início do
evento**? (#36 recalcula no check-in.)

**Resposta:**

### D2.5 — Desconto progressivo de dependentes (P1)

Novo. Não há fórmula.

Exemplos para escolher ou substituir:

- **A.** 1º dependente 0%, 2º 10%, 3º+ 20% sobre a taxa (não sobre extras).
- **B.** Progressivo sobre o total (taxa+extras) do dependente.
- **C.** A partir do N-ésimo dependente a taxa é R$ 0.

**Resposta:** (incluir se aplica a cônjuge, até que idade, se empilha com CPF)

### D2.6 — Empilhamento de descontos (P1)

Hoje: percentual por CPF **ou** UF/cidade (CPF ganha); depois crédito de
evento anterior como valor absoluto na UI (`descontoEventoAnterior`).

- **A.** Manter: um percentual (CPF>distância) + crédito de evento anterior.
- **B.** Apenas uma regra vence (prioridade explícita: gratuidade > CPF >
  progressivo > distância > crédito).
- **C.** Somam-se todas, com teto de 100%.

**Hipótese:** A, mais o progressivo só em dependentes. Gratuidade (#63) zera
tudo.

**Resposta:**

### D2.7 — Crédito de evento anterior (P1)

Não está na wiki. É operação real de tesouraria.

- **A.** Entra no spec (origem, destino, valor fixo ou “o que foi pago”).
- **B.** Vira um desconto por CPF manual; some a regra automática.
- **C.** Fora do v1.

**Hipótese:** A se D0.7 importar histórico; senão B.

**Resposta:**

### D2.8 — Distância (P2)

Wiki: “CPF e/ou distância”. Legado: UF ou nome de cidade, não km.

- **A.** Continuar tabela UF/cidade → %.
- **B.** Calcular km (CEP/coordenada) a partir do local do evento.
- **C.** Só UF.

**Hipótese:** A. B é projeto à parte (#62 CEP já é issue).

**Resposta:**

### D2.9 — Gratuidade por substituição de família (P2)

#63 aberta.

- **A.** v1: tipo de desconto 100% vinculado a uma inscrição cancelada.
- **B.** v1: link seguro com taxa 0 (como COMITE).
- **C.** Fora do v1 (processo manual).

**Hipótese:** C ou B. A exige modelo de “transferência” que a wiki não descreve.

**Resposta:**

---

## D3. Pagamento, cancelamento, reembolso

### D3.1 — Unidade de cobrança (P0)

- **A.** Uma cobrança por inscrição-agregado (família), como hoje o responsável
  paga o total.
- **B.** Uma cobrança por membro.
- **C.** Taxa numa cobrança, extras em outra.

**Hipótese:** A.

**Resposta:**

### D3.2 — Integração Asaas (P0)

Do #25:

- **A.** Link de pagamento (UX parecida com PagSeguro; cliente duplicado no Asaas).
- **B.** Cobrança + customer (escolhe meio/parcelas no nosso UI).
- **C.** B no v1 só Pix à vista; cartão/parcelas depois.

**Hipótese:** A para chegar no próximo Congresso; B se OTP+conta já existirem
e quisermos parcelamento Pix controlado.

**Resposta:**

### D3.3 — Meios no v1 (P1)

- **A.** Pix + cartão + boleto (paridade PagSeguro).
- **B.** Pix + cartão.
- **C.** Só Pix.

**Resposta:**

### D3.4 — Cancelamento automático não pago (P0)

Wiki + #26 + análise `consultas_pagamento` (7 dias).

- **A.** Expira com a cobrança no Asaas; webhook cancela.
- **B.** Job interno (ex. 7 dias) independente do gateway.
- **C.** Os dois: o que ocorrer primeiro.

**Hipótese:** A + aviso explícito no aceite (#26). Confirmar prazo (7 dias?
configurável por evento/lote?).

**Resposta:**

### D3.5 — Lembrete de não pago (P1)

Wiki pede e-mail de lembrete. Não define cadência.

- **A.** 1 lembrete em T-2 dias do vencimento.
- **B.** Cadência (ex. 24h, 72h, T-1).
- **C.** Só o e-mail de confirmação com o link; sem lembrete.

**Hipótese:** A. B pode ser ruído.

**Resposta:**

### D3.6 — Política de reembolso #28 (P0)

Confirmar se continua:

1. 100% menos taxas até 7 dias após a inscrição paga.
2. 50% da taxa + 100% alimentação até 15 dias antes do evento.
3. Depois disso: sem reembolso? (a issue não diz)

E hospedagem/camping: segue a alimentação, a taxa, ou não reembolsa?

**Hipótese:** após a janela 2, indeferir. Camping segue alimentação.
Texto no termo **e** na tela de cancelamento.

**Resposta:**

### D3.7 — Credenciais por evento (P1)

- **A.** Um wallet Asaas por evento (wiki literal).
- **B.** Um wallet MEUC, metadado do evento na cobrança.
- **C.** Um wallet por “marca” (Congresso vs Jovens vs …).

**Hipótese:** C ou B. A é operacionalmente pesado se todos os eventos são MEUC.

**Resposta:**

### D3.8 — Status de reembolso visível ao usuário (P1)

Wiki: consultar status. Quais estados?

- **A.** `solicitado` / `aprovado_processando` / `reembolsado` / `indeferido`.
- **B.** Só `solicitado` e `concluído`, detalhe só no admin.
- **C.** Fora do self-service no v1 (e-mail manual).

**Hipótese:** A se D0.2 = Congresso com self-service; C se autenticação OTP
escorregar.

**Resposta:**

---

## D4. Autenticação e identidade

### D4.1 — OTP como (P0)

Wiki: “OTP parece ser a forma de menor atrito”.

- **A.** OTP por e-mail.
- **B.** OTP por SMS.
- **C.** OTP por WhatsApp.
- **D.** Magia: e-mail link, sem código.
- **E.** Combinar (quais?).

**Hipótese:** A (custo zero, já temos e-mail). SMS/WhatsApp = custo e dado a
mais (LGPD). Identificador de conta = e-mail, não CPF (Paraguai, #30).

**Resposta:**

### D4.2 — Chave da pessoa (P0)

Hoje a pessoa é upsert por CPF.

- **A.** CPF quando BR; passaporte/documento PY; e-mail sempre único da conta.
- **B.** Só e-mail; CPF opcional.
- **C.** Continuar CPF obrigatório (bloqueia #30).

**Hipótese:** A. Conta (OTP) ≠ participante (documento).

**Resposta:**

### D4.3 — Admin (P1)

- **A.** Mesmo IdP (OTP) + papéis (tesouraria, recepção, admin).
- **B.** Admin separado (e-mail/senha), público só OTP.
- **C.** Manter CrudBooster no legado para admin, next-gen só público.

**Hipótese:** B no v1 se D0.1 = C (strangler). A se greenfield completo.

**Resposta:**

---

## D5. LGPD e termos

### D5.1 — Termos de uso vs privacidade (P0)

- **A.** Dois documentos versionados (termos de inscrição + privacidade).
- **B.** Um único “termos de uso” que inclui LGPD.
- **C.** Termos do evento + política MEUC global.

**Hipótese:** C. Evento muda regra de reembolso/lote; privacidade é da MEUC.

**Resposta:**

### D5.2 — Reaceite quando a versão muda (P1)

- **A.** Obriga reaceite no próximo login/inscrição.
- **B.** Só em inscrição nova; aceites antigos ficam na versão da época.
- **C.** Banner opcional.

**Hipótese:** A para privacidade; B para termos de evento (já aceitos naquela
inscrição).

**Resposta:**

### D5.3 — Remoção / anonimização (P0)

#18 + wiki. Conservar o quê para tesouraria/auditoria?

- **A.** Anonimizar PII; manter valores, datas, status de pagamento.
- **B.** Hard delete da pessoa e inscrições abertas; pagas viram registro
  financeiro sem nome.
- **C.** Só inativar (`inativo=1` legado); sem apagar.

**Hipótese:** A. C não atende LGPD. Definir prazo legal (ex. 5 anos fiscais
antes de anonimizar financeiro).

**Resposta:**

### D5.4 — “Guardar meus dados para o próximo evento” (P1)

#18: default hoje é guardar (upsert por CPF).

- **A.** Opt-in (default desligado).
- **B.** Opt-out (default ligado, desmarca se quiser).
- **C.** Sempre guarda identidade; só extras/endereço são opt-in.

**Hipótese:** B no Congresso (famílias voltam todo ano); A é mais “by design”
LGPD. Precisa de advogado/comitê, não só de dev.

**Resposta:**

---

## D6. Jornada do inscrito (Congresso)

### D6.1 — Fila de espera (P0)

Hoje: texto pedindo e-mail. Wiki: registrar interesse + consultar status +
admin gere.

- **A.** Fila por família (1 entrada). Quando abre vaga, OTP + prazo para
  pagar. Admin pode furar fila.
- **B.** Fila por pessoa.
- **C.** Só captura de e-mail + lista no admin (sem auto-promoção).

**Hipótese:** A. Confirmar: vaga surge no cancelamento não pago, no reembolso,
ou nos dois? Ordem FIFO estrita?

**Resposta:**

### D6.2 — O que o usuário pode alterar **sem** aprovação (não paga) (P1)

#27 + wiki.

Marcar: membros, extras, endereço, crachá, necessidades especiais, previsão
de chegada. Recalcula preço e **substitui** a cobrança?

**Hipótese:** tudo editável enquanto `aguardando_pagamento`; gera nova
cobrança e cancela a anterior.

**Resposta:**

### D6.3 — O que exige aprovação **depois de paga** (P1)

Wiki não lista o subconjunto.

- **A.** Qualquer mudança de membro ou extra; endereço/crachá livre.
- **B.** Tudo exige aprovação.
- **C.** Depois de paga, só cancelamento; alteração = admin.

**Hipótese:** A. Mudança que altera valor precisa de recálculo/reembolso/cobrança
complementar — a wiki não fala em **cobrança complementar**. Isso é um furo.

**Pergunta extra:** se a alteração **aumenta** o valor, gera 2ª cobrança?
Se **diminui**, reembolso parcial automático ou só na aprovação?

**Resposta:**

### D6.4 — Previsão de chegada (P1)

Único campo novo no agregado da wiki. Não existe no legado.

- **A.** Data/hora por inscrição (família).
- **B.** Data/hora por membro.
- **C.** Dia previsto (sem hora), para logística de refeição/check-in.
- **D.** Cortar do v1.

**Hipótese:** C. Para que serve (cozinha, recepção, transporte)?

**Resposta:**

### D6.5 — Necessidades especiais (P2)

Hoje: boolean. Wiki: “indicar”.

- **A.** Boolean + texto livre (visível só ao comitê).
- **B.** Boolean; contato posterior como hoje.
- **C.** Catálogo (mobilidade, alimentação, outro).

**Hipótese:** A. Texto livre é o mínimo para o comitê não ligar às cegas.

**Resposta:**

### D6.6 — Paraguai e endereço (P2)

#30, #60, #62.

v1 precisa de: país, documento não-CPF, complemento, CEP opcional, UF/cidade
livres ou IBGE?

**Hipótese:** país + complemento no v1; ViaCEP só para BR; validação UF/IBGE
não bloqueia PY.

**Resposta:**

### D6.7 — Crachá (P2)

#59/#70: limite de impressão, preview, sugerir primeiro nome.

Entra no v1 público ou só na operação do evento (D0.6)?

**Resposta:**

---

## D7. Admin, KPIs, clone, e-mails

### D7.1 — Deep clone (P1)

Wiki: duplicar evento a partir de um anterior.

O que clona? tipo, extras, lotes, descontos, termos, credencial, grupos,
**sem** inscrições/fila. Datas deslocadas manualmente?

**Hipótese:** clona configuração, zera datas de lote para o admin preencher,
não clona segredo de pagamento (reaponta credencial).

**Resposta:**

### D7.2 — Excluir vs cancelar (P1)

Wiki lista “excluir inscrições”. Legado tem `cancelada` e deletes de
dependentes (#35/#65).

- **A.** Não há delete físico de inscrição paga; só cancelamento com trilha.
  Delete só em rascunho/não pago, com auditoria.
- **B.** Admin pode apagar qualquer uma.
- **C.** Soft-delete universal.

**Hipótese:** A (financeiro + LGPD).

**Resposta:**

### D7.3 — Quem aprova alteração/cancelamento pago? (P1)

- **A.** Papel tesouraria.
- **B.** Qualquer admin.
- **C.** Tesouraria para reembolso; recepção para alteração cadastral.

**Hipótese:** A.

**Resposta:**

### D7.4 — KPIs e os três dashboards (P1)

A wiki só nomeia as fases. Sem métricas não dá para modelar eventos de domínio.

Preencher o que **precisa** aparecer:

**Pré-evento:** (ex. inscritos pagos/não pagos, por lote, fila, arrecadação
bruta/líquida, extras, necessidades especiais, por UF)

**Durante:** (ex. presentes, aguardando, equipes de refeição, chegadas/hora)

**Pós:** (ex. no-show, reembolsos, occupancy camping, NPS? — NPS não existe)

**Hipótese mínima pré:** pagos, pendentes, cancelados, fila, R$ líquido, quebra
por extra. Durante/pós dependem de D0.6.

**Resposta:**

### D7.5 — E-mail “não responda” (P2)

#66: From noreply, contato congressodefamilias no corpo.

Vale para todos os e-mails do next-gen? Eventos não-Congresso usam de-qual
domínio?

**Resposta:**

---

## D8. Não-objetivos e restrições

### D8.1 — Fora do v1 (P0)

Marcar o que **não** entra, mesmo que a wiki cite:

- Unificação com outros sistemas MEUC além do modelo genérico
- App mobile nativo
- Parcelamento Pix
- Cálculo de km real
- Multi-idioma (ES)
- Impressão de crachá
- Relatórios pós-evento avançados
- Migração automática CrudBooster → novo admin
- Outro: ___

**Hipótese:** os sete primeiros ficam fora se o deadline for o próximo Congresso.

**Resposta:**

### D8.2 — Volume e hospedagem (P1)

Ordem de grandeza do Congresso (famílias, picos de inscrição, e-mails/dia)?
Hospedagem alvo (continuar Umbler, VPS, outro)?

Isso trava OTP, fila, webhook Asaas e envio de e-mail.

**Resposta:**

### D8.3 — Deadline real (P0)

Qual a data do próximo evento que **não** pode mais usar PagSeguro legado?
Há um Congresso intermediário ainda no sistema atual?

**Resposta:**

---

## D9. Modelo de dados (furos da wiki)

### D9.1 — Inscrição como agregado com membros (P0)

Confirmar a virada:

- **A.** 1 inscrição = 1 família/grupo; membros dentro; pagamento no agregado.
- **B.** Continuar 1 inscrição = 1 pessoa, com responsável apontando filhos.
- **C.** A no público, B no admin (não fazer isso).

**Hipótese:** A. É o que a seção Dados descreve. Impacta relatórios, crachá
e check-in (presença passa a ser do membro).

**Resposta:**

### D9.2 — Pessoa global vs snapshot (P0)

- **A.** Identidade global (conta) + snapshot na inscrição (o que foi pago
  naquele ano não muda se a pessoa editar o cadastro depois).
- **B.** Só snapshot; próxima inscrição começa em branco (ou via opt-in D5.4).
- **C.** Só identidade viva; inscrição aponta para a pessoa (legado).

**Hipótese:** A. Financeiro e LGPD exigem snapshot; UX de retorno exige identidade.

**Resposta:**

### D9.3 — Adicionais no agregado (P1)

Wiki coloca `Adicionais` na inscrição, não no membro.

- **A.** Extra é por membro (camping do pai, refeição da criança) — igual hoje.
- **B.** Extra é da inscrição (uma hospedagem familiar).
- **C.** Depende do item (flag `per_member` no catálogo).

**Hipótese:** C, default `per_member` para hospedagem/refeição.

**Resposta:**

---

## Ordem sugerida para responder

1. D0.1, D0.2, D8.3, D0.4, D0.6 — recorte e deadline.
2. D9.1, D9.2, D1.1, D2.1, D2.3, D3.1 — modelo.
3. D3.2, D3.4, D3.6, D4.1, D5.1, D5.3, D6.1 — jornadas críticas.
4. O restante P1/P2 na mesma conversa ou numa segunda rodada.

Quando P0 estiver fechado, a próxima sessão deve emitir `spec.md` só com o
que foi confirmado, e recusar implementar o resto.
