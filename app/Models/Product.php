<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'badge',
        'sizes', 'image_url', 'active', 'stock',
    ];

    protected $casts = [
        'sizes'  => 'array',
        'active' => 'boolean',
        'price'  => 'float',
    ];

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }
}
