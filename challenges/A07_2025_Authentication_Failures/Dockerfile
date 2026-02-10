# 1. Use the tiny Alpine image
FROM php:7.4-alpine

# 2. Set the folder
WORKDIR /var/www/html

# 3. Copy your PHP files
COPY src/ .

# 4. Permissions (Kept exactly as you had them)
# Alpine includes the 'www-data' user, so this command still works perfectly.
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 555 /var/www/html

# 5. Open Port 80
EXPOSE 80

# 6. Start the lightweight PHP Built-in Server
# (Replaces the heavy Apache service)
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]