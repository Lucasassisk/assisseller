<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {
    protected $table = 'gallery';
    protected $fillable = ['filename','url','product_id','size_bytes'];
    public function product() { return $this->belongsTo(Product::class); }
}
