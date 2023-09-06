# Use the official PHP-FPM image as the base image
FROM php:8.2-fpm

# Install MySQLi extension and other dependencies
RUN docker-php-ext-install mysqli && \
    apt-get update && \
    apt-get install -y \
        wget \
        unzip \
        && rm -rf /var/lib/apt/lists/* \
    && wget https://phar.phpunit.de/phpunit-9.5.phar && chmod +x phpunit-9.5.phar && mv phpunit-9.5.phar /usr/local/bin/phpunit

# Set the working directory to /var/www/html
WORKDIR /var/www/html