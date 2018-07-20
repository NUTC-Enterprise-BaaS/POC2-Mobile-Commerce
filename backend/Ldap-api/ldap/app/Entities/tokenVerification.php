<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class tokenVerification extends Model
{
    protected $fillable = ['id', 'verify_code', 'token', 'created_at'];
    protected $table = 'tokenVerification';
}
