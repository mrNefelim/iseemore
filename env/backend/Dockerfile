FROM php:7.4-fpm

RUN apt-get update --fix-missing && \
    apt-get install -y git zip unzip\
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Additional ini settings
COPY ./php-additional.ini /usr/local/etc/php/conf.d/php-additional.ini

# В соответствие с dev/боем
RUN sed -i 's|DEFAULT@SECLEVEL=2|DEFAULT@SECLEVEL=1|g' /etc/ssl/openssl.cnf

WORKDIR /var/www/html/iseemore

