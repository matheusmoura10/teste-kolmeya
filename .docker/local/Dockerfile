FROM php:8.2-fpm

# Instalar dependências do Ubuntu Linux
RUN apt update && \
    apt install -y nginx libzip-dev libpng-dev $PHPIZE_DEPS

# Instalar extensões do PHP
RUN docker-php-ext-install opcache pcntl pdo_mysql zip sockets
RUN pecl update-channels && \
    pecl install ds igbinary xdebug && \
    docker-php-ext-enable ds igbinary xdebug sockets
ENV XDEBUG_MODE=coverage

# Copiar configurações
COPY .docker/local/nginx/default.conf /etc/nginx/sites-available/default
COPY .docker/local/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY .docker/local/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY .docker/local/php/php-fpm.conf /usr/local/etc/php/conf.d/php-fpm.conf

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copiar projeto
COPY --chown=www-data:www-data . /app

# Determinar entrypoint
COPY .docker/local/entrypoint.sh /entrypoint.sh
ENTRYPOINT sh /entrypoint.sh
