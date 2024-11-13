#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- supervisord "$@"
fi

if [ "$APP_ENV" = "local" ]; then
    composer install --no-scripts --no-interaction
fi

mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

php artisan config:cache
php artisan route:cache
php artisan view:cache

if ls -A database/migrations/*.php >/dev/null 2>&1; then
    php artisan migrate --no-interaction --force
fi

chown -R www-data:www-data storage/logs
chown -R www-data:www-data storage/framework/cache
chown -R www-data:www-data storage/framework/sessions
chown -R www-data:www-data storage/framework/views

exec "$@"
