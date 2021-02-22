FROM composer:latest

RUN addgroup -g 1000 fileapi && adduser -G fileapi -g fileapi -s /bin/sh -D fileapi

WORKDIR /var/www/html
