# OpenClassrooms - BileMo

## Repository containing the context and deliverables of the project
https://github.com/Galuss1/openclassrooms-archive/tree/main/php-symfony-application-developer/project-7

## Setting up

### Required
1. [PHP ⩾8.1](https://www.php.net/downloads.php)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://www.mysql.com/fr/downloads/)

### Optional
1. [Docker](https://www.docker.com/)
2. [Symfony CLI](https://symfony.com/download)

### Installation
1. **Clone the repository on the main branch**

2. **Create the .env.local file and replace the values of the .env origin file**

3. **Generate your public and private keys and create your passphrase** \
*The keys must be in the "config/jwt" folder, simply use the commands below from the project root.*
```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```
```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

4. **If you are using docker, install your environment and start the project**
```bash
docker-compose up --build
```
Wait a few moments for the environment to fully install. \
The API doc is accessible at http://localhost:8080/api/doc \
Mailhog web interface (SMTP) is accessible at http://localhost:8025 \
The database was created with data at localhost:3310 \
Your installation is complete, you do not need to follow the next steps.

5. **Installing dependencies**
```bash
composer install
```

6. **Setting up the database**
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```

7. **Start the project**
```bash
php -S 127.0.0.1:8080 -t public
```
```bash
symfony server:start
```

 --- --- ---

### Links
[Website](https://www.formation.bilemo.gaelpaquien.com)\
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-bilemo/dashboard)
