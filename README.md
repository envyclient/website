[![buddy pipeline](https://app.buddy.works/haaaqs/revived-website/pipelines/pipeline/254472/badge.svg?token=b18303429e26f76a17ce9a17b3d4056c1b1b99fa46097bdb41dd0ae275f9799a "buddy pipeline")](https://app.buddy.works/haaaqs/revived-website/pipelines/pipeline/254472)

![Publish Docker](https://github.com/envyclient/revived-website/workflows/Publish%20Docker/badge.svg?branch=master)

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
