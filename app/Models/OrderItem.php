<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = ['order_id','product_id','product_name','size','quantity','unit_price','total_price'];
    protected $casts = ['unit_price'=>'float','total_price'=>'float'];
    public function product() { return $this->belongsTo(Product::class); }
    public function order() { return $this->belongsTo(Order::class); }
}
