# SDD — Inscrições Next Gen

Processo de Specification-Driven Development para a próxima geração do sistema de inscrições da Meuc.

Fonte inicial: wiki [Next-gen](https://github.com/MeucDev/inscricoes/wiki/Next-gen) (ainda em expansão). Snapshot local: `fonte-wiki.md`.

## Objetivo

Chegar a uma especificação que um agente de desenvolvimento consiga executar **sem inventar regra de negócio**. Enquanto houver `[ABERTO]`, não há implementação daquela fatia.

## Como funciona o drill

1. A wiki e o legado alimentam `spec.md`.
2. Cada ambiguidade, lacuna ou conflito vira pergunta em `drill.md`.
3. Respostas atualizam a spec e fecham a pergunta (`[ABERTO]` → texto fechado).
4. Rodada 1 (espinha) está **fechada**. Preço, fila, LGPD, OTP e telas acompanham a expansão da wiki + rodada 2.

Responder na conversa por número (`Q13 = A`, `Q18 = …`) ou editando `drill.md`. Atualizações da wiki devem ser sincronizadas na spec — não ficam só na wiki.

## Artefatos

| Arquivo | Papel |
|---|---|
| `constitution.md` | Princípios que não mudam de uma sessão para outra |
| `fonte-wiki.md` | Texto da wiki congelado na data do drill (desatualiza conforme a wiki cresce) |
| `spec.md` | Spec viva (o que o sistema deve fazer) |
| `legacy-inventory.md` | O que o sistema atual já faz; itens aceitos na v1 estão marcados |
| `modelo-dados.md` | Rascunho do modelo |
| `drill.md` | Log das rodadas de esclarecimento |

## Status

- Spec: rascunho com **espinha fechada** (Q1–Q12)
- Drill: rodada 1 **fechada**; rodada 2 disponível (autor expandindo a wiki)
- Implementação de produto: ainda bloqueada (domínio de preço/LGPD/OTP e stack)
- Go-live alvo: janela de inscrições em **janeiro**, evento em **abril**; PagSeguro legado **já inoperante**
