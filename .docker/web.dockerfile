FROM php:7.4.30-apache

RUN apt-get update && apt-get upgrade -y && apt-get install wget -y

RUN docker-php-ext-install pdo pdo_mysql pcntl posix
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

RUN apt-get update \
&& apt-get install -y libssh2-1-dev libssh2-1 \
&& pecl install ssh2-1.3.1 \
&& docker-php-ext-enable ssh2

# Install PHP Zip extension
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libzip-dev \
        && \
    docker-php-ext-install zip

# Install PHP GD extension
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN apt-get update \
  && apt-get install -y curl \
  && apt-get install -y nodejs \
  && curl -L https://www.npmjs.com/install.sh | npm_install="6.10.0" | sh

COPY .docker/docker.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite
# does not work in docker for some reasons
RUN a2enmod expires
