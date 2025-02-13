FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-enable pgsql pdo_pgsql
RUN apache2ctl restart
RUN a2enmod rewrite