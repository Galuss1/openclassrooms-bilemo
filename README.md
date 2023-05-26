# PHP/Symfony application developer training - OpenclassRooms (P7)

## Exercise: Create a web service exposing an API

### Skills assessed
1. Exposing a REST API with Symfony
2. Designing an efficient and adapted architecture
3. Analyze and optimize the performance of an application
4. Monitor the quality of a project
5. Launch an authentication for each HTTP request
6. Produce a technical documentation

--- --- ---

### Setting up the project

#### Required
1. [PHP 8.1](https://www.php.net/downloads.php)
2. [Symfony CLI](https://symfony.com/download)
3. [Composer](https://getcomposer.org/download/)
4. [MySQL](https://www.mysql.com/fr/downloads/)

#### Installation
1. **Clone the repository**
```bash
git clone https://github.com/Galuss1/openclassrooms-bilemo.git
```
<br />

2. **Generate your public and private keys and create your passphrase.**<br />
**The keys must be in the "config/jwt" folder, simply use the commands below from the project root.**
```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```
```bash
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
<br />

3. **Copy the entire contents of the .env file into a new .env.local file and complet it with your informations.**
```bash
###> symfony/framework-bundle ###
APP_ENV=#dev | prod | test
APP_SECRET=#secret key
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=#"mysql://{db_user}:{db_pass}@{db_host}:{db_port}/{db_name}"
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=#passphrase
###< lexik/jwt-authentication-bundle ###

```
<br />

4. **Installing dependencies**
```bash
composer install
```
<br />

5. **Setting up the database**
```bash
symfony console doctrine:database:create
```
```bash
symfony console doctrine:schema:create
```
```bash
symfony console doctrine:fixtures:load
```
<br />

6. **Start the project**
```bash
symfony server:start
```

 --- --- ---

### Links
[API Documentation](https://www.bilemo.gael-paquien.fr/api/doc)<br />
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-bilemo/dashboard)
