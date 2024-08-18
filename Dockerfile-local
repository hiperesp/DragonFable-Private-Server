FROM php:8.3-apache AS base

RUN a2enmod rewrite headers

# COPY src/cdn/ /var/www/html/cdn/
COPY src/server-emulator/ /var/www/html/server-emulator/
COPY src/web/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

EXPOSE 80
