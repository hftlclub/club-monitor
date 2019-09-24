FROM php:5.6-apache
MAINTAINER Stecker-HfTL-Club
RUN a2enmod rewrite
RUN docker-php-ext-install mysql
RUN apt-get update && apt-get install -y iputils-ping
COPY ./ /var/www/html/.
RUN rm /var/www/html/Dockerfile*
RUN rm /var/www/html/docker-compose*
RUN ls -la /var/www/html/
EXPOSE 80

