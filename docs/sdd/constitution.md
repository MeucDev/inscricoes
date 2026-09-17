# Constituição — Inscrições Next Gen

Regras que valem para o SDD e para sessões futuras de desenvolvimento. Mudar um item aqui é decisão explícita, não efeito colateral.

1. **Spec antes de código.** Nenhuma fatia Next Gen é implementada com regra `[ABERTO]`.
2. **Não inventar domínio.** Na dúvida, perguntar. Preferir opção explícita a default silencioso.
3. **Congresso de Famílias é o evento de referência.** Outros eventos da Meuc entram por configuração de tipo, não por fork de código — salvo decisão contrária na spec.
4. **Motor de pagamento é substituível.** A spec descreve o comportamento (gerar cobrança, confirmar, recusar, reembolsar, consultar), não amarra a implementação a um único provedor sem registrar essa decisão.
5. **LGPD é restrição, não feature opcional.** Aceite versionado, retenção e anonimização entram no desenho de dados desde o início.
6. **Uma inscrição paga não se altera sozinha.** Mudança de valor, membros ou itens após pagamento exige solicitação + aprovação (como na wiki), com trilha de auditoria.
7. **O legado informa, não dita.** Comportamento atual só é copiado se a spec confirmar. Features operacionais do Congresso (check-in, crachá, tipos COMITE/BANDA/STAFF, links seguros) ficam em `legacy-inventory.md` até serem aceitas, adiadas ou descartadas.
8. **Cálculo de preço é determinístico.** Dado evento + lote + membros + itens + descontos, o valor tem uma única regra documentada (precedência, empilhamento, arredondamento).
9. **Configuração vence hardcode.** Tipos de evento, itens adicionais, lotes, termos e credenciais são dados de gestão, não código por evento.
10. **Sessões futuras só executam o que estiver FECHADO na spec.** Drill e implementação não se misturam na mesma fatia.
