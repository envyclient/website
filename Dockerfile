FROM php:7

RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev nodejs npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo_mysql

WORKDIR /app
COPY . /app

RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run prod

RUN echo "upload_max_filesize = 50M\n" \
         "post_max_size = 50M\n" \
         "max_execution_time = 60\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

CMD php artisan serve --host=0.0.0.0 --port=9191
EXPOSE 9191
