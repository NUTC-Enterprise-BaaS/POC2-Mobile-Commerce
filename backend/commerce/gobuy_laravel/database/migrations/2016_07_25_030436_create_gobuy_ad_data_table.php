<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGobuyAdDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gobuy_ad_data', function (Blueprint $table) {
            $table->Integer('created_by');
            $table->string('string');
            $table->string('ad_body');
            $table->string('ad_image');
            $table->dateTime('ad_created_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gobuy_ad_data');
    }
}
