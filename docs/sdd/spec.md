# Spec — Inscrições Next Gen

Status: **rascunho**. Origem: wiki Next-gen + inventário do legado. Itens `[ABERTO]` não podem ser implementados.

## 1. Problema

Substituir o motor de pagamentos descontinuado do sistema de inscrições do Congresso de Famílias, fechar lacunas de LGPD, atualizar stack em risco, e abrir caminho para um sistema unificado de inscrições de eventos da Meuc.

## 2. Objetivos

- Permitir inscrever, pagar, alterar (com regras) e cancelar participação em eventos da Meuc.
- Configurar eventos por tipo (desconto, vínculo a grupo, modo de inscrição, itens, lotes, fila, termos, credenciais de pagamento).
- Automatizar confirmação, lembrete e cancelamento de inscrição não paga.
- Dar à gestão KPIs, edição, inclusão/exclusão, aprovação de mudanças pós-pagamento, fila e relatórios pré/durante/pós evento.
- Cumprir LGPD: aceite versionado e pedido de remoção/anonimização.

## 3. Não-objetivos `[ABERTO]`

Ainda não está fechado o que fica **fora** da v1. Candidatos (dependem do drill):

- App nativo
- Check-in / crachá / equipes de refeição (existem no legado)
- Unificação completa com todos os sistemas de eventos da Meuc no dia 1
- Troca de stack (pode ser v1 ou posterior)

## 4. Atores

| Ator | Papel |
|---|---|
| Participante / responsável | Inscreve, paga, consulta, solicita alteração/cancelamento/anonimização |
| Gestor do evento | Configura evento, vê inscrições, aprova, opera fila e relatórios |
| Sistema | Cancela não pagas, e-mails, consulta pagamento |
| Motor de pagamento | Cobra, confirma, recusa, reembolsa `[ABERTO: provedor]` |

Papéis admin (financeiro, credenciamento, comitê) **não** estão na wiki. `[ABERTO]`

## 5. Tipos de evento

Configuráveis:

- Possui desconto
- Exige vínculo com grupo
- Inscrição individual
- Inscrição de responsável e dependentes
- Inscrição de responsável em nome de dependentes

`[ABERTO]` Se são flags combináveis ou modos mutuamente exclusivos.

`[ABERTO]` O que significa “responsável em nome de dependentes”: o responsável não participa? Não paga taxa própria? Precisa existir como Pessoa?

## 6. Evento

Gestão configura:

- Informações gerais: nome, data, descrição, capa, favicon, endereço, etc. `[ABERTO: lista fechada de campos]`
- Tipo de evento
- Termos de uso (versão)
- Credenciais do motor de pagamento
- Itens adicionais
- Lotes
- Fila de espera ativa
- Deep clone a partir de evento anterior `[ABERTO: o que o clone copia — inscrições? credenciais? termos?]`

## 7. Lotes

Por lote: data de abertura, data de encerramento, limite de inscrições, valor da taxa.

`[ABERTO]` Relação com o legado, onde “lote” é variação de preço por `data_ate` no valor `NORMAL`, e o limite é do evento inteiro (só responsáveis NORMAL).

`[ABERTO]` O limite conta responsáveis, pessoas físicas, ou vagas de hospedagem?

`[ABERTO]` Inscrição iniciada no lote A e paga no lote B: qual valor vale?

## 8. Itens adicionais

Hospedagem e alimentação, com valores que variam por data de nascimento (idade).

`[ABERTO]` Itens são catálogo global vinculados ao evento, ou só do evento?

`[ABERTO]` Um membro pode ter 0..1 hospedagem e 0..1 alimentação, ou N itens?

`[ABERTO]` Regras atuais (LAR implica refeição NENHUMA; COMITE+CAMPING = R$ 0) seguem ou morrem?

## 9. Descontos

- Percentual
- Aplicação por CPF e/ou distância
- Progressivo para dependentes adicionais

`[ABERTO]` Precedência e empilhamento (hoje CPF vence UF/cidade; desconto de evento anterior é valor, não %).

`[ABERTO]` Distância = UF/cidade como hoje, ou km a partir do endereço do evento?

`[ABERTO]` Progressivo: a partir de qual dependente, qual curva, aplica na taxa e/ou nos itens?

`[ABERTO]` Desconto de evento anterior (crédito do valor pago) entra na Next Gen?

## 10. Grupos

A wiki cita dois conceitos sem defini-los:

- Grupos para **vínculo de inscrição** (tipo de evento “exige vínculo”)
- **Grupos de participação**

`[ABERTO]` São entidades distintas? Exemplos: igreja/núcleo vs equipe de refeição/voluntariado?

