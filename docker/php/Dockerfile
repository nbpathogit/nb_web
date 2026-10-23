FROM php:8.5-apache-bookworm

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

# ImageMagick 7 (/usr/local/bin/magick) + Ghostscript สำหรับแปลง PDF -> JPG จาก PHP
# ใช้กับ patient_pdf.php / pdf_attach_upload.php เช่น
#   /usr/local/bin/magick -density 300 ./cus/xxx.pdf -density 300 ./cus/xxx.jpg
# apt ของ base (bookworm) มีแต่ ImageMagick 6 (convert) ไม่มี magick ของ IM7 -> build จาก source
# ghostscript = delegate อ่าน PDF (ขาดไม่ได้), zip = command2 ใน patient_pdf.php
# ห้ามใช้ IM 7.1.1-3x (crash ตอนอ่าน PDF ดู docs/imagemagick-install.md) -> ต้องเป็น 7.1.2-xx เท่านั้น
# libpng/libjpeg/freetype dev ติดตั้งไว้แล้วใน layer แรก -> IM7 build coder JPEG/PNG ให้อัตโนมัติ
# (ช่วง PHP 7.4 เคยใช้ base bullseye EOL ต้องเขียน sources.list ทับ — bookworm ปกติ apt ใช้ได้เลย
#  ถ้าวันหนึ่ง bookworm ถูกย้ายไป archive.debian.org จะสลับ mirror เองอัตโนมัติด้านล่าง)
RUN set -eux; \
    if ! apt-get update; then \
        echo '!! deb.debian.org failed - switch to archive.debian.org'; \
        sed -i 's|deb.debian.org|archive.debian.org|g' /etc/apt/sources.list /etc/apt/sources.list.d/*.sources 2>/dev/null || true; \
        apt-get update -o Acquire::Check-Valid-Until=false -o Acquire::Retries=5; \
    fi; \
    apt-get install -y --no-install-recommends ghostscript zip curl ca-certificates; \
    curl -fsSL -o /tmp/im7.tar.gz \
      https://github.com/ImageMagick/ImageMagick/archive/refs/tags/7.1.2-34.tar.gz; \
    tar -xzf /tmp/im7.tar.gz -C /tmp; \
    cd /tmp/ImageMagick-7.1.2-34; \
    ./configure --prefix=/usr/local --with-magick-plus-plus=no --disable-docs --disable-hdri; \
    make -j"$(nproc)"; \
    make install; \
    ldconfig; \
    magick -version; \
    gs --version; \
    gs -q -sDEVICE=pdfwrite -o /tmp/im7test.pdf -c "0 0 moveto 100 100 lineto 100 0 lineto closepath fill 72 700 moveto /Helvetica findfont 24 scalefont setfont (NB Patho smoke test) show showpage"; \
    magick -density 96 /tmp/im7test.pdf /tmp/im7test.png; \
    test -s /tmp/im7test.png; \
    magick -density 96 /tmp/im7test.pdf /tmp/im7test.jpg; \
    test -s /tmp/im7test.jpg; \
    cd /tmp; \
    rm -rf /tmp/im7.tar.gz /tmp/ImageMagick-7.1.2-34 /tmp/im7test.pdf /tmp/im7test.png /tmp/im7test.jpg; \
    rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/nb-web.ini

WORKDIR /var/www/html
