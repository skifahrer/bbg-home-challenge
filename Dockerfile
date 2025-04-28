# Use the official PHP image as a base
FROM php:8.3-fpm

# Setup working directory
WORKDIR /var/www

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy files
COPY . .

# Install dependencies
RUN composer install --prefer-dist --no-dev --optimize-autoloader

# Setup environment
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Port configuration
EXPOSE 9000

CMD ["php-fpm"]