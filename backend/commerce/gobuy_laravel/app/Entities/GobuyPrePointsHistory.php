<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GobuyPrePointsHistory extends Model
{
    public $timestamps = false;
    protected $table = 'gobuy_pre_points_history';
    protected $fillable = ['points_id', 'user_id', 'points', 'created',
    	'state', 'message'
    ];
}
