<?php

// database/migrations/xxxx_xx_xx_create_merchandise_to_order_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandiseToOrderTable extends Migration
{
    public function up()
    {
        Schema::create('merchandise_to_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchandise_id')->constrained('merchandises'); // 外部キー
            $table->foreignId('order_id')->constrained('orders'); // 外部キー
            $table->integer('pieces'); // 注文個数
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandise_to_orders');
    }
}
