<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

echo "=== Single Product Image Sync Test (Robust Fallback & Correct Path) ===\n";

$product = Product::where('id', 60)->first();
if (!$product) {
    die("Product with ID 60 not found.\n");
}

echo "Testing for product: {$product->name} (Code: {$product->product_code}, Slug: {$product->slug})\n";

$query = "Kohler " . ($product->product_code ?: $product->name);
echo "Search Query: $query\n";

// Step 1: Get vqd token
$url = "https://duckduckgo.com/?q=" . urlencode($query) . "&iax=images&ia=images";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
    die("Failed to fetch DDG HTML.\n");
}

if (preg_match('/vqd=["\']([^"\']+)["\']/', $html, $matches)) {
    $vqd = $matches[1];
    echo "Found vqd token: $vqd\n";
} else {
    die("Failed to find vqd token in HTML.\n");
}

// Step 2: Fetch images JSON
$apiUrl = "https://duckduckgo.com/i.js?q=" . urlencode($query) . "&o=json&vqd=$vqd&f=,,,";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$jsonStr = curl_exec($ch);
curl_close($ch);

if (!$jsonStr) {
    die("Failed to fetch DDG JSON.\n");
}

$data = json_decode($jsonStr, true);
if (!isset($data['results']) || empty($data['results'])) {
    die("No image results found in DDG JSON.\n");
}

$results = $data['results'];
echo "Found " . count($results) . " image results.\n";

$success = false;

foreach ($results as $index => $result) {
    $imageUrl = $result['image'];
    echo "\nTrying image " . ($index + 1) . ": $imageUrl\n";

    // Skip webp since GD doesn't support it
    if (strpos(strtolower($imageUrl), '.webp') !== false) {
        echo "Skipping webp image.\n";
        continue;
    }

    // Force jpeg format if it's a scene7 URL
    if (strpos($imageUrl, 'scene7.com') !== false) {
        if (strpos($imageUrl, '?') !== false) {
            $imageUrl = str_replace('fmt=webp', 'fmt=jpeg', $imageUrl);
            if (strpos($imageUrl, 'fmt=') === false) {
                $imageUrl .= '&fmt=jpeg';
            }
        } else {
            $imageUrl .= '?fmt=jpeg';
        }
        echo "Adjusted Scene7 URL to force JPEG: $imageUrl\n";
    }

    // Download image
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $imgData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$imgData) {
        echo "Failed to download image (HTTP Code: $httpCode).\n";
        continue;
    }

    echo "Downloaded " . strlen($imgData) . " bytes. Processing...\n";

    // Process and save using Intervention Image
    try {
        $ext = 'jpg'; // default extension
        // Guess extension from URL
        $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH));
        if (isset($pathInfo['extension'])) {
            $guessed = strtolower($pathInfo['extension']);
            if (in_array($guessed, ['jpg', 'jpeg', 'png', 'gif'])) {
                $ext = $guessed;
            }
        }

        $slug = Str::slug($product->slug ?: $product->name, '-');
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $ext;

        $disk = Storage::disk('public');
        $disk->makeDirectory('product/' . $slug . '/original');
        $disk->makeDirectory('product/' . $slug . '/large');
        $disk->makeDirectory('product/' . $slug . '/base');

        // Save original using disk
        $originalPath = 'product/' . $slug . '/original/' . $fileName;
        $disk->put($originalPath, $imgData);
        
        // Correct way to resolve absolute path in Laravel
        $source = $disk->path($originalPath);
        echo "Source file exists: " . (file_exists($source) ? "YES" : "NO") . " ($source)\n";

        // Read image using Intervention Image
        $imageInstance = Image::make($source);
        
        // Large (800x600)
        $largeImage = $imageInstance->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $largePath = 'product/' . $slug . '/large/' . $fileName;
        $disk->put($largePath, (string) $largeImage->encode($ext, 92));
        echo "Saved large version to storage.\n";

        // Base (400x300)
        $baseImage = Image::make($source)->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $basePath = 'product/' . $slug . '/base/' . $fileName;
        $disk->put($basePath, (string) $baseImage->encode($ext, 92));
        echo "Saved base version to storage.\n";

        // Update product record
        $product->image = $basePath;
        $product->save();
        echo "✅ Product record updated in database! image = $basePath\n";
        $success = true;
        break; // Stop loop on success

    } catch (\Throwable $e) {
        echo "Error processing image: " . $e->getMessage() . "\n";
        // Clean up partial files
        if (isset($originalPath)) {
            $disk->delete($originalPath);
        }
    }
}

if ($success) {
    echo "Sync succeeded!\n";
} else {
    echo "Sync failed: no suitable images could be processed.\n";
}

echo "=== Test End ===\n";
