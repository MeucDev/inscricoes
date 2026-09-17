# SDD — Inscrições Next Gen

Processo de Specification-Driven Development para a próxima geração do sistema de inscrições da Meuc.

Fonte inicial: wiki [Next-gen](https://github.com/MeucDev/inscricoes/wiki/Next-gen) (ainda incompleta).

## Objetivo

Chegar a uma especificação que um agente de desenvolvimento consiga executar **sem inventar regra de negócio**. Enquanto houver `[ABERTO]`, não há implementação daquela fatia.

## Como funciona o drill

1. A wiki e o legado alimentam `spec.md`.
2. Cada ambiguidade, lacuna ou conflito vira pergunta em `drill.md`.
3. Respostas atualizam a spec e fecham a pergunta.
4. Só depois de fechar as decisões de espinha (`drill.md` rodada 1) faz sentido detalhar regras de cálculo, telas e migração.

## Artefatos

| Arquivo | Papel |
|---|---|
| `constitution.md` | Princípios que não mudam de uma sessão para outra |
| `spec.md` | Spec viva (o que o sistema deve fazer) |
| `legacy-inventory.md` | O que o sistema atual já faz e a wiki ainda não cobre |
| `drill.md` | Log das rodadas de esclarecimento |

## Status

- Spec: rascunho a partir da wiki
- Drill: rodada 1 em andamento (decisões de espinha)
- Implementação: bloqueada até fechar a rodada 1
