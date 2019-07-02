FROM composer AS builder
COPY ./public /app/public
COPY ./src /app/src
COPY composer.* /app/

RUN cd /app && composer install --no-dev

FROM alpine:latest

ENV GATEWAY_API_KEY = ''

# Install packages
RUN apk --no-cache add \
        php7 \
        php7-fpm \
        php7-json \
#        php7-openssl \
#        php7-curl \
#        php7-zlib \
#        php7-xml \
#        php7-phar \
#        php7-intl \
#        php7-dom \
#        php7-xmlreader \
#        php7-ctype \
#        php7-mbstring \
#        php7-gd \
        php7-pgsql \
        php7-pdo_pgsql \
        php7-session \
        nginx \
        supervisor \
        curl


# Configure nginx
COPY docker/web/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker/web/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY docker/web/php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY docker/web/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/tmp/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Setup document root
RUN mkdir -p /var/www/html

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/
COPY --chown=nobody --from=builder app/ /var/www/

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
