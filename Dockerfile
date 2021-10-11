FROM alpine:edge as base

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
    php8-openssl \
    php8-pcntl \
    php8-pdo_mysql \
    php8-phar \
    php8-posix \
    php8-redis \
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

FROM base as composer-build

# install composer
RUN apk --no-cache add composer

# create the app directory
WORKDIR /app

# copy project folder
COPY . ./

# install composer dependencies
RUN composer install --optimize-autoloader --no-dev

FROM mhart/alpine-node:16 as npm-build

# create the app directory
WORKDIR /app

# copy over required files for building css & js
COPY package.json package-lock.json tailwind.config.js webpack.mix.js ./
COPY resources/ ./resources
COPY config/ ./config

# build production css & js
RUN npm install && npm run prod

FROM base as production

# change to working dir
WORKDIR /app

# copy over project files from composer-build
COPY --from=composer-build /app ./

# copy over built assets from npm-build
COPY --from=npm-build /app/public ./public

# expose laravel octane port
EXPOSE 8000

ENTRYPOINT ["/bin/ash", ".docker/entrypoint.sh"]
