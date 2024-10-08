<?php

// database/migrations/xxxx_xx_xx_create_toppings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToppingsTable extends Migration
{
    public function up()
    {
        Schema::create('toppings', function (Blueprint $table) {
            $table->id();
            $table->string('topping_name', 30); // トッピング名
            $table->integer('topping_price'); // トッピング値段
            $table->boolean('topping_display'); // 表示
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('toppings');
    }
}
