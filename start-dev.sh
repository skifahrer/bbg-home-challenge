docker compose down
echo "Building and starting the services..."
docker compose up -d --build
echo "Waiting for the services to start..."
sleep 10
echo "Running clear cache..."
docker compose exec -it app php artisan config:clear
docker compose exec -it app php artisan cache:clear
docker compose exec -it app php artisan route:clear
docker compose exec -it app php artisan view:clear
echo "Running tests..."
docker compose exec -it app php artisan test
echo "Running migrations and seeding the database..."
docker compose exec -it app php artisan migrate:fresh --seed
php artisan route:list --path=api
echo "Done!"
