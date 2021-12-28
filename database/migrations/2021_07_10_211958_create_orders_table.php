<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->integer('member_id');
            $table->integer('product_id');
            $table->integer('promo_id')->nullable(true)->default(0);
            $table->integer('group_id');
            $table->tinyInteger('group_master')->nullable(true)->default(0);
            $table->string('title');
            $table->string('currency', 4)->nullable(true)->default('IDR');
            $table->integer('invoice_id')->nullable(true)->default(0);
            $table->tinyInteger('quantity')->nullable(true)->default(1);
            $table->string('unit', 32)->nullable(true)->default('pcs');
            $table->double('price', 18, 2)->nullable(true)->default(0.0);
            $table->double('discount', 18, 2)->nullable(true)->default(0.0);
            $table->integer('shipping_id')->nullable(true)->default(0);
            $table->tinyInteger('status')->nullable(true)->default(0);
            $table->text('reason')->nullable(true);
            $table->text('notes')->nullable(true);
            $table->text('meta')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
