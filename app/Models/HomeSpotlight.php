<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSpotlight extends Model
{
    protected $table = 'home_spotlights';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

