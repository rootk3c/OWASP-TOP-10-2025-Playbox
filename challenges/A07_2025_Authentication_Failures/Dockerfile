FROM php:7.4-apache
COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 555 /var/www/html
EXPOSE 80