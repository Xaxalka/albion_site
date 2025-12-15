# syntax=docker/dockerfile:1.4

FROM php:8.2-fpm-bullseye AS base

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN groupmod -o -g ${GID} www-data && usermod -o -u ${UID} -g www-data www-data

FROM base AS vendor

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

FROM node:20-bullseye AS frontend
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
RUN npm run build

FROM base AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=vendor /var/www/html/composer.lock ./composer.lock
COPY --from=vendor /var/www/html/composer.json ./composer.json
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

EXPOSE 9000

USER www-data

CMD ["php-fpm"]
