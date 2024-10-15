<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchandiseToOrder extends Model
{
    use HasFactory;

    protected $fillable = ['merchandise_id', 'order_id', 'pieces'];

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
