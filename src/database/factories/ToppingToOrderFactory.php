<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Topping;
use App\Models\ToppingToOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToppingToOrderFactory extends Factory
{
    public function definition()
    {
        // ランダムにトッピングと注文を選び、既存の組み合わせがないかチェック
        do {
            $topping_id = Topping::inRandomOrder()->first()->id;
            $order_id = Order::inRandomOrder()->first()->id;
        } while (ToppingToOrder::where('topping_id', $topping_id)->where('order_id', $order_id)->exists());

        return [
            'topping_id' => $topping_id,  // ランダムに選んだユニークなトッピングID
            'order_id' => $order_id,      // ランダムに選んだユニークな注文ID
        ];
    }
}
