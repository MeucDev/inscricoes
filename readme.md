## Requisitos
- node.js (10.9.0)
- Composer https://getcomposer.org/ (2.2)
- MySQL Community (5.7)
- Php 5.6.x or higher and the extensions:
  - Mcrypt
  - OpenSSL
  - Mbstring
  - Tokenizer
  - FileInfo

- Restaurar a base congfam no mysql 

## Instalação
```php
$ composer install
```

## Configuraçao
- Fazer uma cópia do arquivo .env.example e renomear para .env
  - configurar a conexão com o banco mysql

- Migração do banco
```php
$ php artisan migrate
```
- Populando tabelas 
```php
$ php artisan db:seed
```
- Publicando bibliotecas de terceiros
```php
$ php artisan vendor:publish
```
- Configurando application key
```php
$ php artisan key:generate
```
- Em ambiente de produção carregar a configuração para o cache
```php
$ php artisan config:cache
```

## NPM
- Instalar dependências de ambiente
Em um terminal com privilégios elevados:
```
npm install --global --production windows-build-tools
```
- Instalar as dependências de client-side
```
npm install
```
Se ocorrerem erros de instalação do sass, instalar
- Compilar aplicação front-end
```
npm run dev
```

## Run (dev)
```php
$ php artisan serve
```

## Usuário e senha
default email : admin@crudbooster.com
default password : 123456

## Ambiente UMBLER

O valor de error reporting deve estar como "E_ERROR & ~E_ALL"

### E-mail no Umbler

É necessário configurar as credenciais de e-mail e definir uso de tls no arquivo .env

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=usuario@gmail.com
MAIL_PASSWORD=senhadoemail
MAIL_ENCRYPTION=tls
```

As mesmas configurações precisam ser persistidas em "Configurações de E-mail".

## Atualizações em produção

Atualizações em produção se dão através de acesso SSH ao servidor.
O sistema está publicado na pasta public através de um clone do repositório neste caminho. 
Então, para atualizá-lo, deve-se navegar para a pasta ~/public e executar `git pull`.
Para atualizações que envolvam os assuntos abaixo, outros passos são necessários.
- Alterações na base de dados
  - Verificar status das migrações aplicadas
  ```
  php artisan migrate:status
  ``` 
  - Verificar alterações a serem alteradas
  ```
  php artisan migrate --pretend
  ```
  Se todos os comandos estão de acordo, o próximo passo pode ser executado.
  - Executar migração de dados
  ```
  php artisan migrate
  ```
  Para mais informações, acesse a documentação do Laravel (versão 5.3): https://laravel.com/docs/5.3/migrations
- Novo módulo
  - Criar módulo pelo gerador de módulos do CrudBooster de desenvolvimento
  - Efetuar implementações do módulo
  - Criar Seeder para inserir o módulo na base de produção (atenção para sufixo Controller na coluna indicando o controller a ser carregado)
  - Executar seeder no servidor
    ```
    php artisan db:seed --class=NomeDaClasseDeSeeder
    ```
  - Limpar e regerar informações para o composer
    ```
    php artisan clear-compiled 
    composer dump-autoload
    php artisan optimize
    ```
  - Limpar e regerar cache do laravel
    ```
    php artisan config:cache
    ```
  
- Alterações de front end
  - Antes do commit, executar `npm run prod`, e commitar arquivos de front otimizados para produção
