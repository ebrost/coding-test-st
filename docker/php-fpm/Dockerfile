FROM php:7.4-fpm-alpine

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
RUN apk  add --update --no-cache git
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo_mysql \
    && apk add --no-cache --update rabbitmq-c-dev \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp \
    && docker-php-ext-enable amqp \
    && apk del .phpize-deps
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY php.ini /usr/local/etc/php/conf.d/php.override.ini
WORKDIR /var/www

CMD composer install ; php-fpm