<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        // 合計金額をランダムに生成
        $total_amount = $this->faker->numberBetween(1000, 10000);

        // 預かり金額を合計金額以上に設定
        $deposit_amount = $this->faker->numberBetween($total_amount, $total_amount + 5000);

        // お釣りを計算
        $change = $deposit_amount - $total_amount;

        return [
            'customer' => $this->faker->name,
            'total_amount' => $total_amount, // 合計金額
            'deposit_amount' => $deposit_amount, // 預かり金額 (合計金額以上)
            'change' => $change, // お釣り (預かり金額 - 合計金額)
            'memo' => $this->faker->optional()->sentence, // メモ
            'situation' => $this->faker->numberBetween(1, 5), // 進捗状況
            'user_id' => \App\Models\User::factory(), // ユーザーID
        ];
    }
}
