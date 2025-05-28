#!/bin/sh

log() { echo "$@" >&2; }

log "Waiting for MySQL to be ready..."
until mysqladmin --silent ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD"; do
    log "Waiting for MySQL..."
    sleep 2
done

php artisan config:cache
php artisan route:cache

log "Running migrations..."
php artisan migrate --force

log "Seeding database..."
php artisan db:seed --force

exec php-fpm