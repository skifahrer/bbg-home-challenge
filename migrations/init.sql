-- Drop tables if they already exist
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS category;

-- Create category table
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create product table
CREATE TABLE product (
     id INT AUTO_INCREMENT PRIMARY KEY,
     category_id INT NOT NULL,
     price INT NOT NULL,
     name VARCHAR(255) NOT NULL,
     slug VARCHAR(255) NOT NULL UNIQUE,
     active BOOLEAN NOT NULL DEFAULT TRUE,
     description TEXT,
     image_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/150',
     created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
     updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
);

-- seed tables
INSERT INTO category (name, slug, active) VALUES
    ('Electronics', 'electronics', 1),
    ('Books', 'books', 1),
    ('Clothing', 'clothing', 1),
    ('Home & Kitchen', 'home-kitchen', 1),
    ('Toys', 'toys', 1);

INSERT INTO bbg.product (category_id, price, name, slug, active, description, image_url) VALUES
    (1, 29999, 'Smartphone X1', 'smartphone-x1', 1, 'High-performance smartphone with AMOLED screen', 'https://image.nay.sk/foto/250/4/3/4/product_5419434.jpg'),
    (1, 8999, 'Wireless Headphones', 'wireless-headphones', 1, 'Noise-cancelling headphones with long battery life', 'https://image.nay.sk/foto/250/1/4/1/product_6982141.jpg'),
    (1, 19900, 'Smartwatch S3', 'smartwatch-s3', 1, 'Fitness and notification smart wearable', 'https://image.nay.sk/foto/250/1/6/0/product_6887061.jpg'),
    (2, 1999, 'Fantasy Novel', 'fantasy-novel', 1, 'Epic tale of adventure and dragons', 'https://image.nay.sk/foto/250/1/1/9/product_6981911.jpg'),
    (2, 3550, 'Programming Guide', 'programming-guide', 1, 'Comprehensive PHP & MySQL guidebook', 'https://image.nay.sk/foto/250/7/2/9/product_6886927.jpg'),
    (2, 1599, 'Cookbook Basics', 'cookbook-basics', 1, 'Easy recipes for everyday meals', 'https://image.nay.sk/foto/250/6/5/9/product_6054956.jpg'),
    (3, 2500, 'Men\'s T-Shirt', 'mens-tshirt', 1, '100% cotton, regular fit', 'https://image.nay.sk/foto/250/2/1/4/product_7215412.jpg'),
    (3, 4999, 'Women\'s Blazer', 'womens-blazer', 1, 'Professional and stylish', 'https://image.nay.sk/foto/250/7/3/8/product_6631837.jpg'),
    (3, 5995, 'Running Shoes', 'running-shoes', 1, 'Comfortable running shoes for daily wear', 'https://image.nay.sk/foto/250/6/0/0/product_6885006.jpg'),
    (4, 7900, 'Coffee Maker', 'coffee-maker', 1, 'Automatic coffee machine with timer', 'https://image.nay.sk/foto/250/9/9/1/product_6982199.jpg'),
    (4, 13900, 'Air Fryer XL', 'air-fryer-xl', 1, 'Healthy cooking with little oil', 'https://image.nay.sk/foto/250/2/2/8/product_6631822.jpg'),
    (4, 2950, 'Electric Kettle', 'electric-kettle', 1, 'Fast boiling stainless steel kettle', 'https://image.nay.sk/foto/250/1/7/8/product_6884871.jpg'),
    (5, 1999, 'Puzzle Set', 'puzzle-set', 1, '500-piece scenic puzzle', 'https://image.nay.sk/foto/250/2/0/9/product_6013902.jpg'),
    (5, 3499, 'Remote Control Car', 'remote-control-car', 1, 'Fast and rechargeable RC car', 'https://image.nay.sk/foto/250/2/6/4/product_6055462.jpg'),
    (5, 1495, 'Plush Teddy Bear', 'plush-teddy-bear', 1, 'Soft stuffed animal for kids', 'https://image.nay.sk/foto/250/8/1/2/product_6692218.jpg');
