<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyUserProfile extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'profile_key', 'profile_value', 'ordering'];
}