FROM php:8.2-cli

# Instalar dependencias incluyendo Node.js
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    libsodium-dev \
    zip \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql bcmath zip gd sodium \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos de dependencias primero (para cache)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-interaction --no-dev

# Copiar package.json para instalar dependencias de Node
COPY package.json package-lock.json* ./
RUN npm ci --include=dev

# Copiar el resto del código
COPY . .

# Compilar assets para producción
RUN npm run build

# Crear directorios y permisos
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}