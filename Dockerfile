FROM alpine:3.16 as base

# install curl & php & php extensions
RUN apk --no-cache add \
    curl \
    php81 \
    php81-curl \
    php81-dom \
    php81-fileinfo \
    php81-json \
    php81-mbstring \
    php81-openssl \
    php81-pdo_sqlite \
    php81-phar \
    php81-session \
    php81-simplexml \
    php81-tokenizer \
    php81-zip

# install supervisor (go-lang version)
COPY --from=ochinchina/supervisord /usr/local/bin/supervisord /usr/local/bin

# create symlink
RUN ln -s /usr/bin/php81 /usr/bin/php

# configure php & supervisord
COPY .docker/php.ini /etc/php81/conf.d/99_envy.ini
COPY .docker/supervisord.conf /etc/supervisord.conf

# schedule cron job
RUN echo "* * * * * php /app/artisan schedule:run" | crontab -

FROM base as composer-build

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# create the app directory
WORKDIR /app

# copy project folder
COPY . ./

# install composer dependencies
RUN composer install --optimize-autoloader --no-dev

FROM node:alpine as npm-build

# create the app directory
WORKDIR /app

# copy project folder
COPY . ./

# build production css & js
RUN npm install && npm run prod

FROM base as production

# create app dir
WORKDIR /app

# copy over project files from composer-build
COPY --from=composer-build /app ./

# copy over built assets from npm-build
COPY --from=npm-build /app/public ./public

# expose laravel octane port
EXPOSE 8000

# volumes
VOLUME ["/app/storage"]

ENTRYPOINT ["/bin/sh", ".docker/entrypoint.sh"]
CMD ["/usr/local/bin/supervisord", "-c", "/etc/supervisord.conf"]
