FROM php:7-fpm-alpine
COPY . /var/www/project/
WORKDIR /var/www/project/
