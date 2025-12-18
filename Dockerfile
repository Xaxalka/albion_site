# syntax=docker/dockerfile:1.4

FROM php:8.2-apache-bullseye AS base

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN groupmod -o -g ${GID} www-data && usermod -o -u ${UID} -g www-data www-data

FROM base AS vendor

COPY albione-site/composer.json albione-site/composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress --no-scripts

FROM node:20-bullseye AS frontend
WORKDIR /app
COPY albione-site/package.json albione-site/package-lock.json albione-site/vite.config.js ./
RUN npm ci --no-audit --no-fund
COPY albione-site/public ./public
COPY albione-site/resources ./resources
RUN npm run build

FROM base AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

WORKDIR /var/www/html

COPY --chown=www-data:www-data albione-site/ ./
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=vendor /var/www/html/composer.lock ./composer.lock
COPY --from=vendor /var/www/html/composer.json ./composer.json
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

RUN php artisan package:discover --ansi

RUN a2enmod rewrite headers

COPY albione-site/docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY albione-site/docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
