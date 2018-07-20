<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGobuyJbusinessdirectoryCompanyContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gobuy_jbusinessdirectory_company_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('companyId');
            $table->string('contact_name');
            $table->string('contact_fax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gobuy_jbusinessdirectory_company_contact');
    }
}
