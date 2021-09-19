# stage 0
FROM mhart/alpine-node:16 as npm-build

# create the app directory
WORKDIR /app

# copy over project files
COPY . /app

# build production js & css
RUN npm install && npm run prod

# stage 1
FROM alpine:edge as production

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
    supervisor \
    composer \
    tzdata \
    nginx

# set the timezone
RUN cp /usr/share/zoneinfo/UTC /etc/localtime \
    && echo "UTC" > /etc/timezone \
    && apk del tzdata

# cleanup
RUN rm -rf /tmp/* /var/cache/apk/*

# create symlink
RUN ln -s /usr/bin/php8 /usr/bin/php

# configure php
COPY .docker/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY .docker/php.ini /etc/php8/conf.d/99_envy.ini

# configure supervisord
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# configure nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# setup document root
RUN mkdir -p /var/www/html

# fix permissions
RUN chown -R nginx:nginx /var/www/html \
    && chown -R nginx:nginx /run \
    && chown -R nginx:nginx /var/lib/nginx \
    && chown -R nginx:nginx /var/log/nginx

USER nginx

# create the app directory
WORKDIR /var/www/html

# copy over project files
COPY --chown=nginx . /var/www/html

# copy over built assets from npm-build
COPY --from=npm-build --chown=nginx /app/public ./public

# install composer dependencies
RUN composer install --optimize-autoloader --no-dev

# export nginx port
EXPOSE 8080

# entry point
ENTRYPOINT ["/bin/ash", ".docker/entrypoint.sh"]

# supervioser start php-fpm & nginx & queue-worker
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# health check
HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD curl -sf http://127.0.0.1:8080/fpm-ping
