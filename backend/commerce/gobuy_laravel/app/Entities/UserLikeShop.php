<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class UserLikeShop extends Model
{
    protected $fillable = ['company_id', 'user_id'];
}
