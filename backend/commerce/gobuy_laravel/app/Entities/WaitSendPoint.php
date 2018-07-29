<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class WaitSendPoint extends Model
{
    protected $fillable = [
    	'user_id', 'user_phone', 'points', 'state', 'message',
    	'store_id', 'created'
    ];
}