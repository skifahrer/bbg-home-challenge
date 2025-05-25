BBG Take-Home Challenge – Product Catalog (Laravel)

Features:
- List products: GET /api/products (paginated, 10 per page)
- Product details: GET /api/products/{id}
- List categories: GET /api/categories
- Filter by category: GET /api/products?category_id={id}
- Multilingual support via ?locale=xx (e.g., ?locale=[en|sk])
- Optional search: ?search=keyword

Example:
http://127.0.0.1:8000/products/?page=1&category_id=3&locale=en&search=1

Tech:
- Laravel + MariaDB
- Dockerized setup

Run the project:
> docker compose up
