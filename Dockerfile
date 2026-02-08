FROM php:8.2-apache

# Desactivar MPMs que causan conflicto y dejar solo prefork
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_pgsql bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache rewrite (Laravel)
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Composer oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs

EXPOSE 80
CMD ["apache2-foreground"]
