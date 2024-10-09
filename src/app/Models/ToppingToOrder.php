<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToppingToOrder extends Model
{
    use HasFactory;

    protected $fillable = ['topping_id', 'order_id'];

    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
