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

## Migração
```php
$ php artisan migrate
```

## Run
```php
$ php artisan serve
```