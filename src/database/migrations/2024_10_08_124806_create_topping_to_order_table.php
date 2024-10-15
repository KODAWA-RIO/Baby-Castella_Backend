<?php

// database/migrations/xxxx_xx_xx_create_topping_to_order_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToppingToOrderTable extends Migration
{
    public function up()
    {
        Schema::create('topping_to_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topping_id')
                  ->constrained('toppings')
                  ->onDelete('cascade'); // 親の削除時に自動で削除
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade'); // 親の削除時に自動で削除
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('topping_to_orders');
    }
}

