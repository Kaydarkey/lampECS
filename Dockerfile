FROM php:8.2-apache

RUN docker-php-ext-install mysqli

# Copy files and set correct permissions
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
