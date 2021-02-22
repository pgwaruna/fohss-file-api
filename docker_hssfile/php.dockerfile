FROM php:7.4-fpm-alpine

ADD ./php/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup -g 1000 fileapi && adduser -G fileapi -g fileapi -s /bin/sh -D fileapi

RUN mkdir -p /var/www/html

RUN chown fileapi:fileapi /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
