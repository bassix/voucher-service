# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=7.4
ARG NGINX_VERSION=1.19

FROM php:${PHP_VERSION}-fpm-alpine AS app

# persistent / runtime deps
RUN apk update

RUN apk add --no-cache \
    acl \
    file \
    gettext \
    git;

RUN set -eux; \
    apk add --no-cache \
        $PHPIZE_DEPS \
        coreutils \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libtool \
        libwebp-dev \
        rabbitmq-c \
        rabbitmq-c-dev \
        zlib-dev \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
    docker-php-ext-install -j$(nproc) \
        bcmath \
        pdo_mysql \
    ; \
    pecl install -f amqp; \
    docker-php-ext-enable amqp; \
    docker-php-ext-enable opcache;

# Make Composer available
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Use the default development configuration
# @todo we need a solution to set production or development!
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Configure the PHP and the OPcache
COPY ./docker/php/php.ini $PHP_INI_DIR/conf.d/php.ini
COPY ./docker/php/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini

WORKDIR /srv/app

# build for production
ARG APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY .env .env.test composer.json composer.lock symfony.lock ./
COPY bin bin/
COPY config config/
COPY src src/
COPY public public/

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
    rm -rf var/cache/*; \
    composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
    composer install --no-progress --no-suggest --no-interaction --optimize-autoloader --classmap-authoritative -n --prefer-dist; \
    composer clear-cache; \
    composer dump-autoload --classmap-authoritative; \
    APP_SECRET='' composer run-script post-install-cmd; \
    chmod +x bin/console; sync;
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Connect the host user Id to the www-data user
ARG HOST_UID=33
ENV HOST_UID "$HOST_UID"

# Connect the host group id to the www-data user
ARG HOST_GID=33
ENV HOST_GID "$HOST_GID"

# Change www-data user to match the host system UID and GID and chown www directory
RUN set -eux; \
    apk add --update bash shadow; \
    usermod -u ${HOST_GID} www-data; \
    groupmod -g ${HOST_GID} www-data; \
    mkdir -p var/cache var/log; \
    chown -R www-data:www-data var/cache var/log;

# Create volumes
VOLUME ["/srv/app", "/srv/app/public", "/srv/app/var"]

# Add and activate message consumer
COPY public public/
ENTRYPOINT ["bash", "/srv/app/messenger-consume.sh"]

FROM nginx:${NGINX_VERSION}-alpine AS nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

WORKDIR /srv/app

COPY --from=app /srv/app/public public/

VOLUME /srv/app/public
