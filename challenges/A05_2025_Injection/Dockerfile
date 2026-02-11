FROM php:8.2-alpine

# Install the extension installer (Pre-compiled binaries = No long wait)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Make it executable and install pdo_sqlite
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_sqlite

# Setup Flag
RUN echo "CTF{PRECOMPILED_STABILITY}" > /flag.txt && chmod 444 /flag.txt

# Create App Directory
WORKDIR /var/www/html

# COPY your 'www' folder content
COPY www/ /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 777 /var/www/html

USER www-data

CMD ["php", "-S", "0.0.0.0:80"]