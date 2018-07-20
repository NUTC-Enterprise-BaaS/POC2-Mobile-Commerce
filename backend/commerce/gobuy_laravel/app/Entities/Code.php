<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = ['email', 'verify_code'];
}
