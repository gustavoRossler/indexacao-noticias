# FROM php:7.2.5-fpm
FROM php:7.3-fpm

RUN apt-get update \
    && apt-get install -y libzip-dev \
    && apt-get install -y git \
    && apt-get install zip unzip

RUN apt-get update && apt-get install -y libmcrypt-dev \
    libmagickwand-dev --no-install-recommends \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD docker-php-entrypoint php-fpm