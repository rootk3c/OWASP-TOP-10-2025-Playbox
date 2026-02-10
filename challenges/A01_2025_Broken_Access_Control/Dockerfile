FROM php:7.4-apache

# Flag
RUN echo "OWASP{w47ch_0u7_f0r_m1m3_7yp35}" > /flag.txt
RUN chown root:root /flag.txt && chmod 444 /flag.txt

# Web Root
WORKDIR /var/www/html
COPY src/ .

# Permissions
RUN chown -R root:root /var/www/html \
    && chmod -R 555 /var/www/html \
    && mkdir -p assets/themes \
    && chown www-data:www-data assets/themes \
    && chmod 755 assets/themes

# Security
RUN echo "<Directory /var/www/html/>" > /etc/apache2/conf-available/no-listing.conf \
    && echo "    Options -Indexes" >> /etc/apache2/conf-available/no-listing.conf \
    && echo "</Directory>" >> /etc/apache2/conf-available/no-listing.conf \
    && a2enconf no-listing

RUN echo "ServerTokens Prod" >> /etc/apache2/conf-available/security.conf \
    && echo "ServerSignature Off" >> /etc/apache2/conf-available/security.conf
