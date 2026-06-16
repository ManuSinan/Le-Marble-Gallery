<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Attribute extends Model
{
    protected $table = 'attributes';
 
    protected $guarded = [];

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'attribute_variants');
    }

}
