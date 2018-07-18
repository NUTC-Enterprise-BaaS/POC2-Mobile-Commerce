<?php
namespace App\Repositories;

use App\Entities\ldapauths;
use App\Entities\tokenVerification;
use Curl\Curl;
use DB;

class LdapRepository
{
	private $host = '10.0.0.79:8000';
	private $stor=[
		"LELIGO" => '10.0.0.59:8080',
		"HappyBuy" => '10.0.0.59:8081',
	];
	public function __construct(Curl $curl)
	{
		$this->curl = $curl;
	}

	public function addLdapUser($params)
	{
		$token = str_random(20);
		$this->url = 'http://'.$this->host.'/api/v1/user/newAccount';
		$this->curl->setHeader('Accept', 'application/json');
		$this->curl->setHeader('Content-Type', 'application/json');
		$this->body = '{"point":'.$params->point.',"store":"'.$params->stor.'"}';
		$this->curl->post($this->url,$this->body);
		$data =  json_decode($this->curl->response);
		if($data != null){
			ldapauths::create(["stor"=>$params->stor,"user"=>$params->email,"bc_account"=>$data->message->account,"token"=>$token]);
			return $token;
		}else{
			return 'api fail';
		}
	}
	public function addLdapU2U($params)
	{
		$check = tokenVerification::where('verify_code',$params->verify_code)->count();
        if($check != 0){
        	$check = tokenVerification::where('verify_code',$params->verify_code)->where('created_at','>',date('Y-m-d H:i:s', strtotime('-1 min')))->first();
			if($check != null){
				$token = tokenVerification::where('id',$check->id)->first()->token;
				$this->url = 'http://'.$this->host.'/api/v1/user/newAccount';
				$this->curl->setHeader('Accept', 'application/json');
				$this->curl->setHeader('Content-Type', 'application/json');
				$this->body = $this->body = '{"point":'.$params->point.',"store":"'.$params->stor.'"}';
				$this->curl->post($this->url,$this->body);
				$data = json_decode($this->curl->response);
				if($data != null){
					ldapauths::create(["stor"=>$params->stor,"user"=>$params->email,"bc_account"=>$data->message->account,"token"=>$token]);
					return $token;
				}else{
					return 'api fail';
				}
			}else{
				return "time error";
			}
		}else{
			return "verify_code fail";
		}
	}
	public function getAccountPoint($account)
	{
		$errstring = "Internal Server Error";
		$this->url = 'http://'.$this->host.'/api/v1/user/getPoint';
		$this->curl->setHeader('Accept', 'application/json');
		$this->curl->setHeader('Content-Type', 'application/json');
		$this->body = '{"account":"'.$account.'"}';
		$this->curl->post($this->url,$this->body);
		if($this->curl->response != $errstring){
			$data = json_decode($this->curl->response);
			return $data->message->point;
		}else{
			return null;
		}
	}
	public function changePoint($params)
	{	
		$account = ldapauths::where("token",$params->token)->first();
		if($account != null){
			$fromAccount = ldapauths::where(["user"=>$params->formUser,"stor"=>$params->fromStor])->first();
			$toAccount = ldapauths::where(["user"=>$params->toUser,"stor"=>$params->toStor])->first();
			$this->url = 'http://'.$this->host.'/api/v1/change/changePoint';
			$this->curl->setHeader('Accept', 'application/json');
			$this->curl->setHeader('Content-Type', 'application/json');
			$this->body = '{"txPoint":'.$params->txPoint.',"fromAccount":"'.$fromAccount->bc_account.'","toAccount":"'.$toAccount->bc_account.'"}';
			$this->curl->post($this->url,$this->body);
			$data = json_decode($this->curl->response);
			if($data != null){
				return $data;
			}
		}else{
			return 'token no exist';
		}
	}
	public function noticeUserStor($params)
	{
		$url = 'http://'.$this->stor[$params->toStor].'/api/v1/receive/LdapPoint/'.$params->toUser;
		return $url;
	}
	public function showUserStor($token)
	{
		$list=[];
		$data = ldapauths::where("token",$token)->get();
		foreach ($data as $key => $Data) {
			$list[] = [
				'stor' => $Data->stor,
				'username' => $Data->user,
			];
		}
		return $list;
	}
    public function createTokeVerifyCode($token)
    {
        $verify_code = str_random(4);
        $check = tokenVerification::where('created_at','>',date('Y-m-d H:i:s', strtotime('-1 min')))
        					->where('verify_code',$verify_code)->count();
        if($check == 0){
	        tokenVerification::create(['verify_code' => $verify_code,
	        							'token' => $token,
	              ]);
	        return $verify_code;
	    }else{
	    	$this->createTokeVerifyCode($token);
	    }
    }
}