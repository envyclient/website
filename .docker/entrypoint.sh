#!/bin/sh

function color() {
    echo -e "\033[33m$1\033[0m"
}

echo "checking database status"
until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
    color "waiting for database connection..."
    sleep 1
done

color "migrating database"
php artisan migrate --force

color "symlink storage"
php artisan storage:link

color "starting supervisord"
exec "$@"
