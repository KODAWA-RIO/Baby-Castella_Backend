<?php
// database/migrations/xxxx_xx_xx_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer', 30); // お客様
            $table->integer('total_amount'); // 合計金額
            $table->integer('deposit_amount'); // お預かり金額
            $table->integer('change'); // お釣り
            $table->string('memo', 100)->nullable(); // メモ
            $table->integer('situation'); // 進捗状況
            $table->foreignId('user_id')->constrained('users'); // 外部キー
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
