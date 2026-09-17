# Inventário do legado (wiki ainda não cobre)

Leitura do código atual (`master`) e das issues abertas, para o drill não
“descobrir” o Congresso de Famílias na hora de implementar. Nada aqui é
requisito Next Gen até ser aceito na spec.

## Unidade de inscrição hoje

- **Pessoa** é a identidade persistente (chave prática: CPF). Cônjuge e dependentes são outras Pessoas.
- **Inscrição** é 1 registro por pessoa por evento. O responsável aponta `numero_inscricao_responsavel` nos dependentes. O valor total do responsável soma os dependentes.
- Titular tem `TIPO = 'R'`; cônjuge `C`; dependentes outro tipo. Só o responsável paga taxa de inscrição (`NORMAL`); cônjuge/dependentes têm inscrição R$ 0 e pagam itens.
- Tipos internos de inscrição (não confundir com TIPO da pessoa): `NORMAL`, `BANDA`, `COMITE`, `STAFF`.
  - `COMITE` + alojamento `CAMPING` zera taxa de camping.
  - Links seguros (`LinkInscricao`) e query `p` permitem esses tipos e ignoram limite do evento.

## Preço hoje

- Tabela `valores` por evento + código (`NORMAL`, alojamento, refeição).
- `valor_variacoes`: preço por janela de data (`data_ate`) **ou** faixa de idade. Isso é o “lote” atual, misturado com variação etária.
- Limite de inscrições conta só responsáveis `NORMAL` (não BANDA/COMITE/STAFF).
- Quando o limite do lote atual estoura e existe próximo `data_ate`, a UI diz que o próximo lote começa nessa data.
- Campo `limite_refeicoes` existe no evento.

## Descontos hoje

- Percentual por CPF (precede), senão por UF, senão por cidade. (#31, #61)
- Desconto de evento anterior: valor fixo **ou** valor pago no evento origem, aplicado no evento destino (`evento_aplicar_id` / `evento_origem_id`). Não é o “progressivo para dependentes” da wiki.

## Pagamento hoje

- PagSeguro legado (`allw/laravel-pagseguro`), checkout por inscrição responsável.
- Webhook de notificação + consulta assíncrona em análise (`analysis/ConsultaPagamentos`, tabela `consultas_pagamento`).
- Histórico de pagamentos (`valorLiquido`, `valorTaxas`, `formaPagamento`).
- Flags `inscricaoPaga`, `cancelada`. Cancelamento automático de não pagas **não** está ligado; a análise sugere 7 dias. (#26)

## Operação de evento hoje (ausente na wiki)

- Check-in / presença (`presencaConfirmada`, `checkin_em`). (#36, #37)
- Crachá (`nomecracha`, componentes Vue). (#59, #70)
- Equipes de refeição (`equipeRefeicao`: QUIOSQUE_A/B, LAR_A/B) com balanceamento na confirmação de presença (`todo.txt`).
- Necessidades especiais (flag boolean). (#38)
- Data de casamento (campo recente).
- Fila de espera: página estática pedindo e-mail para `contato@congressodefamilias.com.br` — não há entidade de solicitação.
- Relatórios/admin via CRUDBooster (Lar, Quiosque, equipes, contagens por tipo).

## Stack hoje

- Laravel 5.3 + CRUDBooster 5.4 + PHP ≥ 5.6 + Composer 2.2 + MySQL 5.7 + Vue 2 + Gulp 3.
- Motivação da wiki: frameworks descontinuados + PagSeguro legado + LGPD + unificação.
- Issue #16: atualizar Laravel (8/9) e PHP 8.

## Issues que já decidem produto (não herdadas automaticamente)

Confirmar ou rejeitar na rodada 2; a wiki não as cita.

| Issue | Decisão candidata | Estado |
|---|---|---|
| #25 | Trocar PagSeguro por **Asaas** (cobrança vs link ainda em aberto) | OPEN |
| #26 | Cancelar não paga quando o gateway expirar; avisar na confirmação | OPEN |
| #27 | Usuário autenticado edita/cancela inscrição **não paga** | OPEN |
| #28 | Reembolso: 100%−taxas até 7 dias; 50% taxa + 100% alimentação até 15 dias antes do evento | OPEN |
| #18 | Página de uso dos dados; remoção; opt-out de guardar dados; anos anteriores só autenticado | OPEN |
| #17 | Itens extras genéricos por categoria (refeição deixa de ser campo nativo) | OPEN |
| #30 | Famílias do Paraguai (endereço/documento) | OPEN |
| #63 | Gratuidade por substituição de família | OPEN |
| #60 | Complemento de endereço | OPEN |
| #62 | Integração de CEP / estados e cidades | OPEN |
| #66 | E-mail “não responda” + contato congressodefamilias | OPEN |
| #33 #35 #65 | Cancelar inscrição / persistir remoção de dependente | OPEN |

Presente no legado e **não** citado na wiki: links seguros, crédito de evento anterior, isenção camping COMITE, `inativo` em pessoa, integração C# de consulta PagSeguro, hospedagem LAR ainda no código (#29 removeu a opção no produto).

## Implicação para o drill

Se a spec não decidir o destino de cada item acima, a implementação Next Gen vai ou perder operação real do Congresso, ou reintroduzir legado por acidente.
