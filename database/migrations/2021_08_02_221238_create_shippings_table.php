<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('distance_from')->nullable()->default(0);
            $table->integer('distance_to')->nullable()->default(0);
            $table->double('cost', 18, 2)->nullable(true)->default(0.0);
            $table->text('description')->nullable(true);
            $table->text('meta')->nullable(true);
            $table->tinyInteger('enabled')->nullable(true)->default(1);
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
        Schema::dropIfExists('shippings');
    }
}
