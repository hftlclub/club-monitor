FROM php:5.6-apache
RUN a2enmod rewrite && docker-php-ext-install mysql
COPY . /var/www/html/
EXPOSE 80
