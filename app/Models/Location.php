<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Location extends Model
{
    protected $table = 'locations';
 
    protected $guarded = [];


    public function state()
    {
        return $this->belongsTo(State::class);
    }

}
