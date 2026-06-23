<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

echo "=== Categories Image Sync Script ===\n";

$categories = Category::where(function($query) {
    $query->whereNull('image')->orWhere('image', '');
})->get();

$total = count($categories);
echo "Found $total categories without images.\n";

$disk = Storage::disk('public');

foreach ($categories as $i => $category) {
    $progress = $i + 1;
    echo "\n------------------------------------------------------------\n";
    echo "[$progress/$total] Processing Category ID {$category->id}: {$category->name}\n";
    echo "Slug: {$category->slug}\n";

    // Build search query (specifying Kohler to get relevant product/category visuals)
    $searchQuery = "Kohler " . $category->name;
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
        echo "⚠️ Failed to fetch DDG HTML. Skipping.\n";
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
    echo "Found " . count($results) . " image candidates. Starting processing...\n";

    $slug = Str::slug($category->slug ?: $category->name, '-');
    $success = false;

    foreach ($results as $index => $result) {
        $imageUrl = $result['image'];
        
        // Skip webp since GD doesn't support it
        if (strpos(strtolower($imageUrl), '.webp') !== false) {
            continue;
        }

        // Force JPEG format on Scene7 URLs
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
            continue;
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

            $fileName = time() . '_' . rand(1000, 9999) . '.' . $ext;

            // Ensure directories exist
            $disk->makeDirectory('category/' . $slug . '/original');
            $disk->makeDirectory('category/' . $slug . '/large');
            $disk->makeDirectory('category/' . $slug . '/base');

            // Save original image
            $originalPath = 'category/' . $slug . '/original/' . $fileName;
            $disk->put($originalPath, $imgData);
            
            $source = $disk->path($originalPath);
            
            // Generate large and base using Intervention Image
            // Large width = 400
            $largeImage = Image::make($source)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $largePath = 'category/' . $slug . '/large/' . $fileName;
            $disk->put($largePath, (string) $largeImage->encode($ext, 92));

            // Base width = 200
            $baseImage = Image::make($source)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $basePath = 'category/' . $slug . '/base/' . $fileName;
            $disk->put($basePath, (string) $baseImage->encode($ext, 92));

            // Update category image column
            $category->image = $basePath;
            $category->save();
            
            echo "   ✅ Successfully synced: $imageUrl\n";
            $success = true;
            break; // Stop loop on success

        } catch (\Throwable $e) {
            // Clean up original file if failed
            if (isset($originalPath)) {
                $disk->delete($originalPath);
            }
        }
    }

    if (!$success) {
        echo "❌ Failed to sync an image for this category.\n";
    }

    // Sleep to be nice to search engines and servers
    usleep(1500000); // 1.5 seconds sleep
}

echo "\n=== Category Sync Completed! ===\n";
