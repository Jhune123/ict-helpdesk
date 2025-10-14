FROM php:8.3-fpm

WORKDIR /var/www/html

# Install dependencies and extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libssl-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy Laravel app to container
COPY . .

# Install dependencies without scripts (to avoid artisan failing)
RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 9000
CMD ["php-fpm"]
