<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGobuyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gobuy_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->tinyInteger('block');
            $table->tinyInteger('sendEmail');
            $table->dateTime('registerDate');
            $table->dateTime('lastvisitDate');
            $table->text('params');
            $table->dateTime('lastResetTime');
            $table->integer('resetCount');
            $table->string('otpKey');
            $table->string('otep');
            $table->tinyInteger('requireReset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gobuy_users');
    }
}
