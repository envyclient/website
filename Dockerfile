FROM alpine:edge as base

# install required packages & php extensions
RUN apk --no-cache add \
    curl \
    php8 \
    php8-ctype \
    php8-curl \
    php8-dom \
    php8-fpm \
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
    php8-redis \
    php8-session \
    php8-tokenizer \
    php8-xml \
    php8-xmlreader \
    php8-zlib \
    nginx

# set the timezone
RUN apk add --no-cache tzdata \
    && cp /usr/share/zoneinfo/UTC /etc/localtime \
    && echo "UTC" > /etc/timezone \
    && apk del tzdata

# cleanup
RUN rm -rf /tmp/* /var/cache/apk/*

# create symlink
RUN ln -s /usr/bin/php8 /usr/bin/php

# configure php
COPY .docker/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY .docker/php.ini /etc/php8/conf.d/99_envy.ini

# configure nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# setup document root
RUN mkdir -p /var/www/html

# fix permissions
RUN chown -R nginx:nginx /var/www/html \
    && chown -R nginx:nginx /run \
    && chown -R nginx:nginx /var/lib/nginx \
    && chown -R nginx:nginx /var/log/nginx

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

# swtich to nginx user
USER nginx

# create the app directory
WORKDIR /var/www/html

# copy over project files from composer-build
COPY --from=composer-build --chown=nginx /app ./

# copy over built assets from npm-build
COPY --from=npm-build --chown=nginx /app/public ./public

# fix permissions
RUN chmod 777 -R bootstrap storage

# expose nginx port
EXPOSE 8080

ENTRYPOINT ["/bin/ash", ".docker/entrypoint.sh"]
