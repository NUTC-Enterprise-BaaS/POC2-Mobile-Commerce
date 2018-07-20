<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\ldapauths;
use App\Repositories\LdapRepository;

class LdapController extends Controller
{
	public function __construct(LdapRepository $LdapRepository)
    {
        $this->LdapRepository = $LdapRepository;
    }
    public function ldapAdd(Request $request)
    {
    	$token = $this->LdapRepository->addLdapUser($request);
    	if($token == null){
	    	return response()->json([
	    				'message' => 'ladp add fail'
		            ]);
    	}else if($token == 'api fail'){
    		return response()->json([
	    				'message' => 'BlockChan server error'
		            ]);
    	}else{
    		return response()->json([
	    				'message' => 'ladp create success',
		                'token' => $token
		            ]);
    	}
    }
    public function addLdapU2U(Request $request)
    {
        $message = $this->LdapRepository->addLdapU2U($request);
        if($message == "verify_code fail" || $message == "time error" || $message == "api fail"){
            return response()->json([
                            "message" => $message
                        ]);
        }else{
            return response()->json([
                            "message" => "binding success",
                            "token" => $message
                        ]);
        }
    }
	public function cleanBinding(Request $request)
	{
		$message = $this->LdapRepository->cleanBinding($request);
		if($message == "user no enable BlockChain"){
			return response()->json([
				"message" => $message
			]);
		}else{
			return response()->json([
				"message" => "binding clean",
				"token" => $message
			]);
		}
	}
    public function ldapUserPoint(Request $request)
    {
		$account = ldapauths::where(["stor"=>$request->stor,"user"=>$request->email])->first();
    	if($account != null){
	    	$point = $this->LdapRepository->getAccountPoint($account->bc_account);
	    	if($point != null)
	    	{
		    	return response()->json([
		    			'stor' => $request->stor,
		    			'email' => $request->email,
		    			'token' => $account->token,
		                'point' => $point
		            ]);
	    	}else{
	    		return response()->json([
		                'message' => "api server error"
		            ]);
	    	}
    	}else{
    		return response()->json([
		                'message' => " input error"
		            ]);
    	}
    }
    public function ldapUserPointChange(Request $request)
    {
        $notice = 'no connect api';
    	$data = $this->LdapRepository->changePoint($request);
        if(isset($data->statusCode)){
            if($data->statusCode == 200){
                $notice = $this->LdapRepository->noticeUserStor($request);
            }
        }
    	return response()->json([
    					"status" => "success",
		    			"message" => $data,
                        "notice" => $notice
		            ]);
    }
    public function showUserStor(Request $request)
    {
    	$data = $this->LdapRepository->showUserStor($request->token);
    	return response()->json([
    					"status" => "success",
		    			"list" => $data
		            ]);
    }
    public function getverifycode(Request $request)
    {
        $verifycode = $this->LdapRepository->createTokeVerifyCode($request->token);
        return response()->json([
                        "verifycode" => $verifycode
                    ]);
	}
}
