# Многоэтапная сборка для уменьшения размера
FROM composer:2.6 AS composer

FROM php:8.3-fpm-alpine

WORKDIR /var/www/laravel-project

# Установка только необходимых зависимостей
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    postgresql-dev && \
    apk add --no-cache \
    bash \
    zip \
    unzip \
    curl \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu \
    postgresql-libs && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    zip \
    gd \
    mbstring \
    opcache && \
    pecl install -o -f redis && \
    docker-php-ext-enable redis && \
    apk del .build-deps && \
    rm -rf /var/cache/apk/* /tmp/*

# Копируем только composer из первого этапа
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Оптимизированные настройки PHP
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.enable_cli=1'; \
    echo 'realpath_cache_size=4096K'; \
    echo 'realpath_cache_ttl=600'; \
    echo 'expose_php=Off'; \
} > /usr/local/etc/php/conf.d/opcache.ini

CMD ["php-fpm"]