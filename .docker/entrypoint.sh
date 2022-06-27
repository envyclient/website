#!/bin/sh

DATABASE=/database/database.sqlite
function color() {
    echo -e "\033[33m$1\033[0m"
}

color "checking if database exists"
if [[ -f "$DATABASE" ]]; then
    color "database exists"
else
    color "creating database"
    touch "$DATABASE"
fi

color "migrating database"
php artisan migrate --force

color "symlink storage"
php artisan storage:link

color "starting supervisord"
exec "$@"
