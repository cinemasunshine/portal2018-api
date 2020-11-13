# https://hub.docker.com/_/php/
FROM php:7.3-fpm-alpine

# apkをupdate
# install
#    - git        composerのため
#    - zip、unzip composerのエラー解決
RUN apk update \
    && apk add -U --no-cache \
        git \
        zip \
        unzip

RUN docker-php-ext-install \
        pdo_mysql

# https://hub.docker.com/_/composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php
