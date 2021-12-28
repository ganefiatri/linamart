<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id', 32)->nullable(true)->default(null);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('group_id')->nullable(true)->default(0);
            $table->string('phone', 32)->nullable(true);
            $table->integer('district_id')->nullable(true)->default(0);
            $table->tinyText('district_name')->nullable(true);
            $table->tinyText('address')->nullable(true);
            $table->string('postal_code')->nullable(true);
            $table->tinyInteger('gender')->nullable(true)->default(1);
            $table->string('currency', 4)->nullable(true)->default('IDR');
            $table->string('lang', 4)->nullable(true)->default('ID');
            $table->text('notes')->nullable(true);
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
        Schema::dropIfExists('members');
    }
}
