<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class LuckyForm extends Model
{
    protected $fillable = ['user_id', 'token', 'money', 'state'];
}