FROM php:8.4-fpm

# Устанавливаем рабочую директорию
WORKDIR /var/www/laravel

# Добавляем пользователя
RUN useradd -ms /bin/bash dws

# Устанавливаем зависимости и расширения
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
        redis-server \
        git \
        libzip-dev \
        zip \
        unzip \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Устанавливаем расширение Redis
RUN pecl install redis && docker-php-ext-enable redis

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Переключаемся на пользователя dws
USER dws
