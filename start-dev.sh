docker compose up -d --build
echo "Waiting for the services to start..."
sleep 10
echo "Running migrations and seeding the database..."
docker compose exec -it app php artisan migrate
docker compose exec -it app php artisan db:seed
echo "Done!"
