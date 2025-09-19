# Stage 1: Build dependencies with Composer
FROM composer:2 AS build

WORKDIR /app

# Copy only composer files first (better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev)
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

# Now copy the full application (including artisan)
COPY . .

# Run Composer scripts now that artisan exists
RUN composer dump-autoload --optimize \
    && composer run-script post-autoload-dump

# Stage 2: Production image with PHP & Apache
FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libjpeg-dev libonig-dev libwebp-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

WORKDIR /var/www/html

# Copy app (including vendor from build stage)
COPY --from=build /app . 

# Set permissions for Laravel writable dirs
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Apache setup for Laravel
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2dissite 000-default && a2ensite 000-default

# Cloud Run expects PORT env
ENV PORT 8080
EXPOSE 8080
