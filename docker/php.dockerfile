# Use official PHP 8.4 FPM image
FROM php:8.4-fpm

# Install system dependencies (ADD libpq-dev for PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libjpeg-dev libfreetype6-dev \
    nodejs npm mariadb-client postgresql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (SUPPORT BOTH MYSQL + PGSQL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        mbstring exif pcntl bcmath gd zip

# Install Redis
RUN pecl install redis && docker-php-ext-enable redis

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend
RUN npm install && npm run build

# Set permissions while still root
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Optional: create storage symlink
RUN php artisan storage:link || true

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM as default www-data
CMD ["php-fpm", "-F"]