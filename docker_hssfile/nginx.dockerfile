FROM nginx:stable-alpine

ADD ./nginx/nginx.conf /etc/nginx/nginx.conf
ADD ./nginx/default.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p /var/www/html

RUN addgroup -g 1000 fileapi && adduser -G fileapi -g fileapi -s /bin/sh -D fileapi

RUN chown fileapi:fileapi /var/www/html
