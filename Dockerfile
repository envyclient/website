FROM alpine:edge as base

# install curl, php & php extensions
RUN apk --no-cache add \
    curl \
    php82 \
    php82-curl \
    php82-dom \
    php82-fileinfo \
    php82-json \
    php82-mbstring \
    php82-openssl \
    php82-pdo_sqlite \
    php82-phar \
    php82-session \
    php82-simplexml \
    php82-tokenizer \
    php82-zip

# create symlink
RUN ln -s /usr/bin/php82 /usr/bin/php

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

FROM golang:alpine as supervisord-build

# install
RUN apk --no-cache add gcc git rust

# create the app directory
WORKDIR /app

# build
RUN git clone https://github.com/ochinchina/supervisord.git .
RUN go generate
RUN GOOS=linux go build -tags release -a -ldflags "-linkmode external -extldflags -static" -o /usr/local/bin/supervisord

FROM base as production

# create app dir
WORKDIR /app

# copy over project files from composer-build
COPY --from=composer-build /app ./

# copy over built assets from npm-build
COPY --from=npm-build /app/public ./public

# copy over built supervisord
COPY --from=supervisord-build /usr/local/bin/supervisord /usr/bin/supervisord

# php server port
EXPOSE 8000

# volumes
VOLUME ["/app/storage"]

ENTRYPOINT ["/bin/sh", ".docker/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
