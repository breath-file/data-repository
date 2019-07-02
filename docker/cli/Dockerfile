FROM composer AS builder
COPY ./public /app/public
COPY ./src /app/src
COPY composer.* /app/

RUN cd /app && composer install --no-dev


FROM php:7-cli-alpine
LABEL maintainer="Julien MERCIER-ROJAS <julien@jeckel-lab.fr>"

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev
RUN docker-php-ext-install pdo_pgsql

COPY docker/cli/php.ini /etc/php7/conf.d/zzz_custom.ini

COPY --from=builder app/ /var/www/
WORKDIR /var/www
ENTRYPOINT [ "php", "./public/index.php" ]
