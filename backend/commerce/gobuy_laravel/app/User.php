<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'block', 'sendEmail', 'registerDate', 'lastvisitDate',
        'params', 'lastResetTime', 'resetCount', 'otpKey', 'otep', 'requireReset',
        'phone', 'bonus', 'superBonus'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}