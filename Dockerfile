# Imagen base de PHP con FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libicu-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar TODOS los archivos del proyecto
COPY . /var/www

# Aumentar el límite de memoria de PHP
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Limpiar caché de Composer antes de instalar dependencias
RUN composer clear-cache

# Instalar dependencias de Laravel sin límite de memoria
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-interaction --no-progress
RUN COMPOSER_MEMORY_LIMIT=-1 composer update --no-interaction --no-progress
RUN COMPOSER_MEMORY_LIMIT=-1 composer require livewire/livewire filament/filament:"^3.2" -W --no-interaction

# Ajustar permisos después de instalar dependencias
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/vendor
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# Desactivar modo mantenimiento antes de publicar assets
RUN php artisan down || true
RUN php artisan vendor:publish --tag=livewire:assets --force --no-interaction
RUN php artisan vendor:publish --tag=filament-assets --force --no-interaction
RUN php artisan up

# Optimizar Filament
RUN php artisan filament:optimize
RUN php artisan filament:optimize-clear

# Instalar y compilar assets con Vite
RUN npm install --legacy-peer-deps && npm run build

# Copiar configuración de Nginx
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Exponer el puerto en el contenedor
EXPOSE 8080

# Script de inicio para ejecutar Nginx y PHP-FPM juntos
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
