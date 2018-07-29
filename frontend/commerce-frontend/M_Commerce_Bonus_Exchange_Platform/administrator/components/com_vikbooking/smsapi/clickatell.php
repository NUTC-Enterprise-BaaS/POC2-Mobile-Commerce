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
	private $BASE_URL = "http://api.clickatell.com";
	
	public static function getAdminParameters() {
		return array(
			"user" => array(
				'label' => 'User',
				'type' => 'text'
			),
			"password" => array(
				'label' => 'Password',
				'type' => 'text'
			),
			"apis" => array(
				'label' => 'API ID',
				'type' => 'text'
			),
			"senderid" => array(
				'label' => 'Sender ID//this field is optional and it should be the approved source-sender address. Random senders will be rejected if not approved. Leave empty if unsure.',
				'type' => 'text'
			),
			"prefix" => array(
				'label' => 'Default Prefix',
				'type' => 'text'
			)
		);
	}
	
	public function __construct ($order, $params=array()) {
		$this->order_info=$order;
		
		$this->params = ( !empty($params) ) ? $params : $this->params;
	}
	
	///// SEND MESSAGE /////
	
	//$date = new DateTime ( "2014-02-08 11:14:15" );
	//$when = $date->format ( DateTime::ISO8601 );

	public function sendMessage( $phone_number, $msg_text, $when=NULL ) {
		if( empty($phone_number) || empty($msg_text) ) return;
		
		$phone_number = trim(str_replace(" ", "", $phone_number));
		
		if( substr($phone_number, 0, 1) != '+' ) {
			if( substr($phone_number, 0, 2) == '00' ) {
				$phone_number = '+'.substr($phone_number, 2);
			} else {
				$phone_number = $this->params['prefix'].$phone_number;
			}
		}
		
		return $this->_send( $phone_number, $msg_text );
	}
	
	private function _send( $destination, $message ) {
		
		$user = $this->params['user'];
		$password = $this->params['password'];
		$api_id = $this->params['apis'];
	   
		if( $unicode_message = $this->sms_clickatell_unicode($message) ) {
			$text = $unicode_message;
		} else {
			$text = rawurlencode($message).'&unicode=0';
		}
 
		// auth call
		$url = $this->BASE_URL."/http/auth?user=$user&password=$password&api_id=$api_id";
 
		// do auth call
		$ret = file($url);
 
		// explode our response. return string is on first line of the data returned
		$sess = explode(":", $ret[0]);
		if( $sess[0] == "OK" ) {
 
			$sess_id = trim($sess[1]); // remove any whitespace
			$url = $this->BASE_URL."/http/sendmsg?session_id=$sess_id&to=$destination&text=$text".(!empty($this->params['senderid']) ? "&from=".$this->params['senderid'] : ""); // unicode added automatically

			$msglen = strlen($text);
			if($msglen > 306) {
				$url .= '&concat=3';
			} else if($msglen > 160) {
				$url .= '&concat=2';
			}
 
			// do sendmsg call
			$ret = file($url);
			$send = explode(":", $ret[0]);
 
			if( $send[0] == "ID" ) {
				
			} else {
				$this->log = "Send message failed: ".$send[1];
				return false;
			}
		} else {
			$this->log = "Authentication failure: ". $ret[0];
			return false;
		}
		
		return true;
		
	}

	public function validateResponse($response_obj) {
		return ($response_obj);
	}
	
	///// UTILS /////
	
	public function getLog() {
		return $this->log;
	}

	protected function sms_clickatell_unicode($message) {
		if( function_exists('iconv') ) {
			$latin = @iconv('UTF-8', 'ISO-8859-1', $message);
			if( strcmp($latin, $message) ) {
				$arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
				return strtoupper($arr['hex']) .'&unicode=1';
			}
		}
		return false;
	}

}	

?>
