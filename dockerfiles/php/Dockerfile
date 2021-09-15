FROM php:7.4-fpm
USER root

# Install composer
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

# PHP extensions
RUN docker-php-ext-install sockets && docker-php-ext-install pdo_mysql

# Installing needed tools
RUN apt-get update && apt-get install -y git

WORKDIR /var/www/site