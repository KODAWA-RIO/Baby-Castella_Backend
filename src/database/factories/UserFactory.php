<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'user_number' => $this->faker->unique()->numberBetween(100000, 999999), // 学籍番号
            'authority' => $this->faker->boolean,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // パスワード
            'remember_token' => Str::random(10),
        ];
    }
}
