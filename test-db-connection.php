<?php
/**
 * Database Connection Test Script
 * 
 * Run this on your live server to test if the database password is correct:
 * php test-db-connection.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== Database Connection Test ===\n\n";

// Get database config
$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$database = env('DB_DATABASE', '');
$username = env('DB_USERNAME', 'root');
$password = env('DB_PASSWORD', '');

echo "Database Configuration:\n";
echo "─────────────────────────────────\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $database\n";
echo "Username: $username\n";
echo "Password: " . (strlen($password) > 0 ? str_repeat('*', min(strlen($password), 10)) . " (" . strlen($password) . " chars)" : "NOT SET") . "\n";

echo "\nTesting Connection:\n";
echo "─────────────────────────────────\n";

try {
    // Test using Laravel's DB facade
    $pdo = DB::connection()->getPdo();
    echo "✅ Laravel DB Connection: SUCCESS\n";
    
    // Test query
    $result = DB::select('SELECT VERSION() as version');
    echo "✅ Database Query: SUCCESS\n";
    echo "   MySQL Version: " . $result[0]->version . "\n";
    
    // Test database exists
    $databases = DB::select('SHOW DATABASES LIKE ?', [$database]);
    if (!empty($databases)) {
        echo "✅ Database '$database' exists\n";
    } else {
        echo "❌ Database '$database' does NOT exist\n";
    }
    
    // Test tables
    $tables = DB::select('SHOW TABLES');
    echo "✅ Tables found: " . count($tables) . "\n";
    
} catch (\PDOException $e) {
    echo "❌ Database Connection FAILED\n";
    echo "   Error Code: " . $e->getCode() . "\n";
    echo "   Error Message: " . $e->getMessage() . "\n";
    
    // Common error messages
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "\n⚠️  ACCESS DENIED - Password or username is incorrect!\n";
        echo "   Please verify:\n";
        echo "   - DB_USERNAME in .env\n";
        echo "   - DB_PASSWORD in .env\n";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "\n⚠️  DATABASE NOT FOUND - Database name is incorrect!\n";
        echo "   Please verify DB_DATABASE in .env\n";
    } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "\n⚠️  CONNECTION REFUSED - MySQL server is not running or wrong host/port!\n";
        echo "   Please verify:\n";
        echo "   - DB_HOST in .env\n";
        echo "   - DB_PORT in .env\n";
        echo "   - MySQL service is running\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Unexpected Error: " . $e->getMessage() . "\n";
}

echo "\n=== End of Test ===\n\n";
