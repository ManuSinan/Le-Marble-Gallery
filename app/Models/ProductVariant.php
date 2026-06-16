<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    public $timestamps = false;
 
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantOption()
    {
        return $this->belongsTo(VariantOption::class);
    }

}
