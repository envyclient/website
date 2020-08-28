FROM php:7.4.9-cli-alpine

# install npm & composer
RUN apk --no-cache add curl npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo_mysql

# copy the project files
WORKDIR /app
COPY . /app

# build dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run prod

RUN echo "upload_max_filesize=50M\n" \
         "post_max_size=50M\n" \
         "max_execution_time=60\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# run php server
EXPOSE 9191
CMD php artisan serve --host=0.0.0.0 --port=9191
