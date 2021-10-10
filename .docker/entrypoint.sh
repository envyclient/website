#!/bin/ash

# change to project directory
cd /app || exit

# checking for successful database connection before starting
echo "checking database status"
until nc -z -v -w30 "$DB_HOST" "$DB_PORT"; do
    echo "waiting for database connection..."
    sleep 1
done

function startServer() {
    # migrating database
    echo -e "migrating database"
    php artisan migrate --force

    # symlink storage
    echo -e "symlink storage"
    php artisan storage:link

    # laravel octane
    echo -e "starting laravel octane"
    php artisan octane:start --host=0.0.0.0 --max-requests=250 &
    octane_service_pid=$!

    # monitor process
    while (true); do
        if ! kill -0 "$octane_service_pid" 2>/dev/null; then
            echo "[octane] service is no longer running! exiting..."
            sleep 5
            wait "$octane_service_pid"
            exit 1
        fi
        sleep 1
    done
}

function startHorizon() {
    # laravel horizon
    echo -e "starting laravel horizon"
    php artisan horizon &
    horizon_service_pid=$!

    # monitor process
    while (true); do
        if ! kill -0 "$horizon_service_pid" 2>/dev/null; then
            echo "[horizon] service is no longer running! exiting..."
            sleep 5
            wait "$horizon_service_pid"
            exit 1
        fi
        sleep 1
    done
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
