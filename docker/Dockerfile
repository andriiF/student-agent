FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

# Bind mounts use the host owner's UID/GID (often 1000). Default www-data (33) cannot
# write storage/, so Laravel's tempnam(dirname($path), ...) falls back to /tmp and PHP
# emits: "tempnam(): file created in the system's temporary directory".
RUN groupmod -g "${GID}" www-data \
    && usermod -u "${UID}" -g www-data www-data

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl unzip vim git \
    libpq-dev libonig-dev libicu-dev libxml2-dev libzip-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql pdo_pgsql pgsql intl zip bcmath soap gd \
    && pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html

COPY composer.json composer.lock ./

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

