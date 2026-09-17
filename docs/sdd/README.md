# SDD — Inscrições Next Gen

Processo de Specification-Driven Development para a próxima geração do sistema de inscrições da Meuc.

Fonte inicial: wiki [Next-gen](https://github.com/MeucDev/inscricoes/wiki/Next-gen) (ainda incompleta). Snapshot local: `fonte-wiki.md`.

## Objetivo

Chegar a uma especificação que um agente de desenvolvimento consiga executar **sem inventar regra de negócio**. Enquanto houver `[ABERTO]`, não há implementação daquela fatia.

## Como funciona o drill

1. A wiki e o legado alimentam `spec.md`.
2. Cada ambiguidade, lacuna ou conflito vira pergunta em `drill.md`.
3. Respostas atualizam a spec e fecham a pergunta (`[ABERTO]` → texto fechado).
4. Só depois de fechar as decisões de espinha (`drill.md` rodada 1) detalhar preço, fila, LGPD, telas e migração.

Responder na conversa por número (`Q1 = A`, `Q4 = A com ressalva…`) ou editando `drill.md`.

## Artefatos

| Arquivo | Papel |
|---|---|
| `constitution.md` | Princípios que não mudam de uma sessão para outra |
| `fonte-wiki.md` | Texto da wiki congelado na data do drill |
| `spec.md` | Spec viva (o que o sistema deve fazer) |
| `legacy-inventory.md` | O que o sistema atual já faz e a wiki ainda não cobre |
| `modelo-dados.md` | Rascunho do modelo; a seção Dados da wiki está truncada |
| `drill.md` | Log das rodadas de esclarecimento |

## Status

- Spec: rascunho a partir da wiki
- Drill: **rodada 1 em andamento** (Q1–Q12, decisões de espinha)
- Implementação: bloqueada até fechar a rodada 1
- Rodada 2 (Q13–Q42) está escrita e bloqueada até Q1–Q12
