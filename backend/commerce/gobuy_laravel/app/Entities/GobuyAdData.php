<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyAdData extends Model
{
    public $timestamps = false;
    protected $table = 'gobuy_ad_data';
    protected $fillable = ['created_by', 'ad_title', 'ad_body',
    	'ad_image', 'ad_created_date'
   	];
}