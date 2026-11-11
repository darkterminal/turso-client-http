# Laravel proof — fresh Laravel 12 app + turso-client-http via path repo.
# Build context must be the repo root.
#
#   docker/laravel-proof/proof.sh

ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli-bookworm

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

RUN apt-get update && apt-get install -q -y --no-install-recommends \
        git \
        curl \
        unzip \
        ca-certificates \
        libsqlite3-dev \
        libzip-dev \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Fresh Laravel 12 application + the proof command
RUN composer create-project laravel/laravel /app "12.*" --prefer-dist --no-progress

COPY . /driver-src

ARG DRIVER_VERSION=*
RUN composer config minimum-stability dev && \
    composer config prefer-stable true && \
    composer config repositories.turso-path '{"type":"path","url":"/driver-src","options":{"symlink":false}}' && \
    composer require "darkterminal/turso-client-http:${DRIVER_VERSION}" --no-progress

COPY docker/laravel-proof/app/ProofCommand.php /app/app/Console/Commands/ProofCommand.php
COPY docker/laravel-proof/app/config/turso-http.php /app/config/turso-http.php
COPY docker/laravel-proof/app/.env /app/.env

RUN composer dump-autoload

CMD ["php", "artisan", "proof:run"]
