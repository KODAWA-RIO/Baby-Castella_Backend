<?php

// database/migrations/xxxx_xx_xx_create_merchandises_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandisesTable extends Migration
{
    public function up()
    {
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->string('merchandise_name', 30); // 商品名
            $table->integer('merchandise_price'); // 商品値段
            $table->integer('stock'); // 在庫
            $table->boolean('merchandise_display');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandises');
    }
}

