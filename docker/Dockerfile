FROM php:8.0.0-cli-buster

RUN apt update && apt install unzip

RUN docker-php-ext-install sockets

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
