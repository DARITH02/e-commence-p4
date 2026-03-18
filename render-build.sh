#!/usr/bin/env bash
set -e

echo "🚀 Starting Render Build Script..."

# 1. Install PHP dependencies
composer install --no-dev --optimize-autoloader

# 2. Install & build frontend
npm install
npm run build

# 3. Fix permissions (VERY IMPORTANT)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 4. Generate APP_KEY if missing (and persist)
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY not set, generating..."
    php artisan key:generate
fi

# 5. Laravel optimization
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Storage link (for images/uploads)
php artisan storage:link || true

echo "✅ Build completed successfully!"