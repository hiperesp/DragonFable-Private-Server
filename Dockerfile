FROM php:8.2-apache AS base

RUN a2enmod rewrite headers

RUN docker-php-ext-install pdo_mysql

COPY src/cdn/ /var/www/html/cdn/
COPY src/server-emulator/ /var/www/html/server-emulator/
COPY src/web/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80
