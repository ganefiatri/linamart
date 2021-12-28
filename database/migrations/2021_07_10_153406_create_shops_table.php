<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id')->nullable(true)->default(0);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('phone', 32)->nullable(true);
            $table->integer('district_id')->nullable(true)->default(0);
            $table->tinyText('district_name')->nullable(true);
            $table->tinyText('address')->nullable(true);
            $table->string('postal_code')->nullable(true);
            $table->text('meta')->nullable(true);
            $table->tinyInteger('status')->nullable(true)->default(0);
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
        Schema::dropIfExists('shops');
    }
}
