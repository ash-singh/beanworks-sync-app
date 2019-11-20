FROM php:7.3.8-fpm-stretch

ARG DOCKER_UID=33
RUN usermod -u ${DOCKER_UID} www-data

ENV PHP_MONGODB_VERSION=1.5.5\
 PHP_XDEBUG_VERSION=2.7.2\
 PHP_DD_TRACE_VERSION=0.29.0

RUN set -ex &&\
 apt-get update &&\
 apt-get install -y libicu-dev libxml2-dev unzip supervisor &&\
 docker-php-ext-install -j 8 intl opcache pdo_mysql soap &&\
 pecl install\
 mongodb-${PHP_MONGODB_VERSION}\
 xdebug-${PHP_XDEBUG_VERSION} &&\
 docker-php-ext-enable mongodb &&\
 cd /tmp &&\
 apt-get autoremove --purge -y libicu-dev libxml2-dev && apt-get autoclean &&\
 rm -rf /var/lib/apt/lists/* && rm -rf /tmp/*

ARG APP_ENV=local
ENV APP_ENV=${APP_ENV}
ARG APP_VERSION=dev
ENV APP_VERSION=${APP_VERSION}
ENV APP_DEBUG=1

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        zlib1g-dev \
        librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
