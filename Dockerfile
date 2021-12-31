FROM alpine:edge

# install required packages & php extensions
RUN apk --no-cache add \
    -X \
    https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    curl \
    php81 \
    php81-ctype \
    php81-curl \
    php81-dom \
    php81-fileinfo \
    php81-gd \
    php81-intl \
    php81-json \
    php81-mbstring \
    php81-opcache \
    php81-openssl \
    php81-pcntl \
    php81-pdo_mysql \
    php81-phar \
    php81-posix \
    php81-session \
    php81-tokenizer \
    php81-xml \
    php81-xmlreader \
    php81-zip \
    php81-zlib \
    php81-pecl-swoole

# create symlink
RUN ln -s /usr/bin/php81 /usr/bin/php

# configure php
COPY .docker/php.ini /etc/php81/conf.d/99_envy.ini

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# schedule cron job
RUN echo "* * * * * cd /app && php artisan schedule:run" | crontab -

# change to app dir
WORKDIR /app

# copy project folder
COPY . ./

# install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# expose laravel octane port
EXPOSE 8000

# entrypoint
ENTRYPOINT ["/bin/ash", ".docker/entrypoint.sh"]
