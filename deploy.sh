#!/usr/bin/env bash
set -e

echo "⚡ Starting quick deployment..."

# Navigate to project directory
cd /var/www/ict-helpdesk

# Pull latest code from GitHub
echo "📥 Pulling latest code from GitHub..."
git pull origin main  # Change 'main' to your branch name if different

# Restart containers (picks up volume-mounted changes)
echo "🔄 Restarting containers..."
docker compose restart app

# Wait for app to be ready
echo "⏳ Waiting for app to be ready..."
sleep 5

# Install/update composer dependencies
echo "📦 Updating composer dependencies..."
docker compose exec -T app composer install --optimize-autoloader --no-dev --prefer-dist --no-interaction

# Run migrations (optional)
echo "🗄️  Running database migrations..."
docker compose exec -T app php artisan migrate --force

# Clear and cache
echo "🧹 Clearing and caching..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
docker compose exec -T app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Quick deployment completed!"
