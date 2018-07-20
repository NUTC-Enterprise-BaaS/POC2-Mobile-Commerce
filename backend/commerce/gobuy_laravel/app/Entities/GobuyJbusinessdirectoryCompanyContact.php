<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyJbusinessdirectoryCompanyContact extends Model
{
	public $timestamps = false;
    protected $table = 'gobuy_jbusinessdirectory_company_contact';
    protected $fillable = ['companyId', 'contact_name', 'contact_fax',
    					   'contact_email', 'contact_phone'
   	];
}
