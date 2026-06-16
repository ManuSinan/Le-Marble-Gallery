<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Order extends Model
{
    protected $table = 'orders';
 
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Backwards-compatible alias for the order items relationship.
     * The checkout webhook payload loader expects `orderItems`.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statuss()
    {
        return $this->hasMany(OrderStatus::class);
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getQuotationRefAttribute()
    {
        $createdAt = \Carbon\Carbon::parse($this->created_at);
        return 'QUO-' . $createdAt->format('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
