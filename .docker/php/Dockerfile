FROM php:8.2-fpm

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev libpq-dev librabbitmq-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql pgsql pdo_pgsql \
    && pecl install apcu amqp \
    && docker-php-ext-enable apcu amqp \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/project

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer