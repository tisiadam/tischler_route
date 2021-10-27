FROM php:8.0-fpm-alpine

WORKDIR /usr/src/app

COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD ["mkdir" , "/usr/src/app"]

RUN apk update && apk add --no-cache \
    libpng-dev \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    curl \
    oniguruma-dev \
    libxml2-dev \
    fish

RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-install mbstring


CMD ["php-fpm"]
