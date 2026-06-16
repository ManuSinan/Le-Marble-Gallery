#!/bin/bash

# Laravel Deployment Script
# Run this script on your live server after SSH connection

echo "🚀 Starting deployment..."

# Navigate to project directory (update this path to your server's project path)
# cd /path/to/your/project

# Pull latest changes from git
echo "📥 Pulling latest changes from git..."
git pull origin main

# Install/Update Composer dependencies (production only)
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install/Update NPM dependencies
echo "📦 Installing NPM dependencies..."
npm ci --production

# Build assets for production
echo "🏗️  Building production assets..."
npm run production

# Run database migrations (if any)
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache configuration for better performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment completed successfully!"
