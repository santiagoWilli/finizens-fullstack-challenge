# syntax=docker/dockerfile:1

FROM php:8.2 as final
RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libpq-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Use the default production configuration for PHP runtime arguments, see
# https://github.com/docker-library/docs/tree/master/php#configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./composer.* /var/www/html
COPY ./src /var/www/html/src
COPY ./apps /var/www/html/apps

WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-interaction

EXPOSE 9000
CMD cd /var/www/html/apps/PortfolioManager/RestApi/public; php -S 0.0.0.0:9000

# Switch to a non-privileged user (defined in the base image) that the app will run under.
USER www-data
