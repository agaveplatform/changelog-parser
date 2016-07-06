FROM deardooley/php:5.6-composer

ENV DOCUMENT_ROOT /var/www/html/api

COPY . /var/www/html

RUN composer install && \
    mkdir -p /var/www/data/cache &&
    chown -R apache:apache /var/www/html
