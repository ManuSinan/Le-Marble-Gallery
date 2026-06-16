# Emergency Fix for .env File

## Issue Identified

Your `.env` file has a database password with special characters that need to be quoted:
- The `$` character can cause issues in .env files

## Immediate Fix Steps (Run on Live Server)

### Step 1: SSH into your server
```bash
ssh user@your-server
cd /path/to/the-canvas-co
```

### Step 2: Backup current .env
```bash
cp .env .env.backup
```

### Step 3: Fix the .env file

Edit `.env` and make these changes:

1. **Quote the database password** (important!):
   ```env
   DB_PASSWORD="your_password_here"
   ```

2. **Set APP_URL** (replace with your domain):
   ```env
   APP_URL=https://your-domain.com
   ```

3. **Set APP_DEBUG to false** for production:
   ```env
   APP_DEBUG=false
   ```

### Step 4: Clear all caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
```

### Step 5: Rebuild config cache
```bash
php artisan config:cache
```

### Step 6: Check if it works
```bash
php artisan tinker
```
Then run:
```php
DB::connection()->getPdo();
```
If it works, type `exit` and check your website.

### Step 7: If still not working, check logs
```bash
tail -f storage/logs/laravel.log
```

### Step 8: Restart web server (if needed)
```bash
# For Apache:
sudo systemctl restart apache2

# OR for Nginx:
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm  # or php8.1-fpm, php8.0-fpm
```

## Corrected .env Template

Here's the corrected version with proper quoting:

```env
APP_ID=com.thecanvasco.app
APP_VERSION=1.0.0

APP_NAME='The Canvas Co'
APP_ENV=production
APP_KEY=base64:your-key-here
APP_DEBUG=false
APP_PERMISSION=true

APP_URL=https://your-domain.com

APP_LOCAL_LANG_CODE=
APP_LOCAL_LANG=
APP_LOCATION_STATE=false

APP_USE_PINCODE_FOR_DELIVERY=true

WEBHOOK=
WEBHOOK_API_KEY=
CONNECT_API_KEY=xxxx

THEME_PRIMARY=f9322b
THEME_PRIMARY_COLOR=fff
THEME_SECONDARY=ffd6c1
THEME_SECONDARY_COLOR=f9322b
THEME_TERTIARY=482f23
THEME_FOURTHIARY=f9322b
STATUS_BAR_STYLE=black-translucent
 
LOG_CHANNEL=stack
 
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thecanvasco_db
DB_USERNAME=root
DB_PASSWORD="your_password_here"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_COOKIE=__session

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtpout.secureserver.net
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_NAME=The Canvas Co
MAIL_FROM_ADDRESS= 

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

FIREBASE=false
FIREBASE_SERVER_KEY=
FIREBASE_APIKEY=
FIREBASE_AUTHDOMAIN=
FIREBASE_PROJECTID=
FIREBASE_STORAGEBUCKET=
FIREBASE_MESSAGINGSENDERID=
FIREBASE_APPID=

PAYMENT_GATEWAY_KEY=
PAYMENT_GATEWAY_SECRET=

USE_WHATSAPP_OTP=false
WHATSAPP_GRAPH_URL=https://graph.facebook.com/v18.0
WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_API_KEY=
WHATSAPP_OTP_COUNTRY_CODE=91
WHATSAPP_OTP_TEMPLATE_NAME=peak_otp
```

## Quick One-Liner Fix

Run this on your server (replace with your actual domain and path):

```bash
cd /path/to/the-canvas-co && \
sed -i 's/^APP_URL=$/APP_URL=https://your-domain.com/' .env && \
sed -i 's/^APP_DEBUG=true$/APP_DEBUG=false/' .env && \
php artisan config:clear && \
php artisan config:cache && \
php artisan optimize:clear
```
