<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaitSendPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wait_send_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('user_phone');
            $table->string('store_id');
            $table->string('points');
            $table->string('state');
            $table->string('message');
            $table->string('created');
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
        Schema::drop('wait_send_points');
    }
}
