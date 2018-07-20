<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class UserTransferPoint extends Model
{
	protected $fillable = ['point', 'receive_phone',
						   'receive_email', 'send_id',
						   'send_email', 'state', 'check_id'
	];
}
