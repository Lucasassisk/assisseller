<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {
    protected $fillable = ['code','type','value','min_value','max_uses','used_count','expires_at','active'];
    protected $casts = ['active'=>'boolean','value'=>'float','min_value'=>'float','expires_at'=>'date'];

    public function isValid(float $cartTotal): bool {
        if (!$this->active) return false;
        if ($this->used_count >= $this->max_uses) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($cartTotal < $this->min_value) return false;
        return true;
    }

    public function discountFor(float $total): float {
        return match($this->type) {
            'percent' => round($total * $this->value / 100, 2),
            'fixed'   => min($this->value, $total),
            default   => 0,
        };
    }
}
