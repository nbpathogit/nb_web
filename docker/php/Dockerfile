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

# ImageMagick 7 (/usr/local/bin/magick) + Ghostscript สำหรับแปลง PDF -> JPG จาก PHP
# ใช้กับ patient_pdf.php / pdf_attach_upload.php เช่น
#   /usr/local/bin/magick -density 300 ./cus/xxx.pdf -density 300 ./cus/xxx.jpg
# apt ของ base (buster/bullseye) มีแต่ ImageMagick 6 (คำสั่ง convert) ไม่มี magick ของ IM7
# -> ต้อง build IM7 จาก source ; ghostscript = delegate อ่าน PDF (ขาดไม่ได้)
# zip = command2 ใน patient_pdf.php (zip รายงาน jpg+pdf+txt)
# base image EOL แล้ว ถ้า apt ล้ม เดินตามขั้น: (1) mirror ปกติ (2) ตัด repo -updates/-security
# ที่ปลดระวางแล้วให้เหลือ main บน deb.debian.org (3) archive.debian.org

# libpng/libjpeg/freetype dev ติดตั้งไว้แล้วใน layer แรก -> IM7 build coder JPEG/PNG ให้อัตโนมัติ

RUN set -eux; \

    if ! apt-get update -o Acquire::Retries=3; then \

        echo '!! apt-get update failed - base suite EOL, retry with main repo only'; \
        cat /etc/os-release; \
        sed -i '/-updates/d; /security.debian.org/d' /etc/apt/sources.list; \

        if ! apt-get update -o Acquire::Check-Valid-Until=false; then \
            echo '!! still failing - switch to archive.debian.org'; \
            sed -i 's|deb.debian.org|archive.debian.org|g' /etc/apt/sources.list; \
            apt-get update -o Acquire::Check-Valid-Until=false -o Acquire::Retries=5; \

        fi; \
    fi; \
    apt-get install -y --no-install-recommends ghostscript zip curl ca-certificates; \
    curl -fsSL -o /tmp/im7.tar.gz \
      https://github.com/ImageMagick/ImageMagick/archive/refs/tags/7.1.1-38.tar.gz; \
    tar -xzf /tmp/im7.tar.gz -C /tmp; \
    cd /tmp/ImageMagick-7.1.1-38; \
    ./configure --prefix=/usr/local --with-magick-plus-plus=no --disable-docs; \
    make -j"$(nproc)"; \
    make install; \
    ldconfig; \
    magick -version; \
    gs --version; \
    gs -q -sDEVICE=pdfwrite -o /tmp/im7test.pdf -c "0 0 moveto 100 100 lineto 100 0 lineto closepath fill showpage"; \
    magick -density 96 /tmp/im7test.pdf /tmp/im7test.jpg; \
    test -s /tmp/im7test.jpg; \
    cd /tmp; \
    rm -rf /tmp/im7.tar.gz /tmp/ImageMagick-7.1.1-38 /tmp/im7test.pdf /tmp/im7test.jpg; \
    rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/nb-web.ini

WORKDIR /var/www/html
