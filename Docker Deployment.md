Updating docker instance

    docker compose build --no-cache
    docker compose up -d

Fetch update
    chmod +x deploy.sh
    ./deploy.sh

Remove bootstrap cache
    sudo rm -f bootstrap/cache/*.php

   sudo mkdir -p mysql_data
   sudo chown -R 999:999 mysql_data

Docker post update

    docker compose exec app php artisan cache:clear
    docker compose exec app php artisan config:clear
    docker compose exec app php artisan route:clear
    docker compose exec app php artisan view:clear

    docker compose exec app php artisan migrate


# Fix permissions if needed
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/storage

# Clear Laravel cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
