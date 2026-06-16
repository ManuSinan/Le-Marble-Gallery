<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    protected $table = 'product_taxs';

    public $timestamps = false;
 
    protected $guarded = [];

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

}
