FROM php:7.4.5-apache-buster
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli

# Unsure about the usefulness of the following three
RUN mkdir /var/www/html/uploads
RUN chown -R www-data:www-data /var/www/html/uploads
RUN chmod 0764 /var/www/html/uploads
EXPOSE 80
