#!/usr/bin/env bash
set -e

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /var/www/ict-helpdesk

# Pull latest code from GitHub (includes pre-built assets)
echo "📥 Pulling latest code and assets from GitHub..."
git pull origin main  # Change 'main' to your branch name if different

# Verify assets were pulled
if [ ! -d "public/build" ]; then
  echo "⚠️  WARNING: public/build directory not found!"
  echo "Make sure you built assets locally with 'npm run build' before pushing."
fi

# Run migrations (optional - remove if you don't want auto-migrations)
echo "🗄️  Running database migrations..."
docker compose exec -T app php artisan migrate --force

# Clear and cache config
echo "🧹 Clearing caches..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan route:clear
docker compose exec -T app php artisan view:clear

# Optimize for production
echo "⚡ Optimizing for production..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
docker compose exec -T app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/image
docker compose exec -T app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/image

echo "✅ Deployment completed successfully!"
echo "🌐 Your app should now be running with the latest code and assets."
