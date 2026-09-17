# Snapshot da wiki Next-gen

Copiado de `https://github.com/MeucDev/inscricoes/wiki/Next-gen` em 2026-09-17
(commit wiki `b56e8bcbf132e9c2f7252ceb4f8de221a3d7fef0`).

A wiki continua sendo a fonte editável pelo mantenedor. Este arquivo só
congela o texto que alimentou o drill, para sessões futuras não dependerem
de clone da wiki.

---

# Introduçao
Esta página descreve as funcionalidades que farão parte do sistema de inscrições para eventos da Meuc em sua "next generation". 
A motivação inicial vem da necessidade de substituir o motor de pagamentos do sistema de inscrições do Congresso de Famílias. A versão legada do PagSeguro sendo utilizada foi descontinuada, fazendo com que pelo menos esta parte do sistema tenha que ser reescrita. Existem lacunas legais que precisam ser preenchidas (relacionadas à LGPD), além de diversos desejos de otimizações e melhorias a serem incluídas neste sistema. As versões de frameworks e bibliotecas utilizadas estão bastante defasadas ou até mesmo descontinuadas, adicionando riscos de segurança ao sistema. Tudo isso faz com que esse seja o momento ideal para considerar e implementar uma nova versão do sistema.
Conversas com os mantenedores de outros sistemas de inscrições para eventos da Meuc já ocorreram e existe um desejo de criar um sistema unificado que atenda as necessidades de diversos tipos de eventos, cada um com suas características específicas, além dos elementos comuns a todos eles.

# Funcionalidades gerais de gestão

- Gerenciar descontos
  - Percentual de desconto
  - Aplicação por CPF e/ou distância
  - Desconto progressivo para dependentes adicionais
- Gerenciar grupos para vínculo de inscrição
- Gerenciar itens adicionais para eventos
  - Hospedagem e respectivos valores (com variações baseadas na data de nascimento)
  - Alimentações e respectivos valores (com variações baseadas na data de nascimento)
- Gerenciar tipos de eventos
  - Possui desconto
  - Exige vínculo com grupo
  - Inscrição individual
  - Inscrição de responsável e dependentes
  - Inscrição de responsável em nome de dependentes
- Gerenciar termos de uso
  - Marcar aceite
  - Gerenciar versão/atualização de termos de uso
- Gerenciar grupos de participação
- Gerenciar credenciais de motor de pagamento
- Gerenciar lotes de inscrições
  - Data de abertura
  - Data de encerramento
  - Limite de inscrições
  - Valor taxa
- Gerenciar eventos
  - Selecionar termos de uso
  - Selecionar credenciais de motor de pagamentos
  - Informações gerais do evento (nome, data, descrição, capa, favicon, endereço, etc)
  - Tipo de evento
  - Itens adicionais
  - Lotes
  - Fila de espera ativa
- Duplicar eventos a partir de um evento anterior
  - Deep clone

- Visualizar inscrições realizadas
  - KPIs
- Editar inscrições realizadas
- Incluir inscrições
- Excluir inscrições
- Aprovar solicitações de alteração em inscrições já pagas
- Aprovar solicitações de cancelamento em inscrições já pagas
- Gerir fila de espera

- Relatórios/dashboard pré-evento
- Relatórios/dashboard durante evento
- Relatórios/dashboard pós evento

# Funcionalidades automáticas
- Cancelamento de inscrição não paga
- Envio de e-mail de confirmação de inscrição
- Envio de e-mail de lembrete de inscrição não paga

# Funcionalidades para o usuário final do Congresso de Famílias
- Autenticar-se ao sistema (OTP parece ser a forma que gerará menor atrito aos diversos perfis de usuários)
- Aceitar termos de uso
- Registrar uma inscrição em um evento ativo (dados de titular e dependentes, indicar necessidades especiais; gerir itens adicionais - hospedagem, alimentação)
- Editar detalhes de uma inscrição não paga
- Receber lembretes de pagamento pendente para uma inscrição não paga
- Consultar status de pagamento de uma inscrição realizada
- Solicitar aprovação de alterações em uma inscrição já paga
- Cancelar uma inscrição não paga
- Solicitar cancelamento de uma inscrição já paga
- Consultar status de reembolso de uma inscrição com solicitação de cancelamento
- Consultar detalhes de inscrições realizadas em eventos anteriores
- Registrar interesse em participar da fila de espera de um evento com inscrições já esgotadas
- Consultar status de solicitação de fila de espera
- Solicitar remoção/anonimização de dados

# Dados
Esta sessão descreve os dados que precisam ser persistidos

## Inscrição 
- Id
- Evento
- DataInscricao 
- PrevisaoaChegada
- Membros
- Adicionais
