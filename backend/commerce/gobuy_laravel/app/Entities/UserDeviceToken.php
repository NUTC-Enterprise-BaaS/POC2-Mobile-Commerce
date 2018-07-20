<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    protected $fillable = ['user_id', 'name', 'username', 'email', 'device', 'device_token'];
}
