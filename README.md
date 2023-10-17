# OpenClassrooms - Create a web service exposing an API

## Repository containing the context and deliverables of the project
https://github.com/Galuss1/openclassrooms-archive/tree/main/php-symfony-application-developer/project-7

## Setting up

### Required
1. [PHP 8.1](https://www.php.net/downloads.php)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://www.mysql.com/fr/downloads/)

### Optional
1. [Docker](https://www.docker.com/)
2. [Symfony CLI](https://symfony.com/download)

### Installation
1. **Clone the repository on the main branch**
<br>

2. **Generate your public and private keys and create your passphrase**<br>
**The keys must be in the "config/jwt" folder, simply use the commands below from the project root.**
```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```
```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
<br>

3. **Create the .env.local file and replace the values of the .env origin file**
```bash
###> symfony/framework-bundle ###
APP_ENV=#env|prod|test#
APP_SECRET=#secret#
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=#"mysql://user:password@host:port/database?serverVersion=15&charset=utf8"#
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=#passphrase#
###< lexik/jwt-authentication-bundle ###

###> docker/database ###
DATABASE_HOST=#database_host#
MYSQL_DATABASE=#database_name#
MYSQL_ROOT_PASSWORD=#database_root_password#
MYSQL_USER=#database_user#
MYSQL_PASSWORD=#database_user_password#
MYSQL_DATABASE_TEST=#database_test_name#
###< docker/database ###
```
<br>

4. **If you are using docker, install your environment and start the project**
```bash
docker-compose up --build -d
```
<br>

5. **Installing dependencies**
```bash
composer install
```
<br>

6. **Setting up the database**<br>
*If you are using docker, the first command is not necessary and the database "training_bilemo" is already created without the data at localhost:3310*
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```
<br>

7. **Start the project**<br>
*If you are using docker, the project is already accessible at http://localhost:8080*
```bash
php -S 127.0.0.1:8080 -t public
```
```bash
symfony server:start
```
<br>

 --- --- ---

### Links
[Website](https://www.formation.bilemo.gaelpaquien.com)\
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-bilemo/dashboard)
