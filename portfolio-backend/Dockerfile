# 1. Use PHP 8.4
FROM php:8.4-fpm

# 2. Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install \
        pdo pdo_pgsql pgsql mbstring bcmath zip pcntl exif

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy the entire application first
COPY . .

# 6. Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# 7. Set permissions (now storage & bootstrap/cache exist)
RUN chmod -R 777 storage bootstrap/cache

# 8. Expose port and start Laravel server
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]