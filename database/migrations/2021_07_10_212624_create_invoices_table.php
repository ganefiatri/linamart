<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->integer('member_id');
            $table->string('serie', 16);
            $table->integer('nr')->nullable(true)->default(0);
            $table->string('hash')->nullable(true);
            $table->string('currency', 4)->nullable(true)->default('IDR');
            $table->double('currency_rate', 8, 2)->nullable(true)->default(1.0);
            $table->double('credit', 18, 2)->nullable(true)->default(0.0);
            $table->double('base_income', 18, 2)->nullable(true)->default(0.0);
            $table->double('base_refund', 18, 2)->nullable(true)->default(0.0);
            $table->double('refund', 18, 2)->nullable(true)->default(0.0);
            $table->integer('shipping_id')->nullable(true)->default(0);
            $table->double('shipping_fee', 18, 2)->nullable(true)->default(0.0);
            $table->text('notes')->nullable(true);
            $table->tinyInteger('status')->nullable(true)->default(0);
            $table->string('seller_name')->nullable(true);
            $table->string('seller_phone', 32)->nullable(true);
            $table->tinyText('seller_address')->nullable(true);
            $table->tinyText('seller_city')->nullable(true);
            $table->string('buyer_name')->nullable(true);
            $table->string('buyer_phone', 32)->nullable(true);
            $table->tinyText('buyer_address')->nullable(true);
            $table->tinyText('buyer_city')->nullable(true);
            $table->string('buyer_postal_code')->nullable(true);
            $table->dateTime('due_at')->nullable(true);
            $table->dateTime('reminded_at')->nullable(true);
            $table->dateTime('paid_at')->nullable(true);
            $table->dateTime('refunded_at')->nullable(true);
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
        Schema::dropIfExists('invoices');
    }
}
