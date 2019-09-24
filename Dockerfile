FROM php:7.2-apache
MAINTAINER Stecker-HfTL-Club
RUN a2enmod rewrite
COPY * /var/www/html/
RUN rm /var/www/html/Dockerfile
RUN ls -la /var/www/html/
EXPOSE 80

