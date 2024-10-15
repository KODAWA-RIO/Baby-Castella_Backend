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

    // 削除イベントに対する処理
    protected static function booted()
    {
        static::deleting(function ($order) {
            // 関連する商品の在庫を戻す
            foreach ($order->merchandiseToOrders as $merchandiseToOrder) {
                $merchandise = $merchandiseToOrder->merchandise;
                $merchandise->stock += $merchandiseToOrder->pieces;
                $merchandise->save();
            }
        });
    }
}
