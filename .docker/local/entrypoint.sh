#!/bin/sh

# Iniciar Nginx
nginx

# Configurar permissões
chmod 777 -R storage/
chmod 777 -R bootstrap/cache/

composer install --ignore-platform-reqs

# Iniciar o PHP-FPM
php-fpm
