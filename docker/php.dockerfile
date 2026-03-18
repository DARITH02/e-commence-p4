# Use official PHP 8.4 FPM image
FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libjpeg-dev libfreetype6-dev \
    nodejs npm libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install and enable Redis
RUN pecl install redis && docker-php-ext-enable redis

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user for app
RUN useradd -G www-data,root -u 1000 -d /home/dev dev \
    && mkdir -p /home/dev/.composer \
    && chown -R dev:dev /home/dev

# Set working directory
WORKDIR /var/www

# Copy app files into container
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies & build frontend
RUN npm install && npm run build

# Give proper permissions
RUN chown -R dev:www-data /var/www && chmod -R 775 storage bootstrap/cache

# Switch to app user
USER dev

# Expose port for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
<<<<<<< HEAD
CMD ["php-fpm", "-F"]
=======
CMD ["php-fpm", "-F"]
>>>>>>> e50830231de277ea03eeca2c5d7672e170bbc51d
