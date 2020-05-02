# envy
The official website of the new Envy Client.

### deploying
```text
composer install --optimize-autoloader --no-dev
php artisan key:gen
php artisan storage:link
php artisan migrate:fresh
```

### docker
```text
git clone git@github.com:envyclient/revived-website.git app
cd app/
docker build -t haaaqs/envyclient .
docker run -p 9191:9191 haaaqs/envyclient
```
