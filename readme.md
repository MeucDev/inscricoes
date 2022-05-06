# Sistema de Inscrições

## Requisitos

- node.js (10.9.0)
- Composer https://getcomposer.org/
- MySQL Community (or MariaDB)
- PHP 5.6.x or higher and the extensions:
  - Mcrypt
  - OpenSSL
  - Mbstring
  - Tokenizer
  - FileInfo

- Restaurar a base congfam no banco de dados

> Para facilitar a instalação local do banco de dados,
> também é possível criar a instância via [Docker](https://www.docker.com/).  
> Basta instalar, incializar e rodar o comando abaixo na pasta base do projeto:
> ```bash
> docker compose up -d
> ```

## Instalação

```php
$ composer install
```

## Configuração

- Fazer uma cópia do arquivo `.env.example` e renomear para `.env`
  - Configurar a conexão com o banco de dados

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

## npm

- Instalar dependências de ambiente

  Em um terminal com privilégios elevados:
  ```bash
  npm install --global --production windows-build-tools
  ```

- Instalar as dependências de client-side
  ```bash
  npm install
  ```

  > Se ocorrerem erros de instalação do sass, instalar

- Compilar aplicação front-end
  ```bash
  npm run dev
  ```

## Run

```php
$ php artisan serve
```

## Usuário e senha

E-mail: `admin@crudbooster.com`  
Senha: `123456`

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
