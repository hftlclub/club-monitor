FROM php:5.6-apache
COPY . /var/www/html/
RUN a2enmod rewrite && docker-php-ext-install mysql && rm /var/www/html/Dockerfile
EXPOSE 80