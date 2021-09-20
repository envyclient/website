#!/bin/ash

# change to project directory
cd /var/www/html || exit

# check for DB up before starting the panel
echo "checking database status"
until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
    echo "waiting for database connection..."
    sleep 1
done

function startServer() {
    # clear application cache
    echo -e "clearing cache"
    php artisan optimize:clear

    # migrating database
    echo -e "migrating database"
    php artisan migrate --force

    # symlink storage
    echo -e "symlink storage"
    php artisan storage:link

    echo -e "starting php-fpm"
    php-fpm8 --nodaemonize &
    php_service_pid=$!

    echo -e "starting nginx"
    nginx -g 'daemon off;' &
    nginx_service_pid=$!

    # Monitor Child Processes
    while (true); do
        if ! kill -0 "$php_service_pid" 2>/dev/null; then
            echo "[php] service is no longer running! exiting..."
            sleep 5
            wait "$php_service_pid"
            exit 1
        fi
        if ! kill -0 "$nginx_service_pid" 2>/dev/null; then
            echo "[nginx] service is no longer running! exiting..."
            sleep 5
            wait "$nginx_service_pid"
            exit 2
        fi
        sleep 1
    done
}

function startHorizon() {
    exec php /var/www/html/artisan horizon
}

case "$1" in
start:server)
    startServer
    ;;
start:horizon)
    startHorizon
    ;;
*)
    echo -e "no service specified"
    ;;
esac
