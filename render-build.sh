#!/usr/bin/env bash
set -e

echo "🚀 Starting Render Build Script..."

# 1. Create .env if it doesn't exist
if [ ! -f .env ]; then
    echo "📄 Creating .env file from .env.example..."
    cp .env.example .env
fi

# 2. Install PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Install & build frontend
npm install
npm run build

# 4. Fix permissions
chmod -R 775 storage bootstrap/cache

# 5. Generate APP_KEY if missing in environment and .env
if [ -z "$APP_KEY" ]; then
    if grep -q "APP_KEY=base64:" .env; then
        echo "✅ APP_KEY already exists in .env"
    else
        echo "⚠️ APP_KEY not set, generating..."
        php artisan key:generate
    fi
else
    echo "✅ APP_KEY provided from environment"
    # Keep .env in sync with the environment APP_KEY if it's set
    sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|g" .env
fi

# 6. Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force || true

# 7. Laravel optimization
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Storage link
php artisan storage:link || true

echo "✅ Build completed successfully!"