# SDD — Next-gen (inscrições MEUC)

Pacote de Specification-Driven Development iniciado a partir da wiki
[Next-gen](https://github.com/MeucDev/inscricoes/wiki/Next-gen).

A wiki ainda está incompleta (a seção **Dados** para no agregado `Inscrição`).
Este pacote não implementa o sistema: ele transforma o que já está documentado
em um inventário verificável e em um **drill** para fechar ambiguidades antes
de qualquer sessão de código.

## Como usar

1. `fonte-wiki.md` é o texto original (incompleto de propósito).
2. Ler `inventario.md` — o que a wiki pede, o que o legado já faz, o que as
   issues abertas já decidiram.
3. Responder `drill.md` — cada pergunta tem opções, uma hipótese e um campo
   `Resposta`. Editar o arquivo (ou responder na conversa citando o ID, ex. `D0.2 = B`).
4. Com as respostas P0/P1, a próxima sessão deve gerar:
   - `spec.md` (requisitos fechados)
   - `modelo-dados.md` completo (hoje é só rascunho)
   - `plan.md` / `tasks.md` por fatia vertical

Não começar implementação enquanto as perguntas **P0** estiverem em PENDENTE.

## Arquivos

| Arquivo | Papel |
|---|---|
| `fonte-wiki.md` | Texto da wiki congelado na data do drill |
| `inventario.md` | Mapa wiki × legado × issues |
| `drill.md` | Questionário de esclarecimento (entregável principal) |
| `modelo-dados.md` | Rascunho do modelo; lacunas da seção Dados |

## Estado em 2026-09-17

- Fonte: wiki `Next-gen.md` (4.2 KB, 85 linhas), commit wiki `b56e8bc`.
- Código legado: Laravel 5.3 / PHP ≥ 5.6 / CrudBooster 5.4 / Vue 2 / PagSeguro.
- Este drill cobre Congresso de Famílias como primeiro recorte, e o desejo
  declarado de unificar outros eventos MEUC.