## 11. Identidade e autenticação (participante)

- OTP “parece ser a forma de menor atrito”.
- Aceite de termos de uso (marcar + versionar).

`[ABERTO]` Canal do OTP: e-mail, SMS, WhatsApp.

`[ABERTO]` Identificador da conta: e-mail, telefone, CPF, ou combinação.

`[ABERTO]` Pessoa (cadastro) vs Usuário (login): um usuário gerencia várias pessoas? Cônjuge tem login próprio?

## 12. Jornada do participante (Congresso de Famílias)

Fluxo listado na wiki:

1. Autenticar
2. Aceitar termos
3. Registrar inscrição em evento ativo (titular, dependentes, necessidades especiais, hospedagem, alimentação)
4. Editar inscrição **não paga**
5. Receber lembrete de pagamento
6. Consultar status de pagamento
7. Solicitar alteração de inscrição **já paga** (gestão aprova)
8. Cancelar inscrição não paga
9. Solicitar cancelamento de inscrição já paga
10. Consultar status de reembolso
11. Consultar inscrições de eventos anteriores
12. Entrar na fila de espera se esgotado
13. Consultar status da fila
14. Solicitar remoção/anonimização de dados

`[ABERTO]` “Evento ativo” = lote vigente + evento aberto + não encerrado?

`[ABERTO]` Necessidades especiais: flag ou texto? Por membro?

`[ABERTO]` Previsão de chegada (campo na seção Dados) é obrigatória? Por inscrição ou por membro?

## 13. Pagamento `[ABERTO — motivação principal, quase sem spec]`

Comportamentos implícitos na wiki:

- Inscrição pode existir não paga
- Lembrete de pagamento
- Cancelamento automático de não paga
- Após pago, alteração/cancelamento são solicitações
- Há reembolso com status consultável

Ainda não especificado: provedor, meios (PIX/cartão/boleto), momento da cobrança, o que acontece se o valor muda antes do pagamento, split, nota fiscal, prazos de cancelamento automático e de reembolso.

Decisões **candidatas** em issues, não herdadas até o drill confirmar: #25 Asaas; #26 cancelar não paga no vencimento; #28 política de reembolso (100%−taxas até 7 dias; 50% taxa + 100% alimentação até 15 dias antes).

## 14. Fila de espera

- Evento pode ter fila ativa
- Participante registra interesse quando esgotado
- Consulta status da solicitação
- Gestão gere a fila

`[ABERTO]` Ordenação (FIFO, prioridade), conversão em inscrição (prazo para pagar?), limite da fila, dados coletados no interesse.

## 15. LGPD

- Termos versionados + aceite
- Solicitação de remoção/anonimização

`[ABERTO]` Anonimizar vs apagar. Inscrição paga/fiscal pode ser apagada? Prazo de retenção. Quem executa o pedido (automático vs gestão). Escopo: só o solicitante ou o núcleo familiar.

## 16. Gestão de inscrições

- Visualizar + KPIs `[ABERTO: quais KPIs]`
- Editar / incluir / excluir `[ABERTO: excluir paga?]`
- Aprovar alteração e cancelamento pós-pagamento
- Relatórios pré, durante e pós evento `[ABERTO: conteúdo de cada um]`

## 17. Automações

- Cancelar inscrição não paga `[ABERTO: prazo, se libera vaga/lote, se avisa]`
- E-mail de confirmação `[ABERTO: após criar ou após pagar?]`
- E-mail de lembrete de não paga `[ABERTO: cadência]`

## 18. Modelo de dados (wiki truncada)

### Inscrição (único agregado iniciado na wiki)

- Id
- Evento
- DataInscricao
- PrevisaoaChegada
- Membros
- Adicionais

Isso já sugere mudança em relação ao legado (hoje 1 inscrição por pessoa). `[ABERTO]` Confirmar agregado: 1 inscrição (família) com membros, vs 1 inscrição por pessoa.

Entidades citadas na wiki e ainda sem campos: Evento, TipoEvento, Lote, ItemAdicional, Desconto, GrupoVinculo, GrupoParticipacao, TermoUso, Aceite, CredencialPagamento, FilaEspera, SolicitacaoAlteracao, SolicitacaoCancelamento, Reembolso, Usuario.

## 19. Requisitos não funcionais `[ABERTO]`

Volume esperado, hospedagem, SLA do checkout, e-mail, PII, backup, se a v1 é sistema novo ou evolução deste repositório.

## 20. Rascunho de modelo

Ver `modelo-dados.md`. A wiki só listou o agregado Inscrição e parou. Entidades citadas sem campos permanecem `[ABERTO]` até a rodada 2.
