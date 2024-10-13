<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // MerchandiseToOrderとのリレーション
    public function merchandiseToOrders(): HasMany
    {
        return $this->hasMany(MerchandiseToOrder::class);
    }

    // ToppingToOrderとのリレーション
    public function toppingToOrders(): HasMany
    {
        return $this->hasMany(ToppingToOrder::class);
    }
}

