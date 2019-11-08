## Requisitos
- Composer https://getcomposer.org/
- MySQL Community
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

## Run
```php
$ php artisan serve
```

## Usuário e senha
default email : admin@crudbooster.com
default password : 123456
