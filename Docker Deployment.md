Updating docker instance

    docker compose build --no-cache
    docker compose up -d

Fetch update
    ./deploy.sh

   sudo mkdir -p mysql_data
   sudo chown -R 999:999 mysql_data


docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

docker compose exec app php artisan migrate
