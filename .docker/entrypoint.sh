#!/bin/ash

# change to project directory
cd /var/www/html || exit

# check for DB up before starting the panel
echo "checking database status"
until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
    echo "waiting for database connection..."
    sleep 1
done

# clear cache
echo -e "clearing cache"
php artisan optimize:clear

# migrating database
echo -e "migrating database"
php artisan migrate --force

# symlink storage
echo -e "symlink storage"
php artisan storage:link

echo -e "starting supervisord"
exec "$@"
