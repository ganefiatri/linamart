<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRequestsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->string('type');
            $table->tinyInteger('success')->nullable(true)->default(0);
            $table->text('exception')->nullable(true);
            $table->text('meta')->nullable(true);
            $table->timestamp('failed_at')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_requests_tabel');
    }
}
