# syntax=docker/dockerfile:1

FROM composer:lts as deps
WORKDIR /app
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-interaction

FROM php:8.2-apache as final
RUN docker-php-ext-install pdo pdo_mysql

# Use the default production configuration for PHP runtime arguments, see
# https://github.com/docker-library/docs/tree/master/php#configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=deps app/vendor/ /var/www/html/vendor
COPY ./composer.* /var/www/html
COPY ./src /var/www/html/src
COPY ./apps/PortfolioManager/RestApi /var/www/html/app

# Switch to a non-privileged user (defined in the base image) that the app will run under.
USER www-data
