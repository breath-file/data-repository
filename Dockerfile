FROM php:7-cli-alpine
ENV OWM_APP_ID 'API_KEY'
COPY . /var/www/project/
WORKDIR /var/www/project/
CMD php -S web:80 -t public/
