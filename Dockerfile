FROM php:5.6-apache
RUN apt-get update && apt-get install -y libcurl4-openssl-dev && a2enmod rewrite && docker-php-ext-install mysql curl
COPY . /var/www/html/
EXPOSE 80
