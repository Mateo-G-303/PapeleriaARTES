# =========================
# Etapa 1: build frontend
# =========================
FROM node:18 AS nodebuilder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build


# =========================
# Etapa 2: PHP runtime
# =========================
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql bcmath zip gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY --from=nodebuilder /app/public/build ./public/build
COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

