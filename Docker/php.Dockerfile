FROM php:8.4-fpm-alpine

WORKDIR /var/www/library-app

RUN docker-php-ext-install pdo pdo_mysql