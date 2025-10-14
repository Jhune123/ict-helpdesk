# syntax=docker/dockerfile:1
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libssl-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy existing Laravel application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
