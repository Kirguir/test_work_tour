FROM php:fpm
MAINTAINER Your Name <your_email@domain.com>

ENV REFRESHED_AT 2017-01-17

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt pdo_mysql mbstring \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# Xdebug
RUN pecl install xdebug-beta

COPY ./xdebug.ini /usr/local/etc/php/conf.d/

RUN usermod -u 1000 www-data

WORKDIR /var/www

CMD ["php-fpm"]
