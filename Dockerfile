FROM php:7

# Installing packages
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y openssl zip unzip git libonig-dev nodejs npm

# Installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installing php extension(s)
RUN docker-php-ext-install pdo_mysql

# Cleaning
RUN apt-get clean && apt-get autoremove -y

WORKDIR /app
COPY . /app

# Installing composer dependencies
RUN composer install --optimize-autoloader --no-dev

# Upgrading Node
RUN npm cache clean -f
RUN npm install -g n
RUN n stable

RUN npm run prod

# Updating PHP config
RUN echo "upload_max_filesize = 20M\n" \
         "post_max_size = 20M\n" \
         "max_execution_time = 60\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

EXPOSE 9191
CMD php artisan serve --host=0.0.0.0 --port=9191
