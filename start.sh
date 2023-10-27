#!/bin/sh

# Start Apache in the foreground
apache2-foreground &

# Wait for Apache to start
sleep 5

# Log a message to indicate the start of Apache
echo "start.sh : Apache started"

# Wait for MySQL to start
until nc -z -v -w30 database 3306
do
  echo "start.sh : Waiting for MySQL to start to continue"
  sleep 5
done

# Log a message to indicate the start of MySQL
echo "start.sh : MySQL started"

# Change directory to the project root
cd /var/www/html

# Install Composer dependencies
composer install

# Build database
php bin/console doctrine:database:create --env=dev
php bin/console doctrine:schema:create --env=dev
echo "yes" | php bin/console doctrine:fixtures:load --env=dev

# Extract JWT passphrase from .env
JWT_PASSPHRASE=$(grep 'JWT_PASSPHRASE' .env.local | cut -d '=' -f 2)

# Generate private key
openssl genpkey -out /var/www/html/config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass pass:"$JWT_PASSPHRASE" 2>&1

# Generate public key
openssl pkey -in /var/www/html/config/jwt/private.pem -out /var/www/html/config/jwt/public.pem -pubout -passin pass:"$JWT_PASSPHRASE" 2>&1

# Log the result of public key generation
echo "start.sh : Private key generation result: $?"
echo "start.sh : Public key generation result: $?"
echo "start.sh : Generation of JWT keys completed"

# Log a message to indicate the end of the script
echo "start.sh : End of script execution, container ready"

# Keep container running
tail -f /dev/null
