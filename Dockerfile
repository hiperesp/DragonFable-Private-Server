FROM php:8.3-apache AS base

RUN a2enmod rewrite headers speling

# Enable case insensitive mode
RUN echo "CheckSpelling On" >> /etc/apache2/conf-available/speling.conf

COPY src/cdn/ /var/www/html/cdn/
COPY src/server-emulator/ /var/www/html/server-emulator/
COPY src/web/ /var/www/html/

EXPOSE 80
