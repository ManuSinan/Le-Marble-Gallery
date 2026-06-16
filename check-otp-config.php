<?php
/**
 * OTP Configuration Checker
 * 
 * Run this script on your live server to diagnose OTP issues:
 * php check-otp-config.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== OTP Configuration Checker ===\n\n";

// Check WhatsApp Configuration
echo "📱 WhatsApp Configuration:\n";
echo "─────────────────────────────────\n";
$useWhatsApp = config('whatsapp.use_whatsapp_otp');
echo "USE_WHATSAPP_OTP: " . ($useWhatsApp ? "✅ TRUE" : "❌ FALSE") . "\n";

if ($useWhatsApp) {
    $graphUrl = config('whatsapp.graph_url');
    $phoneNumberId = config('whatsapp.phone_number_id');
    $apiKey = config('whatsapp.api_key');
    $countryCode = config('whatsapp.country_code');
    $templateName = config('whatsapp.otp_template_name');
    
    echo "Graph URL: " . ($graphUrl ? "✅ $graphUrl" : "❌ MISSING") . "\n";
    echo "Phone Number ID: " . ($phoneNumberId ? "✅ SET (" . substr($phoneNumberId, 0, 10) . "...)" : "❌ MISSING") . "\n";
    echo "API Key: " . ($apiKey ? "✅ SET (" . strlen($apiKey) . " characters)" : "❌ MISSING") . "\n";
    echo "Country Code: " . ($countryCode ? "✅ $countryCode" : "❌ MISSING") . "\n";
    echo "Template Name: " . ($templateName ? "✅ $templateName" : "❌ MISSING") . "\n";
    
    // Check if all required configs are present
    $allSet = !empty($graphUrl) && !empty($phoneNumberId) && !empty($apiKey);
    echo "\nConfiguration Status: " . ($allSet ? "✅ COMPLETE" : "❌ INCOMPLETE") . "\n";
    
    if (!$allSet) {
        echo "\n⚠️  Missing required configuration. Please check your .env file:\n";
        echo "   - WHATSAPP_GRAPH_URL\n";
        echo "   - WHATSAPP_PHONE_NUMBER_ID\n";
        echo "   - WHATSAPP_API_KEY\n";
    }
} else {
    echo "\n📧 Using SMS fallback (USE_WHATSAPP_OTP=false)\n";
    echo "   Check SMS configuration in .env\n";
}

// Check environment
echo "\n🌍 Environment:\n";
echo "─────────────────────────────────\n";
echo "APP_ENV: " . env('APP_ENV', 'not set') . "\n";
echo "APP_DEBUG: " . (env('APP_DEBUG', false) ? "TRUE ⚠️" : "FALSE ✅") . "\n";

// Check if config is cached
echo "\n💾 Cache Status:\n";
echo "─────────────────────────────────\n";
$configCache = file_exists(base_path('bootstrap/cache/config.php'));
echo "Config Cache: " . ($configCache ? "✅ CACHED" : "❌ NOT CACHED") . "\n";
if ($configCache) {
    echo "⚠️  If you updated .env, run: php artisan config:clear && php artisan config:cache\n";
}

// Check log file
echo "\n📋 Log File:\n";
echo "─────────────────────────────────\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logSize = filesize($logFile);
    echo "Log file exists: ✅ (" . round($logSize / 1024, 2) . " KB)\n";
    echo "View recent OTP logs: tail -n 50 storage/logs/laravel.log | grep 'WhatsApp OTP'\n";
} else {
    echo "Log file: ❌ NOT FOUND\n";
}

// Network connectivity test
echo "\n🌐 Network Test:\n";
echo "─────────────────────────────────\n";
$graphUrl = config('whatsapp.graph_url', 'https://graph.facebook.com/v18.0');
$host = parse_url($graphUrl, PHP_URL_HOST);
$port = parse_url($graphUrl, PHP_URL_PORT) ?: 443;

$connection = @fsockopen($host, $port, $errno, $errstr, 5);
if ($connection) {
    echo "Connection to $host:$port: ✅ SUCCESS\n";
    fclose($connection);
} else {
    echo "Connection to $host:$port: ❌ FAILED ($errstr)\n";
    echo "⚠️  Check firewall/network settings\n";
}

echo "\n=== End of Check ===\n\n";
