FROM php:7.3-apache
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
RUN a2enmod rewrite
COPY . /var/www/html/nooreyehealthcenter