FROM alpine:3.15

# install required packages & php extensions
RUN apk --no-cache add curl supervisor
RUN apk --no-cache add \
    -X \
    https://dl-cdn.alpinelinux.org/alpine/edge/testing \
    php81 \
    php81-curl \
    php81-dom \
    php81-fileinfo \
    php81-json \
    php81-mbstring \
    php81-openssl \
    php81-pcntl \
    php81-pdo_mysql \
    php81-phar \
    php81-posix \
    php81-session \
    php81-sockets \
    php81-tokenizer \
    php81-zip

# create symlink
RUN ln -s /usr/bin/php81 /usr/bin/php

# configure php & supervisord
COPY .docker/php.ini /etc/php81/conf.d/99_envy.ini
COPY .docker/supervisord.conf /etc/supervisord.conf

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# schedule cron job
RUN echo "* * * * * php /app/artisan schedule:run" | crontab -

# create app dir & copy project files
WORKDIR /app
COPY . ./

# install composer dependencies & octane
RUN composer install --optimize-autoloader --no-dev
RUN php artisan octane:install --server=roadrunner

# expose laravel octane port
EXPOSE 8000

# volumes
VOLUME ["/app/storage"]

ENTRYPOINT ["/bin/ash", ".docker/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
