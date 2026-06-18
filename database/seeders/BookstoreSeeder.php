<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\BannerSlider;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Location;
use App\Models\Option;
use App\Models\Product;
use App\Models\State;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BookstoreSeeder extends Seeder
{
    public function run()
    {
        $this->seedOptions();
        $this->seedStatesAndLocations();
        $this->seedUnits();
        $this->seedAttribute();
        // $this->seedCategories();
        // $this->seedOrigins();
        // $this->seedBannerSliders();
        // $this->seedProducts();
    }

    protected function seedStatesAndLocations()
    {
        $state = State::firstOrCreate(['name' => 'Default'], ['local_name' => 'Default']);

        Location::firstOrCreate(
            ['name' => 'All Areas'],
            [
                'state_id' => $state->id,
                'local_name' => 'All Areas',
                'minimum_cart_amount' => 0,
                'delivery_charge' => 0,
                'delivery_cart_amount' => 0,
            ]
        );
    }

    protected function seedOptions()
    {
        $storeName = "Lee Marble Gallery";

        $options = [
            'website_meta_title' => $storeName . ' — Premium Stone & Interior Solutions',
            'website_meta_description' => 'Quotation Management System for natural marble, granite, quartz and stones.',
            'website_meta_keywords' => 'marble, granite, quartz, stones, luxury interiors, stone gallery',
            'shop_meta_title' => 'Browse Collections — ' . $storeName,
            'shop_meta_description' => 'Select premium stones and get professional custom-cut quotes.',
            'shop_meta_keywords' => 'italian marble, black granite, white quartz, luxury tiles',
            'store_address' => 'Luxury Stone Plaza, Gallery Row, Building 1',
            'store_country' => 'India',
            'order_enquiry_number' => '+91 99999 88888',
            'shipping_time_text' => '3-5 business days',
            'about_us_title' => 'About ' . $storeName,
            'about_us_content' => '<p>' . $storeName . ' is a premier stone gallery specializing in fine Italian marble, granite, quartz, and bespoke interior solutions.</p><p>We provide architects, designers, and developers with an interactive client quote-building system tailored for custom-fit stone layouts.</p>',
            'tc_title' => 'Terms and Conditions',
            'tc_content' => '<p>All quotations are valid for 30 days from creation.</p><p>Actual slab textures and vein patterns may vary from display samples.</p>',
            'privacy_policy_title' => 'Privacy Policy',
            'privacy_policy_content' => '<p>Your privacy is important. Customer and client records are stored securely for quotation and layout generation purposes only.</p>',
            'safety_tips_title' => 'Handling & Care Tips',
            'safety_tips_content' => '<ul><li>Seal natural marble regularly to prevent stain absorption.</li><li>Avoid acid-based cleaners on polished marble surfaces.</li><li>Verify layout drawings before confirming custom cuts.</li></ul>',
            'import_updates_title' => 'Gallery News',
            'import_updates_content' => '<p>New shipments of Carrara and Calacatta gold slabs have arrived in our yard.</p>',
        ];

        foreach ($options as $key => $value) {
            Option::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    protected function seedUnits()
    {
        Unit::updateOrCreate(
            ['id' => 1],
            ['type' => 'Area', 'local_type' => 'Area', 'name' => 'Sq.Ft', 'local_name' => 'Sq.Ft', 'stepper' => 1]
        );
    }

    protected function seedAttribute()
    {
        Attribute::firstOrCreate(
            ['id' => 1],
            ['name' => 'Finish']
        );
    }

    protected function seedCategories()
    {
        // Parent Collections
        $collections = [
            'Marble Collection' => [
                'priority' => 30,
                'subs' => [
                    'Italian Marble' => 10,
                    'Indian Marble' => 9,
                    'Imported Marble' => 8,
                ]
            ],
            'Granite Collection' => [
                'priority' => 20,
                'subs' => [
                    'Granite Slabs' => 10,
                ]
            ],
            'Quartz & Tiles' => [
                'priority' => 10,
                'subs' => [
                    'Premium Quartz' => 10,
                ]
            ]
        ];

        foreach ($collections as $parentName => $data) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                [
                    'name' => $parentName,
                    'local_name' => $parentName,
                    'image' => null,
                    'priority' => $data['priority'],
                    'parent_id' => null,
                ]
            );

            foreach ($data['subs'] as $subName => $priority) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($subName)],
                    [
                        'name' => $subName,
                        'local_name' => $subName,
                        'image' => null,
                        'priority' => $priority,
                        'parent_id' => $parent->id,
                    ]
                );
            }
        }
    }

    protected function seedOrigins()
    {
        $origins = [
            ['name' => 'Carrara Quarries', 'slug' => 'carrara-quarries'],
            ['name' => 'Rajasthan Mines', 'slug' => 'rajasthan-mines'],
            ['name' => 'Calacatta Valleys', 'slug' => 'calacatta-valleys'],
            ['name' => 'Bespoke Quartz Co.', 'slug' => 'bespoke-quartz-co'],
        ];

        foreach ($origins as $origin) {
            Brand::firstOrCreate(
                ['slug' => $origin['slug']],
                array_merge($origin, ['priority' => 1])
            );
        }
    }

    protected function seedBannerSliders()
    {
        $bannerPath = $this->createPlaceholderBanner();
        $storeName = "Lee Marble Gallery";

        if ($bannerPath) {
            BannerSlider::updateOrCreate(
                ['name' => 'Welcome to ' . $storeName],
                ['image' => $bannerPath, 'priority' => 10]
            );
        }
    }

    protected function createPlaceholderBanner(): ?string
    {
        try {
            $dir = 'banner/seed';
            $baseDir = public_path('uploads/' . $dir);
            $baseDirBase = $baseDir . '/base';
            $baseDirLarge = $baseDir . '/large';

            if (!File::isDirectory($baseDirBase)) {
                File::makeDirectory($baseDirBase, 0755, true);
            }

            if (!File::isDirectory($baseDirLarge)) {
                File::makeDirectory($baseDirLarge, 0755, true);
            }

            $placeholder = public_path('assets/frontend/images/logo.png');
            if (!File::exists($placeholder)) {
                $placeholder = public_path('assets/frontend/images/logo.svg');
            }

            if (!File::exists($placeholder)) {
                return null;
            }

            $fileName = 'banner-placeholder.png';
            $destBase = $baseDirBase . '/' . $fileName;
            $destLarge = $baseDirLarge . '/' . $fileName;

            File::copy($placeholder, $destBase);
            File::copy($placeholder, $destLarge);

            return $dir . '/base/' . $fileName;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function seedProducts()
    {
        // Deactivate old book products
        Product::where('product_code', 'like', 'KNM-%')->update(['status' => 'draft']);

        $materials = [
            'Italian Marble' => [
                ['name' => 'ITALIAN STATUARIO MARBLE', 'price' => 950, 'selling_price' => 850, 'stock' => 1200],
                ['name' => 'CARRARA WHITE MARBLE', 'price' => 600, 'selling_price' => 550, 'stock' => 1500],
                ['name' => 'CALACATTA GOLD MARBLE', 'price' => 1300, 'selling_price' => 1100, 'stock' => 800],
            ],
            'Indian Marble' => [
                ['name' => 'MAKRANA WHITE MARBLE', 'price' => 500, 'selling_price' => 450, 'stock' => 2000],
                ['name' => 'AMBAJI WHITE MARBLE', 'price' => 300, 'selling_price' => 250, 'stock' => 3000],
            ],
            'Granite Slabs' => [
                ['name' => 'POLISHED BLACK GRANITE', 'price' => 250, 'selling_price' => 220, 'stock' => 4000],
                ['name' => 'GREY GALAXY GRANITE', 'price' => 180, 'selling_price' => 160, 'stock' => 5000],
            ],
            'Premium Quartz' => [
                ['name' => 'WHITE QUARTZ COUNTERTOP', 'price' => 400, 'selling_price' => 380, 'stock' => 1000],
            ]
        ];

        $unit = Unit::first();
        $brand = Brand::first();
        $seq = 1;

        foreach ($materials as $subCatName => $items) {
            $category = Category::where('name', $subCatName)->first();
            if (!$category) continue;

            foreach ($items as $item) {
                $slug = Str::slug($item['name']);
                $productCode = 'LMG-' . str_pad((string) $seq, 5, '0', STR_PAD_LEFT);
                $imagePath = $this->generateSeedCoverImages($slug, $subCatName, $item['name']);

                Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $item['name'],
                        'local_name' => $item['name'],
                        'description' => 'Premium luxury stone collection suitable for countertops, flooring, and wall cladding.',
                        'local_description' => 'Premium luxury stone collection suitable for countertops, flooring, and wall cladding.',
                        'category_id' => $category->id,
                        'brand_id' => $brand?->id,
                        'attribute_id' => null,
                        'unit_id' => $unit->id,
                        'product_code' => $productCode,
                        'stock_status' => 'limited',
                        'stock_available' => $item['stock'],
                        'minimum_quantity' => 1,
                        'price' => $item['price'],
                        'selling_price' => $item['selling_price'],
                        'priority' => 3,
                        'status' => 'published',
                        'image' => $imagePath,
                        'keywords' => implode(', ', [$subCatName, $item['name'], 'stone', 'marble']),
                    ]
                );

                $seq++;
            }
        }
    }

    protected function generateSeedCoverImages(string $slug, string $classLabel, string $title): ?string
    {
        $dir = 'product/seed/' . $slug;
        $baseDir = public_path('uploads/' . $dir . '/base');
        $largeDir = public_path('uploads/' . $dir . '/large');

        try {
            if (!File::isDirectory($baseDir)) File::makeDirectory($baseDir, 0755, true);
            if (!File::isDirectory($largeDir)) File::makeDirectory($largeDir, 0755, true);
        } catch (\Throwable $e) {
            return null;
        }

        $fileName = 'cover.png';
        $basePath = $baseDir . '/' . $fileName;
        $largePath = $largeDir . '/' . $fileName;

        $baseOk = $this->renderCoverPng($basePath, 400, 300, $classLabel, $title, $slug);
        $largeOk = $this->renderCoverPng($largePath, 800, 600, $classLabel, $title, $slug);

        if (!$baseOk || !$largeOk) {
            return null;
        }

        return $dir . '/base/' . $fileName;
    }

    protected function renderCoverPng(string $dest, int $w, int $h, string $classLabel, string $title, string $slug): bool
    {
        if (!function_exists('imagecreatetruecolor')) {
            return false;
        }

        $im = imagecreatetruecolor($w, $h);
        if (!$im) return false;

        // Elegant light grey / stone textured background
        $bg = imagecolorallocate($im, 240, 240, 240);
        $fg = imagecolorallocate($im, 31, 41, 55); // Deep Charcoal
        $accent = imagecolorallocate($im, 212, 175, 55); // Gold

        imagefilledrectangle($im, 0, 0, $w, $h, $bg);

        // draw border
        imagerectangle($im, 10, 10, $w - 10, $h - 10, $accent);

        imagestring($im, 4, 20, 20, strtoupper($classLabel), $accent);
        imagestring($im, 5, 20, 50, substr($title, 0, 34), $fg);
        imagestring($im, 3, 20, $h - 35, 'Lee Marble Gallery', $fg);

        $ok = imagepng($im, $dest);
        imagedestroy($im);

        return (bool) $ok;
    }
}
