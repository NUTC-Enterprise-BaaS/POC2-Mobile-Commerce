<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTransferPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transfer_points', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('point');
            $table->string('check_id');
            $table->string('receive_phone');
            $table->string('receive_email');
            $table->string('send_id');
            $table->string('send_email');
            $table->string('state');
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
        Schema::drop('user_transfer_points');
    }
}
