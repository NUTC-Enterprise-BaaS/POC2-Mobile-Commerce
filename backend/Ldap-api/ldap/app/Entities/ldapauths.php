<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ldapauths extends Model
{
    protected $fillable = ['id', 'stor', 'user', 'bc_account', 'token'];
    protected $table = 'ldapauths';
}
