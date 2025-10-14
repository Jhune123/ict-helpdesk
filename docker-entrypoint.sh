#!/usr/bin/env bash
set -e

# Wait for DB to be ready (max timeout)
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
MAX_WAIT=${DB_WAIT_TIMEOUT:-60}
WAITED=0

echo ">>> waiting for database at ${DB_HOST}:${DB_PORT} (timeout ${MAX_WAIT}s)..."
until nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 1
  WAITED=$((WAITED+1))
  if [ "$WAITED" -ge "$MAX_WAIT" ]; then
    echo ">>> timeout waiting for ${DB_HOST}:${DB_PORT} after ${MAX_WAIT}s"
    break
  fi
done

# If APP_KEY not set, generate it (only if .env exists and APP_KEY empty)
if [ -f /var/www/html/.env ]; then
  if ! grep -q '^APP_KEY=' /var/www/html/.env || grep -q '^APP_KEY=$' /var/www/html/.env; then
    echo ">>> Generating APP_KEY..."
    php artisan key:generate || true
  fi
fi

# Run package discovery (safe) and clear caches that do not require DB
echo ">>> Running artisan package:discover..."
php artisan package:discover --ansi || true

# Optionally run migrations if environment variable is set (default: don't)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo ">>> Running migrations (RUN_MIGRATIONS=true)..."
  php artisan migrate --force || true
fi

# Now start php-fpm (the CMD or the vector below will run it)
exec "$@"
