FROM php:8.4.1-apache AS base

RUN a2enmod rewrite headers

RUN docker-php-ext-install pdo_mysql

USER www-data

COPY src/cdn/ /var/www/html/cdn/
COPY src/server-emulator/ /var/www/html/server-emulator/
COPY src/web/ /var/www/html/web/

COPY src/web/.htaccess.disabled /var/www/html/web/.htaccess
RUN ln -s /var/www/html/web/assets/ /var/www/html/assets
RUN find /var/www/html/web/ -name "*.html" -exec ln -s {} /var/www/html/ \;

EXPOSE 80
