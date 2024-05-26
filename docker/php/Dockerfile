# Используем образ php 8.2 с FPM
FROM php:8.2-fpm

# Устанавливаем рабочую директорию внутри контейнера
WORKDIR /var/www/

# Устанавливаем необходимые зависимости через apt-get
RUN apt-get update && apt-get install -y \
      curl \
      wget \
      git \
      libxml2-dev \
      zip \
      zlib1g-dev \
      libpng-dev \
      unzip \
      libldb-dev \
      libldap2-dev \
      libpq-dev \
      libssl-dev \
      libpspell-dev \
      libtidy-dev \
      libzip-dev \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      librabbitmq-dev \
      pkg-config \
    && rm -rf /var/lib/apt/lists/*   # Удаляем кэш apt-get после установки для уменьшения размера образа

# Устанавливаем PHP расширения через docker-php-ext-install
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    dba \
    dom \
    fileinfo \
    gd \
    intl \
    ldap \
    opcache \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    phar \
    posix \
    pspell \
    soap \
    sockets \
    tidy \
    zip \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# Устанавливаем расширение amqp через pecl
RUN pecl install amqp \
    && docker-php-ext-enable amqp

# Настройка конфигурации PHP
RUN { \
    echo "upload_max_filesize = 1024M"; \
    echo "post_max_size = 1030M"; \
    echo "date.timezone=Europe/Moscow"; \
} > /usr/local/etc/php/php.ini

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash

# Назначаем права доступа к директории
RUN chown -R :www-data /var/www/ && chmod -R 755 /var/www/

# Очистка кэша и временных файлов
RUN rm -rf /var/cache/apk/*

# Команда запуска контейнера
CMD ["php-fpm"]
