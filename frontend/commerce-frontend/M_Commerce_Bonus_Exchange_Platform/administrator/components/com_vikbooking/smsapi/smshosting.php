<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

class VikSmsApi {
	
	private $order_info;
	private $params;
	private $log = '';
	
	private $BASE_URI = 'https://api.smshosting.it/rest/api';
	
	public static function getAdminParameters() {
		return array(
			'apikey' => array(
				'label' => 'API Key',
				'type' => 'text'
			),
			
			'apisecret' => array(
				'label' => 'API Secret',
				'type' => 'text'
			),
			
			'sender' => array(
				'label' => 'Sender Name//Max 11 characters',
				'type' => 'text'
			),
			
			'prefix' => array(
				'label' => 'Default Phone Prefix//This will be used only in case the prefix will be missing. It should be the prefix of your country of residence.',
				'type' => 'text'
			),
			
			'sandbox' => array(
				'label' => 'Sandbox',
				'type' => 'select',
				'options' => array('NO', 'YES')
			)
		);
	}
	
	public function __construct ($order, $params=array()) {
		$this->order_info=$order;
		
		$this->params = ( !empty($params) ) ? $params : $this->params;
		
		if( !empty($this->params['sandbox']) ) {
			if( $this->params['sandbox'] == 'NO' ) {
				$this->params['sandbox'] = false;
			} else {
				$this->params['sandbox'] = true;
			}
		}
	}
	
	///// SEND MESSAGE /////
	
	//$date = new DateTime ( "2014-02-08 11:14:15" );
	//$when = $date->format ( DateTime::ISO8601 );

	public function sendMessage( $phone_number, $msg_text, $when=NULL ) {
		if( empty($phone_number) || empty($msg_text) ) return;
		
		return $this->_send( '/sms/send', $phone_number, $msg_text, $when );
	}

	///// ESTIMATE CREDIT /////
	
	public function estimate( $phone_number, $msg_text ) {
		return $this->_send('/sms/estimate', $phone_number, $msg_text, NULL);
	}

	private function _send( $dir_uri, $phone_number, $msg_text, $when=NULL ) {
		$this->log = '';

		$unicode = $this->containsUnicode($msg_text);
		
		if( strlen($this->params['sender']) > 11 ) {
			$start = 0;
			if( substr($this->params['sender'], 0, strlen($this->params['prefix'])) == $this->params['prefix'] ) {
				$start = strlen($this->params['prefix']);
			}
			$this->params['sender'] = trim(substr($this->params['sender'], $start, 11));
		}
		
		$phone_number = trim(str_replace(" ", "", $phone_number));
		
		if( substr($phone_number, 0, 1) != '+' ) {
			if( substr($phone_number, 0, 2) == '00' ) {
				$phone_number = '+'.substr($phone_number, 2);
			} else {
				$phone_number = $this->params['prefix'].$phone_number;
			}
		}
		
		$post = array (
			'to' => urlencode($phone_number),
			'from' => urlencode($this->params['sender']),
			'group' => urlencode(NULL),
			'text' => urlencode($msg_text),
			'date' => urlencode($when),
			'transactionId' => urlencode(NULL),
			'sandbox' => urlencode( $this->params['sandbox'] ),
			'statusCallback' => urlencode(NULL),
			'type' => $unicode ? 'unicode' : 'text'
		);
		
		if( $this->params['sandbox'] ) {
			$this->log .= '<pre>'.print_r($this->params, true)."</pre>\n\n";
			$this->log .= '<pre>'.print_r($post, true)."</pre>\n\n";
		}
		
		$complete_uri = $this->BASE_URI.$dir_uri;
		
		$array_result = $this->sendPost( $complete_uri, $post );
		
		if( $array_result['from_smsh'] ) {
			return $this->parseResponse( $array_result );
		} else {
			return false;
		}
	} 
	
	private function sendPost($complete_uri, $data) {
		$post = '';
		foreach ( $data as $k => $v ) {
			$post .= "&$k=$v";
		}
		
		$array_result = array();
		
		// If available, use CURL
		if( function_exists( 'curl_version' ) ) {
			
			$to_smsh = curl_init( $complete_uri );
			curl_setopt( $to_smsh, CURLOPT_POST, true );
			curl_setopt( $to_smsh, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $to_smsh, CURLOPT_USERPWD, $this->params['apikey'] . ":" . $this->params['apisecret'] );
			curl_setopt( $to_smsh, CURLOPT_POSTFIELDS, $post );
			
			$array_result['from_smsh'] = curl_exec( $to_smsh );
			
			$array_result['smsh_response_status'] = curl_getinfo( $to_smsh, CURLINFO_HTTP_CODE );
			
			curl_close( $to_smsh );
			
		} else if( ini_get( 'allow_url_fopen' ) ) {
			// No CURL available so try the awesome file_get_contents
			
			$opts = array (
				'http' => array (
					'method' => 'POST',
					'ignore_errors' => true,
					'header' => "Authorization: Basic ".base64_encode( $this->params['apikey'] . ":" . $this->params['apisecret'] ) . "\r\nContent-type: application/x-www-form-urlencoded",
					'content' => $post 
				) 
			);
			$context = stream_context_create( $opts );
			$array_result['from_smsh'] = file_get_contents( $complete_uri, false, $context );
			
			list( $version, $status_code, $msg ) = explode( ' ', $http_response_header[0], 3 );
			
			$array_result['smsh_response_status'] = $status_code;
			
		} else {
			// No way of sending a HTTP post
			$array_result['from_smsh'] = false; 
		}
		return $array_result;
	}

	private function parseResponse($arr) {
		
		$response = json_decode( $arr['from_smsh'] );
		
		$response_obj;
		
		if( is_array($response) ){
			$response_obj = new stdClass();
			$response_obj->response = $response; 
		} else {
			$response_obj = $response;	 
		}
		
		
		if( $arr['smsh_response_status'] == 200 ) {
			$response_obj->errorCode = 0;
		}
		
		$this->log .= '<pre>'.print_r($response_obj, true)."</pre>\n\n";
		
		if( $response_obj ) {
			return $response_obj;
		} 
		
		return false;
	}
	
	public function validateResponse($response_obj) {
		return ($response_obj === NULL || $response_obj->errorCode == 0);
	}
	
	///// UTILS /////
	
	public function getLog() {
		return $this->log;
	}
	
	private function containsUnicode($msg_text) {
		return max ( array_map ( 'ord', str_split ( $msg_text ) ) ) > 127;
	}
		
}


?>