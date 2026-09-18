# Constituição — Inscrições Next Gen

Regras que valem para o SDD e para sessões futuras de desenvolvimento. Mudar um item aqui é decisão explícita, não efeito colateral.

1. **Spec antes de código.** Nenhuma fatia Next Gen é implementada com regra `[ABERTO]`.
2. **Não inventar domínio.** Na dúvida, perguntar. Preferir opção explícita a default silencioso.
3. **Congresso de Famílias é o primeiro consumidor.** O modelo de tipos de evento já é genérico (Jovens, menores, outros). Não há fork de código por evento.
4. **Sistema novo (greenfield).** Este repositório é referência de domínio e fonte de migração, não a base da v1. Scripts de migração são à parte e opcionais na operação.
5. **Motor de pagamento é substituível por evento.** Cada evento escolhe o motor **e** as credenciais (contas financeiras distintas, fora do escopo deste sistema). A spec descreve o comportamento (gerar cobrança, confirmar, recusar, reembolsar, consultar), não um único provedor.
6. **LGPD é restrição, não feature opcional.** Aceite versionado, retenção e anonimização entram no desenho de dados desde o início.
7. **Uma inscrição paga não se altera sozinha.** Mudança de valor, membros ou itens após pagamento exige solicitação + aprovação, com trilha de auditoria.
8. **Operação do Congresso entra na v1.** Check-in, crachá, grupos operacionais (staff/banda/comitê), equipes de refeição e links seguros são produto, não “depois”.
9. **Cálculo de preço é determinístico.** Dado evento + lote + membros + itens + descontos + grupo operacional, o valor tem uma única regra documentada (precedência, empilhamento, arredondamento).
10. **Configuração vence hardcode.** Tipos de evento, modos de inscrição, itens, lotes, termos, motor/credenciais e prazo de cancelamento de não paga são dados de gestão.
11. **Sessões futuras só executam o que estiver FECHADO na spec.** Drill e implementação não se misturam na mesma fatia.
12. **Prazo real.** Janela de inscrições em janeiro, evento em abril. O PagSeguro legado já não funciona; PIX “externo” não é solução da v1.
