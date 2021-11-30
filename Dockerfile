FROM alpine:edge

# install required packages & php extensions
RUN apk --no-cache add \
    curl \
    php8 \
    php8-ctype \
    php8-curl \
    php8-dom \
    php8-fileinfo \
    php8-gd \
    php8-intl \
    php8-json \
    php8-mbstring \
    php8-opcache \
    php8-openssl \
    php8-pcntl \
    php8-pdo_mysql \
    php8-phar \
    php8-posix \
    php8-session \
    php8-tokenizer \
    php8-xml \
    php8-xmlreader \
    php8-zlib

# install swoole
RUN apk --no-cache add \
    -X \
    https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    php8-pecl-swoole

# cleanup
RUN rm -rf /tmp/* /var/cache/apk/*

# create symlink
RUN ln -s /usr/bin/php8 /usr/bin/php

# configure php
COPY .docker/php.ini /etc/php8/conf.d/99_envy.ini

# schedule cron job
RUN echo "* * * * * cd /app && php artisan schedule:run" | crontab -

# install composer
RUN apk --no-cache add composer

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
