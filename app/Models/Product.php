<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class Product extends Model
{
    protected $table = 'products';
 
    protected $guarded = [];

    /**
     * Products that should be visible in storefront.
     * Historically the codebase used both "active" and "published".
     */
    public function scopeVisible($query)
    {
        return $query->whereIn($this->getTable() . '.status', ['published', 'active']);
    }

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productGroupVariant( $variant_id )
    {
        $productVariants = $this->productVariant()->where('variant_id', '!=', $variant_id)->get();
 
        $query = ProductVariant::leftJoin('products', 'products.id', '=', 'product_variants.product_id');
 
        // $query->where(function($query){
        //     $query->orWhere(function($query){
        //         $query->where('products.stock_status', 'limited');
        //         $query->whereColumn('products.stock_available', '>=', 'products.minimum_quantity');
        //         $query->where('products.stock_available', '>', 0);
        //     });

        //     $query->orWhere(function($query){
        //         $query->where('products.stock_status', 'unlimited');
        //     });
        // });

        $query->whereIn('products.status', ['published', 'active']);


        $query->where('product_variants.combination_key', $this->combination_key);

        $query->where( function($query) use($productVariants) {
            foreach($productVariants as $productVariant){
                $query->orWhere( function($query) use($productVariant) {
                    $query->where('product_variants.variant_id', $productVariant->variant_id);
                    $query->where('product_variants.variant_option_id', $productVariant->variant_option_id);
                });
            }
        });
  
        $products = $query->get();
 

        $productCounts = $products->groupBy('product_id')->map(function ($row) {
            return $row->count();
        });


        $query = ProductVariant::where('product_variants.combination_key', $this->combination_key);
        $query->leftJoin('products', 'products.id', '=', 'product_variants.product_id');
        $query->where('product_variants.variant_id', $variant_id );
        
        // $query->where(function($query){
        //     $query->orWhere(function($query){
        //         $query->where('products.stock_status', 'limited');
        //         $query->whereColumn('products.stock_available', '>=', 'products.minimum_quantity');
        //         $query->where('products.stock_available', '>', 0);
        //     });

        //     $query->orWhere(function($query){
        //         $query->where('products.stock_status', 'unlimited');
        //     });
        // });

        $query->whereIn('products.status', ['published', 'active']);
  
        $query->where( function($query) use ($productCounts, $productVariants) {
            foreach($productCounts as $product => $count){
                if($count == $productVariants->count()){
                    $query->orWhere('product_id', $product);
                }   
            }
        });
 
        return $query;
    }
    
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
 
    public static function retrieve($sortby = 'featured', $search = '', $categoryId = null, $brandId = null, $limit = 12, $filters = [])
    {
        // $query = Product::where(function($query){
        //     $query->orWhere(function($query){
        //         $query->where('stock_status', 'limited');
        //         $query->whereColumn('stock_available', '>=', 'minimum_quantity');
        //         $query->where('stock_available', '>', 0);
        //     });
 
        //     $query->orWhere(function($query){
        //         $query->where('stock_status', 'unlimited');
        //     });
        // });
 
        // $query->where('status', 'published');

        $query = Product::query()->visible();

        // Handle sorting
        if ($sortby == 'featured') {            
            $query->orderBy('priority', 'DESC');
        }
        
        if ($sortby == 'price-low-to-high') {            
            $query->orderBy('selling_price', 'ASC');
        }
        
        if ($sortby == 'price-high-to-low') {            
            $query->orderBy('selling_price', 'DESC');
        }
        
        // Default sorting
        $query->orderBy('name', 'ASC');
        
        // Filter by category and its children
        if ($categoryId) {
            $category = Category::find($categoryId);

            if ($category) {
                // Get all child category IDs, including the current one
                $categoryIds = collect([$category->id])
                    ->merge($category->children()->pluck('id'))
                    ->toArray();

                $query->whereIn('category_id', $categoryIds);
            }
        }
        
        // Filter by brand
        if ($brandId) {
            $query->where('brand_id', $brandId);
        } elseif (!empty($filters['brands'])) {
            $query->whereIn('brand_id', $filters['brands']);
        }

        // Filter by price ranges
        if (!empty($filters['price_ranges'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['price_ranges'] as $range) {
                    $parts = explode('-', $range);
                    if (count($parts) === 2) {
                        $q->orWhereBetween('selling_price', [(float)$parts[0], (float)$parts[1]]);
                    } elseif (strpos($range, '+') !== false) {
                        $minPrice = (float)str_replace('+', '', $range);
                        $q->orWhere('selling_price', '>=', $minPrice);
                    }
                }
            });
        }
        
        // Basic search
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'like', '%' . $search . '%')
                      ->orWhere('local_name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('local_description', 'like', '%' . $search . '%')
                      ->orWhere('keywords', 'like', '%' . $search . '%');
            });
        }

        // Exclude products
        if (!empty($filters['exclude'])) {
            $query->whereNotIn('products.id', $filters['exclude']);
        }
        
        // Paginate results
        $products = $query->paginate($limit);
        
        // Append sorting and search parameters to pagination links
        if ($products) {
            $products->appends([
                'sortby' => $sortby,
                'search' => $search,
                'category_id' => $categoryId,
                'brands' => $filters['brands'] ?? [],
                'price_ranges' => $filters['price_ranges'] ?? [],
                'exclude' => $filters['exclude'] ?? []
            ]);
        }
        
        return $products;
    }

    public static function featured()
    {
        return Product::where(function($query){
            $query->orWhere(function($query){
                $query->where('stock_status', 'limited');
                $query->whereColumn('stock_available', '>=', 'minimum_quantity');
                $query->where('stock_available', '>', 0);
            });

            $query->orWhere(function($query){
                $query->where('stock_status', 'unlimited');
            });

        })->where(function ($query) {
            $query->where('priority', '3');
            $query->whereIn('status', ['published', 'active']);
        })
        ->orderBy('priority', 'DESC')
        ->inRandomOrder()
        ->take(6)
        ->get();
    }


    public static function outOfStock()
    {
        return Product::where(function ($query) {
            $query->where('stock_status', 'limited');
            $query->whereColumn('stock_available', '<', 'minimum_quantity');
            // $query->where('stock_available', '<=', 0);
            $query->whereIn('status', ['published', 'active']);
        })
        ->get();
    }

 
    public static function offer()
    {
        return Product::where(function($query){
            $query->orWhere(function($query){
                $query->where('stock_status', 'limited');
                $query->whereColumn('stock_available', '>=', 'minimum_quantity');
                $query->where('stock_available', '>', 0);
            });

            $query->orWhere(function($query){
                $query->where('stock_status', 'unlimited');
            });
            
        })->where(function ($query) {
            $query->whereColumn('price', '>', 'selling_price');
            $query->whereIn('status', ['published', 'active']);
        })
        ->orderBy('priority', 'DESC')
        ->orderByRaw('(price - selling_price) DESC')
        ->take(12)
        ->get();
    }

    /**
     * High-priority products (High or Featured) for Special Products section.
     * Excludes given IDs (e.g. offer product IDs) to avoid duplicates.
     */
    public static function priorityProducts(array $excludeIds = [], $limit = 12)
    {
        $query = Product::where(function ($query) {
            $query->orWhere(function ($query) {
                $query->where('stock_status', 'limited');
                $query->whereColumn('stock_available', '>=', 'minimum_quantity');
                $query->where('stock_available', '>', 0);
            });
            $query->orWhere(function ($query) {
                $query->where('stock_status', 'unlimited');
            });
        })
            ->whereIn('status', ['published', 'active'])
            ->whereIn('priority', [2, 3]) // High = 2, Featured = 3
            ->orderBy('priority', 'DESC')
            ->orderByRaw('(price - selling_price) DESC');

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->take($limit)->get();
    }
 
    public static function favourite($user_id)
    {
        return Favourite::select('products.id', 'products.name', 'products.local_name', 'products.image', 'products.minimum_quantity', 'products.selling_price', 'products.price', 'units.type as units_type', 'units.local_type as units_local_type', 'units.stepper as units_stepper', 'units.name as units_name', 'units.local_name as units_local_name','products.slug')
        ->leftJoin('products', 'products.id', '=', 'favourites.product_id')
        ->leftJoin('units', 'units.id', '=', 'products.unit_id')
        ->where(function ($query) use ($user_id){
            $query->whereIn('products.status', ['published', 'active']);
            $query->where('favourites.user_id', $user_id);
        })
        ->orderBy('products.priority', 'DESC')
        ->orderByRaw('(products.price - products.selling_price) DESC')
        ->get();
    }
 
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function hasVariantOption($variantId, $variantOptionId )
    {
        if(ProductVariant::where('product_id', $this->id)->where('variant_id', $variantId)->where('variant_option_id', $variantOptionId)->count() >= 1){
            return true;
        }
        return false;
    }

    public function intraStateTax()
    {
        return $this->hasMany(ProductTax::class)->where('type', 'intra_state_tax');
    }

    public function interStateTax()
    {
        return $this->hasMany(ProductTax::class)->where('type', 'inter_state_tax');
    }

    public function getRelatedProducts()
    {
        // Retrieve related keywords for the current product
        $relatedKeywords = getOption('product_' . $this->id . '_related');
    
        if (!$relatedKeywords) {
            return collect([]); // Return empty collection if no related keywords
        }
    
        // Split keywords by comma and trim whitespace, remove duplicates
        $keywords = array_filter(array_map('trim', explode(',', $relatedKeywords)));
    
        if (empty($keywords)) {
            return collect([]); // Return empty collection if no valid keywords
        }
    
        // Query for related products
        $query = Product::query()->visible()
            ->where('id', '!=', $this->id) // Exclude the current product
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('name', 'like', '%' . $keyword . '%')
                          ->orWhere('keywords', 'like', '%' . $keyword . '%');
                }
            });
    
        // Randomize the products and limit to 12
        return $query->inRandomOrder()->take(12)->get();
    }
    
    public function getFbtProducts()
    {
        // Retrieve FBT keywords for the current product
        $fbtKeywords = getOption('product_' . $this->id . '_fbt');
    
        if (!$fbtKeywords) {
            return collect([]); // Return empty collection if no FBT keywords
        }
    
        // Split keywords by comma and trim whitespace, remove duplicates
        $keywords = array_filter(array_map('trim', explode(',', $fbtKeywords)));
    
        if (empty($keywords)) {
            return collect([]); // Return empty collection if no valid keywords
        }
    
        // Query for FBT products
        $query = Product::query()->visible()
            ->where('id', '!=', $this->id) // Exclude the current product
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('name', 'like', '%' . $keyword . '%')
                          ->orWhere('keywords', 'like', '%' . $keyword . '%');
                }
            });
    
        // Randomize the products and limit to 12
        return $query->inRandomOrder()->take(12)->get();
    } 
}
