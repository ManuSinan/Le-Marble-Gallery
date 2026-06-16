<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincodes';

    protected $guarded = [];

    protected $casts = [
        'minimum_cart_amount' => 'float',
        'delivery_charge' => 'float',
        'delivery_cart_amount' => 'float',
    ];
}
