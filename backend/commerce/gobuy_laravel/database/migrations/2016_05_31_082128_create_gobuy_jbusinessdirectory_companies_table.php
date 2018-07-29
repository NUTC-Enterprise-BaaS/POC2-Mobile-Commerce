<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGobuyJbusinessdirectoryCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gobuy_jbusinessdirectory_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('registrationCode');
            $table->Integer('typeId');
            $table->string('userId');
            $table->string('address');
            $table->string('website');
            $table->string('csv_password');
            $table->dateTime('creationDate');
            $table->dateTime('modified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gobuy_jbusinessdirectory_companies');
    }
}
