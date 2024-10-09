<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Topping;
use App\Models\Merchandise;
use App\Models\ToppingToOrder;
use App\Models\MerchandiseToOrder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ユーザーを10人作成
        User::factory(10)->create();

        // 注文を10件作成
        Order::factory(10)->create();

        // トッピングを5件作成
        Topping::factory(5)->create();

        // 商品を10件作成
        Merchandise::factory(10)->create();

        // トッピングと注文の関連データを作成
        ToppingToOrder::factory(50)->create();

        // 商品と注文の関連データを作成
        MerchandiseToOrder::factory(50)->create();
    }
}
