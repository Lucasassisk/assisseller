<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $fillable = ['name','email','phone','cpf','address','city','state','cep'];
    public function orders() { return $this->hasMany(Order::class); }
}
