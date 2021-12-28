<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_queues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mail_to');
            $table->string('mail_class');
            $table->text('mail_params')->nullable(true)->default(null);
            $table->tinyInteger('priority')->nullable(true)->default(1);
            $table->tinyInteger('executed')->nullable(true)->default(0);
            $table->timestamp('executed_at')->nullable(true)->default(null);
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
        Schema::dropIfExists('mail_queues');
    }
}
