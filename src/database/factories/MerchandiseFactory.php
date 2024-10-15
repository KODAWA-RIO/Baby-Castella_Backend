<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MerchandiseFactory extends Factory
{
    public function definition()
    {
        return [
            'merchandise_name' => $this->faker->word, // 商品名
            'merchandise_price' => $this->faker->numberBetween(500, 5000), // 商品値段
            'stock' => $this->faker->numberBetween(1, 100), // 在庫
        ];
    }
}
