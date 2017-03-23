FROM amqamq/phalcon:nginx

LABEL maintainer "https://github.com/amq/"

COPY . /app

RUN chown -R www-data:www-data /app

COPY docker/php-prod.ini /usr/local/etc/php/php.ini
