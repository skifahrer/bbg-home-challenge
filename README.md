# Development

## Architecture & Features

This project combines a Laravel backend with a Vue.js frontend, structured into a containerized architecture for local development and deployment.

### Containerized Structure

The application is split into four Docker containers:

- **Laravel Container** – handles the backend logic via PHP and Laravel.
- **Vue.js Container** – builds and serves the frontend using Vite and Nginx.
- **Nginx Proxy Container** – proxies and serves PHP-FPM requests to the Laravel backend.
- **Database Container** – used for testing and local development only; not intended for production use.

### Frontend

- Responsive layout compatible with desktop and mobile devices.
- Integrated loading spinner for enhanced user experience during data fetching.
- Custom `404 Not Found` page for undefined routes.

### Backend

- Nginx configurations are located in the `./nginx` directory and processed by two dedicated Dockerfiles
- Faker is used to automatically generate sample data when seeding the database
- Basic automated tests
- Manual testing routes available in /tests/Manual/.http (in VS Code use REST Client)

## Quick Start
### Install (Ubuntu Linux) - Recomended
Copy .env.example in application root directory.
```bash
cp .env.example .env
```

Fill DB_PASSWORD and DB_ROOT_PASSWORD in .env.
```bash
vi .env
```

### Run
```bash
bash start-dev.sh
```

### Tests
```bash
php artisan test
```

### Install (Windows 10)
- Copy .env.example in application root directory.
- Fill DB_PASSWORD and DB_ROOT_PASSWORD in .env.
- Install Docker Desktop and WSL.
- Reboot PC
- Build
```bash
docker-compose up --build
```
- Open Docker Desktop and start app container
- Open app container in docker terminal
- Manual run migration
```bash
php artisan migrate
```
- Manual run seed
```bash
php artisan db:seed
```
- Restart all containers (without rebuild)

# BBG take-home-challenge
## Description
We appreciate your interest in joining our BBG gang as a Full-Stack Developer. This take-home challenge will help us evaluate your coding skills, problem-solving approach, and ability to create functional web applications.

## Objective

Build a basic product catalog application that allows users to:
- View a list of products
- See details of individual products
- Filter products by category

## Requirements

### Backend Development

**Technology Stack:**
- PHP with a lightweight framework of your choice
- Use a SQL database (MySQL or PostgreSQL)

**Features to Implement:**
1. Create an API with the following endpoints:
    - GET `/api/products` - List all products with pagination (limit 10 per page)
    - GET `/api/products/:id` - Get a single product by ID
    - GET `/api/categories` - List all product categories

2. Data Requirements:
    - Create a simple database schema for products with at least these fields:
        - id
        - name
        - price
        - description
        - category_id
        - image_url (can be a placeholder)
    - Create a categories table with at least:
        - id
        - name
    - Seed the database with at least 15 sample products across 3-5 categories

### Frontend Development

**Technology Stack:**
- Use your preferred frontend framework/library or pure JS with CSS

**Features to Implement:**
1. Product Listing Page:
    - Display products in a grid with basic information (name, price, image)
    - Show pagination controls
    - Includes a simple dropdown to filter by category

2. Product Detail Page:
    - Show complete product information when a user clicks on a product
    - Include a "Back to Listing" button

### Optional features to Implement

- Multilingual support
- Implement search functionality to filter products.
- Implement shopping cart.
- Implement checkout process.

### Requirements for Both:

- Clean, readable code with appropriate comments
- Basic error handling
- Responsive design (mobile-friendly)

## Evaluation Criteria

Your submission will be evaluated based on:
- Functionality (does it work as expected?)
- Code quality and organization
- Proper use of the selected technologies
- UI/UX considerations
- Documentation quality

## Submission

Please submit your code as Pull Request to this repository.