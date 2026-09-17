# Inventário do legado (wiki ainda não cobre)

Leitura do código atual (`master`) para o drill não “descobrir” o Congresso de Famílias na hora de implementar. Nada aqui é requisito Next Gen até ser aceito na spec.

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

## Descontos hoje

- Percentual por CPF (precede), senão por UF, senão por cidade.
- Desconto de evento anterior: valor fixo **ou** valor pago no evento origem, aplicado no evento destino (`evento_aplicar_id` / `evento_origem_id`). Não é o “progressivo para dependentes” da wiki.

## Pagamento hoje

- PagSeguro legado (`laravel/pagseguro`), checkout por inscrição responsável.
- Webhook de notificação + consulta assíncrona em análise (`analysis/ConsultaPagamentos`).
- Histórico de pagamentos; flags `inscricaoPaga`, `cancelada`.
- Cancelamento automático de não pagas (há coluna e análise de 7 dias).

## Operação de evento hoje (ausente na wiki)

- Check-in / presença (`presencaConfirmada`, `checkin_em`).
- Crachá (`nomecracha`, componentes Vue).
- Equipes de refeição (`equipeRefeicao`: QUIOSQUE_A/B, LAR_A/B) com balanceamento na confirmação de presença.
- Necessidades especiais (flag).
- Data de casamento (campo recente).
- Fila de espera atual: página estática pedindo e-mail para `contato@congressodefamilias.com.br` — não há entidade de solicitação.
- Relatórios/admin via CRUDBooster.

## Stack hoje

- Laravel 5.3 + CRUDBooster + PHP 5.6+/Composer 2.2 + MySQL 5.7 + Vue no front + Node antigo.
- Motivação da wiki: frameworks descontinuados + PagSeguro legado + LGPD + unificação.

## Implicação para o drill

Se a spec não decidir o destino de cada item acima, a implementação Next Gen vai ou perder operação real do Congresso, ou reintroduzir legado por acidente.
