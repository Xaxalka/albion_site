#!/bin/sh
set -eu

PORT="${PORT:-80}"

if [ -f /etc/apache2/ports.conf ]; then
  sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf || true
fi

if [ -f /etc/apache2/sites-available/000-default.conf ]; then
  sed -i "s/<VirtualHost \\*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf || true
fi

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

php /var/www/html/artisan migrate --force

exec apache2-foreground
