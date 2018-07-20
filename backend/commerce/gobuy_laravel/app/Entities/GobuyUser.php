<?php

namespace App\Entities;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class GobuyUser extends Model implements AuthenticatableContract
{
	use Authenticatable;
	public $timestamps = false;
    protected $fillable = ['name', 'username', 'email', 'password', 'block', 'sendEmail',
    	'registerDate', 'lastvisitDate', 'params', 'lastResetTime', 'resetCount',
    	'otpKey', 'otep', 'requireReset',
    ];
    public function getRememberToken()
 	{
   		return null; // not supported
 	}

	 public function setRememberToken($value)
	 {
	   // not supported
	 }

	 public function getRememberTokenName()
	 {
	   return null; // not supported
	 }

	 /**
	  * Overrides the method to ignore the remember token.
	  */
	 public function setAttribute($key, $value)
	 {
	   $isRememberTokenAttribute = $key == $this->getRememberTokenName();
	   if (!$isRememberTokenAttribute)
	   {
	     parent::setAttribute($key, $value);
	   }
	 }
}
