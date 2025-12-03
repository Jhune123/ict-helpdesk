#!/usr/bin/env bash
set -e

# Wait for DB to be ready (max timeout)
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
MAX_WAIT=${DB_WAIT_TIMEOUT:-120}
WAITED=0

echo ">>> Waiting for database at ${DB_HOST}:${DB_PORT} (timeout ${MAX_WAIT}s)..."

# First wait for DNS resolution
until getent hosts "$DB_HOST" > /dev/null 2>&1; do
  echo ">>> Waiting for DNS resolution of ${DB_HOST}..."
  sleep 2
  WAITED=$((WAITED+2))
  if [ "$WAITED" -ge "$MAX_WAIT" ]; then
    echo ">>> ERROR: DNS resolution timeout for ${DB_HOST} after ${MAX_WAIT}s"
    exit 1
  fi
done

echo ">>> DNS resolved for ${DB_HOST}, now checking port..."

# Then wait for port to be open
until nc -z "$DB_HOST" "$DB_PORT" 2>/dev/null; do
  echo ">>> Waiting for ${DB_HOST}:${DB_PORT} to be ready..."
  sleep 2
  WAITED=$((WAITED+2))
  if [ "$WAITED" -ge "$MAX_WAIT" ]; then
    echo ">>> ERROR: Timeout waiting for ${DB_HOST}:${DB_PORT} after ${MAX_WAIT}s"
    exit 1
  fi
done

echo ">>> Database is ready at ${DB_HOST}:${DB_PORT}!"

# Additional wait for MySQL to fully initialize
echo ">>> Waiting 10 more seconds for MySQL to fully initialize..."
sleep 10

# If APP_KEY not set, generate it
if [ -f /var/www/html/.env ]; then
  if ! grep -q '^APP_KEY=' /var/www/html/.env || grep -q '^APP_KEY=$' /var/www/html/.env; then
    echo ">>> Generating APP_KEY..."
    php artisan key:generate || true
  fi
fi

# Run package discovery
echo ">>> Running artisan package:discover..."
php artisan package:discover --ansi || true

# Test database connection before migrations
echo ">>> Testing database connection..."
php artisan db:show || {
  echo ">>> WARNING: Could not connect to database, but continuing..."
}

# Optionally run migrations
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo ">>> Running migrations (RUN_MIGRATIONS=true)..."
  php artisan migrate --force || true
fi

echo ">>> Starting PHP-FPM..."
exec "$@"
