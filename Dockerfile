# =========================
# 1. Build frontend (Vite)
# =========================
FROM node:18 AS frontend
WORKDIR /app

# Copy package.json and lock files first for caching
COPY package*.json ./

# Install node dependencies
RUN npm install

# Copy the rest of the source code
COPY . .

# Build production assets (Vite)
RUN npm run build


# =========================
# 2. Build PHP Application
# =========================
FROM php:8.2-fpm

# install system deps and small tools (netcat for waiting on db)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev procps netcat-openbsd bash \
    && rm -rf /var/lib/apt/lists/*

# configure gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd mbstring exif pcntl bcmath pdo_mysql zip

# install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy app files (will be image's source)
COPY . .

# ✅ Copy the built Vite assets from the Node stage
COPY --from=frontend /app/public/build ./public/build

# install composer deps but skip scripts during build (avoid artisan running here)
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist --no-interaction

# set permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# add entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
