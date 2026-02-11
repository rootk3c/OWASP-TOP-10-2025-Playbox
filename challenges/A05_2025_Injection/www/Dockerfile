# Use the smallest possible PHP image
FROM php:8.2-alpine

# Install SQLite extensions
RUN apk add --no-cache sqlite-dev && \
    docker-php-ext-install pdo pdo_sqlite

# HARDENING: Disable dangerous PHP functions
RUN echo "disable_functions = exec,passthru,shell_exec,proc_open,popen,curl_exec,curl_multi_exec,show_source" > /usr/local/etc/php/conf.d/disable.ini

# FLAG: Setup
RUN echo "CTF{M0VE_1T_L1K3_CL0P_GANG}" > /flag.txt && \
    chmod 444 /flag.txt

# PERMISSIONS
COPY . /var/www/html/
WORKDIR /var/www/html

RUN chown -R root:root /var/www/html && \
    chmod 755 /var/www/html && \
    touch securetransfer.db && \
    chown www-data:www-data securetransfer.db .

# Run the built-in PHP web server (Ultra-lightweight)
# This replaces the need for Apache entirely
USER www-data
EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]