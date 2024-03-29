# Use the official PHP 8.1 image with Apache
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y git curl unzip libzip-dev libicu-dev netcat-openbsd openssl && \
    docker-php-ext-install pdo pdo_mysql zip intl opcache && \
    pecl install apcu && docker-php-ext-enable apcu && \
    a2enmod rewrite && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add custom php.ini settings
RUN echo "display_errors = Off" >> /usr/local/etc/php/php.ini && \
    echo "log_errors = On" >> /usr/local/etc/php/php.ini && \
    echo "upload_max_filesize = 32M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 32M" >> /usr/local/etc/php/php.ini && \
    echo "memory_limit = 256M" >> /usr/local/etc/php/php.ini && \
    echo "session.cookie_secure = true" >> /usr/local/etc/php/php.ini && \
    echo "SMTP = mailhog" >> /usr/local/etc/php/php.ini && \
    echo "smtp_port = 1025" >> /usr/local/etc/php/php.ini

# Apache configuration
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    DirectoryIndex index.php\n\
    </Directory>\n' > /etc/apache2/conf-available/symfony.conf && \
    a2enconf symfony && \
    sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Set user variables
ARG UID=1000
ARG GID=1000

# Create a non-root user (webuser) and group with the specified UID / GID
RUN groupadd -g ${GID} webuser && useradd -u ${UID} -g webuser -s /bin/bash -m webuser

# Change the owner of the multiple directories to webuser
RUN chown -R webuser:webuser /var/www/html

# Copy application source with correct permissions
COPY --chown=webuser:webuser . /var/www/html

# Give execute permissions to start.sh
RUN chmod +x /var/www/html/start.sh

# Command to start the existing custom startup script
CMD ["sh", "/var/www/html/start.sh"]

# Use the webuser user
USER webuser
