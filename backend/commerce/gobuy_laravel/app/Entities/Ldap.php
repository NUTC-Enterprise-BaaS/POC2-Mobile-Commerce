<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Ldap extends Model
{
    protected $table = 'ldap';
    protected $fillable = ['id', 'user_id', 'ldap_status', 'ldap_token']; 
}
