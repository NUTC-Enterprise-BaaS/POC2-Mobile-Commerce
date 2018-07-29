<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyUserUsergroupMap extends Model
{
	public $timestamps = false;
    protected $table = 'gobuy_user_usergroup_map';
    protected $fillable = ['user_id', 'group_id'];
}
