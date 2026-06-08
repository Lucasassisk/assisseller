<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['name', 'product_id', 'rating', 'comment', 'approved'];
    protected $casts    = ['approved' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
