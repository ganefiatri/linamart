<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_balances', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->double('start_balance', 18, 2)->nullable(true)->default(0.0);
            $table->double('end_balance', 18, 2)->nullable(true)->default(0.0);
            $table->integer('invoice_id')->nullable()->default(0);
            $table->dateTime('last_sync')->nullable(true);
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
        Schema::dropIfExists('member_balances');
    }
}
