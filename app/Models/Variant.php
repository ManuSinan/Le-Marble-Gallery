<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Variant extends Model
{
    protected $table = 'variants';
 
    protected $guarded = [];

    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    
}
