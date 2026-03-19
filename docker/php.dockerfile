# Use official PHP 8.4 FPM image
FROM php:8.4-fpm

# Install system dependencies (ADD libpq-dev for PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libjpeg-dev libfreetype6-dev \
    nodejs npm \
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

# Create app user
RUN useradd -G www-data,root -u 1000 -d /home/dev dev \
    && mkdir -p /home/dev/.composer \
    && chown -R dev:dev /home/dev

# Set working directory
WORKDIR /var/www

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend
RUN npm install && npm run build

# Permissions
RUN chown -R dev:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Switch to non-root user
USER dev

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm", "-F"]