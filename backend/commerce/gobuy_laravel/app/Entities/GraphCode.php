<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GraphCode extends Model
{
    protected $fillable = ['email', 'check_id', 'verify_code', 'address'];
}
