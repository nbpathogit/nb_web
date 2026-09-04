FROM php:7.4-apache

# extensions needed by this project (mysqli, pdo_mysql, gd, mbstring, zip for mpdf/tcpdf)
RUN apt-get update && apt-get install -y --no-install-recommends \
      libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libonig-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql mbstring gd zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Apache: allow .htaccess, suppress ServerName warning
RUN printf '<Directory /var/www/html>\n\tOptions Indexes FollowSymLinks\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' > /etc/apache2/conf-available/nb-web.conf \
    && a2enconf nb-web \
    && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

COPY php.ini /usr/local/etc/php/conf.d/nb-web.ini

WORKDIR /var/www/html
