# 1. Use the lightweight Alpine image (~50MB vs ~400MB)
FROM php:7.4-alpine

# --- FLAG SETUP (Unchanged) ---
RUN echo "OWASP{w47ch_0u7_f0r_m1m3_7yp35}" > /flag.txt
RUN chown root:root /flag.txt && chmod 444 /flag.txt

# --- WEB ROOT SETUP (Unchanged) ---
WORKDIR /var/www/html
COPY src/ .

# --- PERMISSIONS (Unchanged) ---
# We still set permissions so users can't overwrite files
RUN chown -R root:root /var/www/html \
    && chmod -R 555 /var/www/html \
    && mkdir -p assets/themes \
    && chown www-data:www-data assets/themes \
    && chmod 755 assets/themes

# --- SECURITY & SERVER (The Part That Changed) ---
# We removed the 'a2enconf' and '/etc/apache2' lines because they don't exist here.
# Instead, we use the PHP built-in server.
# It is SECURE by default (it does not list directories).

EXPOSE 80

# This command starts the server on Port 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]

