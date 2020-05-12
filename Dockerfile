FROM php:7.3

RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev nodejs npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo_mysql

WORKDIR /app
COPY . /app

RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run prod

CMD php artisan config:cache
CMD php artisan route:cache
CMD php artisan view:cache
CMD php artisan storage:link

CMD php artisan serve --host=0.0.0.0 --port=9191
EXPOSE 9191
