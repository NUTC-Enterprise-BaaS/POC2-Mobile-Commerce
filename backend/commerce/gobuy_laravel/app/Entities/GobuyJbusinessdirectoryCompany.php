<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyJbusinessdirectoryCompany extends Model
{
	public $timestamps = false;
    protected $fillable = ['name', 'registrationCode', 'typeId', 'address', 'website',
    					   'creationDate', 'modified', 'userId', 'featured', 'phone',
    					   'countryId', 'csv_password', 'group', 'shop_class', 'logoLocation', 'alias'
    ];
}