# Diagnóstico: Erro Foreign Key - consultas_pagamento

## Erro
```
SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint
```

## Causas Possíveis

O erro 1215 do MySQL indica que a constraint de foreign key não pôde ser criada. Possíveis causas:

1. **A coluna `inscricoes.numero` não é PRIMARY KEY ou UNIQUE**
   - Para criar uma foreign key, a coluna referenciada deve ser PRIMARY KEY ou ter índice UNIQUE
   - Verificar: `SHOW CREATE TABLE inscricoes;`

2. **Incompatibilidade de tipos de dados**
   - A coluna `consultas_pagamento.inscricao_numero` deve ter o mesmo tipo que `inscricoes.numero`
   - Verificar tipos: `DESCRIBE inscricoes;` e `DESCRIBE consultas_pagamento;`

3. **Engine da tabela diferente (MyISAM vs InnoDB)**
   - Foreign keys só funcionam com InnoDB
   - Verificar: `SHOW TABLE STATUS WHERE Name = 'inscricoes';`

4. **Dados órfãos já existentes**
   - Se a tabela `consultas_pagamento` já tiver dados, eles devem corresponder a valores existentes em `inscricoes.numero`

## Comandos SQL para Diagnóstico

Execute estes comandos no banco de produção para identificar o problema:

```sql
-- 1. Verificar estrutura da tabela inscricoes
SHOW CREATE TABLE inscricoes;

-- 2. Verificar se numero é PRIMARY KEY
DESCRIBE inscricoes;

-- 3. Verificar engine da tabela
SHOW TABLE STATUS WHERE Name = 'inscricoes';

-- 4. Verificar índices na coluna numero
SHOW INDEX FROM inscricoes WHERE Column_name = 'numero';

-- 5. Verificar tipo de dados da coluna numero
SELECT COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'inscricoes'
  AND COLUMN_NAME = 'numero';
```

## Solução Temporária (Se Necessário)

Se a foreign key não puder ser criada, você pode:

1. **Remover a constraint da migration** (não recomendado, mas funciona)
2. **Criar a constraint manualmente após verificar/corrigir a estrutura**
3. **Garantir que `inscricoes.numero` seja PRIMARY KEY ou UNIQUE**

## Migration Ajustada

A migration foi ajustada para:
- Remover `unsigned()` (ficar igual às outras migrations do projeto)
- Adicionar `dropIfExists` para remover tabela se já existir (caso tenha sido criada parcialmente)
- **Remover a criação da foreign key temporariamente** - a tabela será criada sem a constraint
- A foreign key pode ser criada manualmente após verificar/corrigir a estrutura (veja seção abaixo)

## Criar Foreign Key Manualmente (Se Necessário)

Se a migration criar a tabela sem a foreign key, você pode adicioná-la manualmente após verificar/corrigir a estrutura:

```sql
-- Verificar se a constraint já existe
SELECT CONSTRAINT_NAME 
FROM information_schema.TABLE_CONSTRAINTS 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'consultas_pagamento' 
  AND CONSTRAINT_TYPE = 'FOREIGN KEY'
  AND CONSTRAINT_NAME = 'consultas_pagamento_inscricao_numero_foreign';

-- Se não existir, criar manualmente
ALTER TABLE consultas_pagamento 
ADD CONSTRAINT consultas_pagamento_inscricao_numero_foreign 
FOREIGN KEY (inscricao_numero) 
REFERENCES inscricoes(numero) 
ON DELETE CASCADE;
```

**Nota:** Se a foreign key não puder ser criada mesmo manualmente, verifique se:
1. `inscricoes.numero` é PRIMARY KEY ou tem índice UNIQUE
2. Os tipos de dados coincidem exatamente
3. A tabela `inscricoes` usa engine InnoDB (não MyISAM)
