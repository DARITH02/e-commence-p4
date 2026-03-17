#!/usr/bin/env bash
# exit on error
set -e

echo "🚀 Starting Render Build Script..."

# 1. PHP Dependencies
composer install --no-dev --optimize-autoloader

# 2. Frontend Assets
npm install
npm run build

# 3. Laravel Specifics
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Optional: Generate Key if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY not set, generating one for the build session..."
    php artisan key:generate --show
fi

# 5. Database (Run migrations during build or start? 
# Usually better during start, but let's prepare)
# php artisan migrate --force

echo "✅ Build completed successfully!"
