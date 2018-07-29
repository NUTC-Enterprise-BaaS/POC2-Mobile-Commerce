<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username');
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
            $table->string('phone');
            $table->integer('bonus');
            $table->integer('superBonus');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
