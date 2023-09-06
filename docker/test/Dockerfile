# Use the official PHP-FPM image as the base image
FROM php:8.2-fpm

# Install MySQLi extension and other dependencies
RUN docker-php-ext-install mysqli && \
    apt-get update && \
    apt-get install -y \
        wget \
        unzip \
        && rm -rf /var/lib/apt/lists/* \
    && wget https://getcomposer.org/installer -O composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer global require "phpunit/phpunit:^9"

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www/html

CMD ["phpunit", "--colors=always", "./test/php/"]