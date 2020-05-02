# envy
Official website of the new envy client.

### deploying
```text
composer install --optimize-autoloader --no-dev
php artisan key:gen
php artisan storage:link
php artisan migrate:fresh
```
