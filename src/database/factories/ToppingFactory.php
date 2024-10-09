<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ToppingFactory extends Factory
{
    public function definition()
    {
        return [
            'topping_name' => $this->faker->word,
            'topping_price' => $this->faker->numberBetween(100, 500), // トッピング値段
        ];
    }
}
