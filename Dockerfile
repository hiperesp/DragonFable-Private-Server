FROM php:8.3-apache AS base

RUN a2enmod rewrite headers speling

COPY src/cdn/ /var/www/html/cdn/
COPY src/server-emulator/ /var/www/html/server-emulator/
COPY src/web/ /var/www/html/

EXPOSE 80
