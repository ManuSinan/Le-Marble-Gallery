# Deployment Guide for The Canvas Co

## Prerequisites
- SSH access to your live server
- Git repository access configured on the server
- PHP 8.2+ installed
- Composer installed
- Node.js and NPM installed
- Web server (Apache/Nginx) configured

## Deployment Steps

### Option 1: Using the Deployment Script (Recommended)

1. **SSH into your live server:**
   ```bash
   ssh user@your-server-ip
   ```

2. **Navigate to your project directory:**
   ```bash
   cd /path/to/your/project
   ```

3. **Make the script executable (if not already):**
   ```bash
   chmod +x deploy.sh
   ```

4. **Run the deployment script:**
   ```bash
   ./deploy.sh
   ```

### Option 2: Manual Deployment Steps

1. **SSH into your live server:**
   ```bash
   ssh user@your-server-ip
   ```

2. **Navigate to your project directory:**
   ```bash
   cd /path/to/your/project
   ```

3. **Pull latest changes from Git:**
   ```bash
   git pull origin main
   ```

4. **Install/Update Composer dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

5. **Install/Update NPM dependencies:**
   ```bash
   npm ci --production
   ```

6. **Build production assets:**
   ```bash
   npm run production
   ```

7. **Run database migrations (if any new migrations exist):**
   ```bash
   php artisan migrate --force
   ```

8. **Clear all caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

9. **Cache configuration for better performance:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

10. **Set proper permissions:**
    ```bash
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    ```
    (Replace `www-data` with your web server user if different)

## Important Notes

### Environment Configuration
- Make sure your `.env` file on the live server has:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - Correct database credentials
  - Production API keys and secrets

### Database Migrations
- Always backup your database before running migrations:
  ```bash
  php artisan backup:run  # If you have backup package
  # OR manually export database
  ```

### Asset Compilation
- If you make changes to SCSS/JS files, you need to rebuild assets:
  ```bash
  npm run production
  ```

### Troubleshooting

**If you get permission errors:**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

**If assets are not updating:**
- Clear browser cache
- Check if `public/mix-manifest.json` is updated
- Verify `public/assets/` directory has latest files

**If you get "Class not found" errors:**
```bash
composer dump-autoload
php artisan optimize:clear
```

## Quick Deployment Command (One-liner)

```bash
git pull origin main && composer install --no-dev --optimize-autoloader && npm ci --production && npm run production && php artisan migrate --force && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Post-Deployment Checklist

- [ ] Verify website loads correctly
- [ ] Check that CSS/JS assets are loading
- [ ] Test login/signup functionality
- [ ] Verify search bar styling matches desktop
- [ ] Check mobile responsive view
- [ ] Test OTP verification flow
- [ ] Verify database connections
- [ ] Check error logs: `tail -f storage/logs/laravel.log`
