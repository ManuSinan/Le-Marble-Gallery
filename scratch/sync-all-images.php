<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

echo "=== All Products Image Sync Script ===\n";

$products = Product::where(function($query) {
    $query->whereNull('image')->orWhere('image', '');
})->get();

$total = count($products);
echo "Found $total products without images.\n";

$disk = Storage::disk('public');

foreach ($products as $i => $product) {
    $progress = $i + 1;
    echo "\n------------------------------------------------------------\n";
    echo "[$progress/$total] Processing Product ID {$product->id}: {$product->name}\n";
    echo "Code: {$product->product_code} | Slug: {$product->slug}\n";

    $searchQuery = "Kohler ";
    if ($product->product_code) {
        $searchQuery .= $product->product_code;
    } else {
        $searchQuery .= $product->name;
    }
    
    echo "Search Query: $searchQuery\n";

    // Step 1: Get vqd token from DDG
    $url = "https://duckduckgo.com/?q=" . urlencode($searchQuery) . "&iax=images&ia=images";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        echo "⚠️ Failed to fetch DDG HTML for query: $searchQuery. Skipping.\n";
        continue;
    }

    if (!preg_match('/vqd=["\']([^"\']+)["\']/', $html, $matches)) {
        echo "⚠️ Failed to find vqd token in HTML. Skipping.\n";
        continue;
    }
    $vqd = $matches[1];

    // Step 2: Fetch images JSON
    $apiUrl = "https://duckduckgo.com/i.js?q=" . urlencode($searchQuery) . "&o=json&vqd=$vqd&f=,,,";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $jsonStr = curl_exec($ch);
    curl_close($ch);

    if (!$jsonStr) {
        echo "⚠️ Failed to fetch DDG JSON. Skipping.\n";
        continue;
    }

    $data = json_decode($jsonStr, true);
    if (!isset($data['results']) || empty($data['results'])) {
        echo "⚠️ No image results found. Skipping.\n";
        continue;
    }

    $results = $data['results'];
    echo "Found " . count($results) . " image candidates. Starting download & processing...\n";

    $slug = Str::slug($product->slug ?: $product->name, '-');
    $imagesSynced = 0;
    $updatedFields = [];

    foreach ($results as $index => $result) {
        if ($imagesSynced >= 4) {
            break; // We have enough images (main + 3 gallery)
        }

        $imageUrl = $result['image'];
        
        // Skip webp since local PHP GD lacks support
        if (strpos(strtolower($imageUrl), '.webp') !== false) {
            continue;
        }

        // Adjust Scene7 URL to force JPEG
        if (strpos($imageUrl, 'scene7.com') !== false) {
            if (strpos($imageUrl, '?') !== false) {
                $imageUrl = str_replace('fmt=webp', 'fmt=jpeg', $imageUrl);
                if (strpos($imageUrl, 'fmt=') === false) {
                    $imageUrl .= '&fmt=jpeg';
                }
            } else {
                $imageUrl .= '?fmt=jpeg';
            }
        }

        // Download
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $imgData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$imgData) {
            continue; // Fail silently, try next image candidate
        }

        // Process and save
        try {
            $ext = 'jpg';
            $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH));
            if (isset($pathInfo['extension'])) {
                $guessed = strtolower($pathInfo['extension']);
                if (in_array($guessed, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $ext = $guessed;
                }
            }

            $fileName = time() . '_' . rand(1000, 9999) . '_' . $imagesSynced . '.' . $ext;

            // Ensure directories exist
            $disk->makeDirectory('product/' . $slug . '/original');
            $disk->makeDirectory('product/' . $slug . '/large');
            $disk->makeDirectory('product/' . $slug . '/base');

            // Save original image
            $originalPath = 'product/' . $slug . '/original/' . $fileName;
            $disk->put($originalPath, $imgData);
            
            $source = $disk->path($originalPath);
            
            // Generate large and base using Intervention Image
            $largeImage = Image::make($source)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $largePath = 'product/' . $slug . '/large/' . $fileName;
            $disk->put($largePath, (string) $largeImage->encode($ext, 92));

            $baseImage = Image::make($source)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $basePath = 'product/' . $slug . '/base/' . $fileName;
            $disk->put($basePath, (string) $baseImage->encode($ext, 92));

            // Map to corresponding database column
            if ($imagesSynced === 0) {
                $product->image = $basePath;
                $updatedFields[] = "image";
            } else {
                $colName = "gallery_image_" . $imagesSynced;
                $product->$colName = $basePath;
                $updatedFields[] = $colName;
            }

            $imagesSynced++;
            echo "   ✅ Synced image $imagesSynced: $imageUrl\n";

        } catch (\Throwable $e) {
            // Clean up original file if failed
            if (isset($originalPath)) {
                $disk->delete($originalPath);
            }
        }
    }

    if ($imagesSynced > 0) {
        $product->save();
        echo "🎉 Successfully updated DB fields: " . implode(', ', $updatedFields) . "\n";
    } else {
        echo "❌ Failed to sync any images for this product.\n";
    }

    // Sleep to be nice to search engines and servers
    usleep(1500000); // 1.5 seconds sleep
}

echo "\n=== Sync Completed! ===\n";
