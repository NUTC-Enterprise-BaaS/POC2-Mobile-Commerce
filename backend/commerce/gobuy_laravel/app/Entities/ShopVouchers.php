<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ShopVouchers extends Model
{
    protected $fillable = ['id','user_id','voucher_status','voucher_message','update_at','created_at'];
    protected $table = 'ShopVouchers';
}