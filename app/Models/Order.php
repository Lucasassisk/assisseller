<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id', 'customer_name', 'customer_email',
        'customer_phone', 'items', 'subtotal', 'discount', 'shipping', 'total',
        'coupon_code', 'payment_method', 'payment_status', 'order_status',
        'stripe_pi_id', 'tracking_code', 'shipping_address',
    ];

    protected $casts = [
        'items'            => 'array',
        'shipping_address' => 'array',
        'subtotal'         => 'float',
        'discount'         => 'float',
        'shipping'         => 'float',
        'total'            => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateNumber(): string
    {
        $last = static::max('id') ?? 0;
        return 'ASL-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->order_status) {
            'pending'   => 'Pendente',
            'paid'      => 'Pago',
            'preparing' => 'Preparando',
            'shipped'   => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            default     => ucfirst($this->order_status),
        };
    }
}
