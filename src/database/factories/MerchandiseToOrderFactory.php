<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Merchandise;
use App\Models\MerchandiseToOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchandiseToOrderFactory extends Factory
{
    public function definition()
    {
        // ランダムに商品と注文を選び、既存の組み合わせがないかチェック
        do {
            $merchandise_id = Merchandise::inRandomOrder()->first()->id;
            $order_id = Order::inRandomOrder()->first()->id;
        } while (MerchandiseToOrder::where('merchandise_id', $merchandise_id)->where('order_id', $order_id)->exists());

        return [
            'merchandise_id' => $merchandise_id,  // ランダムに選んだユニークな商品ID
            'order_id' => $order_id,              // ランダムに選んだユニークな注文ID
            'pieces' => $this->faker->numberBetween(1, 10), // 注文個数
        ];
    }
}
