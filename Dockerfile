FROM php:7.4.13-fpm-alpine as base

WORKDIR /var/www

# Override Docker configuration: listen on Unix socket instead of TCP
#RUN sed -i "s|listen = 9000|listen = /var/run/php/fpm.sock\nlisten.mode = 0666|" /usr/local/etc/php-fpm.d/zz-docker.conf

# Use the default production configuration
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install dependencies
RUN set -xe \
    && apk add --no-cache bash icu-dev \
    && docker-php-ext-install pdo pdo_mysql intl pcntl

CMD ["php-fpm"]


FROM composer:2.0.7 as composer

RUN rm -rf /var/www && mkdir /var/www
WORKDIR /var/www

COPY composer.* /var/www/

ARG APP_ENV=dev

RUN set -xe \
    && if [ "$APP_ENV" = "prod" ]; then export ARGS="--no-dev"; fi \
    && composer install --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction $ARGS

COPY . /var/www

RUN composer dump-autoload --classmap-authoritative


FROM base

ARG APP_ENV=dev
ARG APP_DEBUG=1

ENV APP_ENV $APP_ENV
ENV APP_DEBUG $APP_DEBUG

COPY --from=composer /var/www/ /var/www/
COPY nginx.conf.d /etc/nginx/conf.d/


# Memory limit increase is required by the dev image
RUN php -d memory_limit=256M bin/console cache:clear
RUN bin/console assets:install
