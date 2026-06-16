<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Address extends Model
{
    protected $table = 'address';
 
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }

    public function deliveryCharge()
    {
        if ($this->relationLoaded('pincode') && $this->pincode) {
            return $this->pincode->delivery_charge;
        }
        if ($this->relationLoaded('location') && $this->location) {
            return $this->location->delivery_charge;
        }
        return 0;
    }

    public function minimumCartAmount()
    {
        if ($this->relationLoaded('pincode') && $this->pincode) {
            return $this->pincode->minimum_cart_amount;
        }
        if ($this->relationLoaded('location') && $this->location) {
            return $this->location->minimum_cart_amount;
        }
        return 0;
    }
}
