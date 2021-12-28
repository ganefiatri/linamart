<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->integer('category_id');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable(true);
            $table->string('unit', 32)->nullable(true)->default('pcs');
            $table->double('weight', 18, 2)->nullable(true)->default(0.0);
            $table->double('price', 18, 2)->nullable(true)->default(0.0);
            $table->double('discount', 18, 2)->nullable(true)->default(0.0);
            $table->integer('stock')->nullable(true)->default(0);
            $table->text('related_ids')->nullable(true);
            $table->tinyInteger('active')->nullable(true)->default(0);
            $table->tinyInteger('enabled')->nullable(true)->default(1);
            $table->tinyInteger('hidden')->nullable(true)->default(0);
            $table->integer('priority')->nullable(true)->default(1);
            $table->text('meta')->nullable(true);
            $table->integer('viewed')->nullable(true)->default(0);
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
        Schema::dropIfExists('products');
    }
}
