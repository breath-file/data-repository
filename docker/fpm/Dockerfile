FROM php:7-fpm-alpine
LABEL maintainer="Julien MERCIER-ROJAS <julien@jeckel-lab.fr>"

RUN docker-php-ext-install pdo_mysql
# Install xdebug mysql and zip extensions
#RUN apk add --no-cache --update --virtual buildDeps autoconf gcc musl-dev make zlib-dev \
#     && pecl install xdebug \
#     && docker-php-ext-install pdo_mysql zip \
#     && docker-php-ext-enable xdebug \
#     && apk del buildDeps autoconf gcc musl-dev make zlib-dev
