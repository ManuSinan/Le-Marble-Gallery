#!/bin/bash

# Emergency Server Fix Script
# Run this on your live server if the site went down after .env changes

echo "🔧 Emergency Server Fix Script"
echo "================================"
echo ""

# Navigate to project directory
cd "$(dirname "$0")" || exit 1

echo "1. Clearing all Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

echo ""
echo "2. Checking .env file syntax..."
if php -r "require 'vendor/autoload.php'; \$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); \$dotenv->load();" 2>&1 | grep -i error; then
    echo "❌ .env file has syntax errors!"
    echo "Check for:"
    echo "  - Unclosed quotes"
    echo "  - Special characters in values (use quotes if needed)"
    echo "  - Missing equals signs"
else
    echo "✅ .env syntax looks OK"
fi

echo ""
echo "3. Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo '✅ Database connection OK\n'; } catch(Exception \$e) { echo '❌ Database error: ' . \$e->getMessage() . '\n'; }"

echo ""
echo "4. Checking web server status..."
if systemctl is-active --quiet apache2; then
    echo "✅ Apache is running"
elif systemctl is-active --quiet nginx; then
    echo "✅ Nginx is running"
else
    echo "⚠️  Web server might not be running"
fi

echo ""
echo "5. Checking file permissions..."
if [ -w storage ] && [ -w bootstrap/cache ]; then
    echo "✅ Storage directories are writable"
else
    echo "⚠️  Fixing permissions..."
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
fi

echo ""
echo "6. Checking Laravel logs for errors..."
if [ -f storage/logs/laravel.log ]; then
    echo "Recent errors:"
    tail -n 20 storage/logs/laravel.log | grep -i error | tail -n 5 || echo "No recent errors found"
else
    echo "⚠️  Log file not found"
fi

echo ""
echo "7. Rebuilding config cache..."
php artisan config:cache

echo ""
echo "================================"
echo "✅ Emergency fix complete!"
echo ""
echo "Next steps:"
echo "1. Check if website loads now"
echo "2. If not, check: tail -f storage/logs/laravel.log"
echo "3. Restart web server if needed: sudo systemctl restart apache2 (or nginx)"
