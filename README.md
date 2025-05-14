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
- Use your preferred frontend framework/library or pure JS with CS

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

# Development
## Quick Start

Start docker project
```bash
  docker compose up
```

Run this in php service to install Database data
```bash
  php bin/install.php
```

Run this in npm service to install frontend:
- for running watcher
```bash
  npm run watch
```
- for development
```bash
  npm run dev 
```
- for production
```bash
  npm run build
```
