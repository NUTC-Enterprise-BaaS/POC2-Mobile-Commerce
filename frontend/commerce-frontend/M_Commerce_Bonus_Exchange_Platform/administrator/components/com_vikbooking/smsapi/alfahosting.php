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
	
	private $BASE_URI = 'https://sms.alfahosting.de/api/';
	
	public static function getAdminParameters() {
		return array(
			'apikey' => array(
				'label' => 'API Key',
				'type' => 'text'
			),
			
			'username' => array(
				'label' => 'Username',
				'type' => 'text'
			),
			
			'sender' => array(
				'label' => 'Sender Name',
				'type' => 'text'
			),
			
			'prefix' => array(
				'label' => 'Phone Prefix',
				'type' => 'text'
			),
		);
	}
	
	public function __construct ($order, $params=array()) {
		$this->order_info=$order;
		
		$this->params = ( !empty($params) ) ? $params : $this->params;
	}
	
	///// SEND MESSAGE /////

	public function sendMessage( $phone_number, $msg_text, $when=NULL ) {
		if( empty($phone_number) || empty($msg_text) ) return;
		
		return $this->_send( $phone_number, $msg_text, $when );
	}

	private function _send( $phone_number, $msg_text, $when=NULL ) {
		$this->log = '';
		
		$phone_number = trim(str_replace(" ", "", $phone_number));
		
		if( substr($phone_number, 0, 1) != '+' ) {
			if( substr($phone_number, 0, 2) == '00' ) {
				$phone_number = '+'.substr($phone_number, 2);
			} else {
				$phone_number = $this->params['prefix'].$phone_number;
			}
		}

		$errorCodes = array(
			100	=> 'Übertragungsfehler - Bitte informieren Sie den Admin',
			101	=> 'Übertragungsfehler - Bitte informieren Sie den Admin',
			102	=> 'Übertragungsfehler - Bitte informieren Sie den Admin',
			200	=> 'Genereller Fehler - Bitte informieren Sie den Admin',
			201	=> 'Genereller Fehler - Bitte informieren Sie den Admin',
			202	=> 'Genereller Fehler - Bitte informieren Sie den Admin',
			203	=> 'Fehlerhafter oder leerer Empfänger',
			204	=> 'Fehlerhafte oder leere Nachricht',
			205	=> 'Genereller Fehler - Bitte informieren Sie den Admin',
			300	=> 'Serverfehler - Bitte informieren Sie den Admin',
			-1	=> 'Unbekannter Fehler - Bitte informieren Sie den Admin',
		);

		try {
			
			// Build XML
			$xml = new SimpleXMLElement('<alfasms></alfasms>');
			$xml->addAttribute('user', utf8_encode($this->params['username']));
			$xml->addAttribute('key', $this->params['apikey']);
			$data = $xml->addChild('data');
			$data->addChild('sender', html_entity_decode($this->params['sender'], ENT_COMPAT, 'UTF-8'));
			$data->addChild('receiver', html_entity_decode($phone_number, ENT_COMPAT, 'UTF-8'));
			$data->addChild('message', base64_encode(utf8_encode($msg_text)));
			
			// Send XML to API by curl
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->BASE_URI);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-type: text/xml; charset=UTF-8',
				'User-Agent: SMS-Form 1.0',
			));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml->asXML());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);

			// Parse result as XML
			$result = @new SimpleXMLElement($result);
			
			// All went right
			if( $result->success && $result->success instanceof SimpleXMLElement ) {
				
			} else {
				// An error occured
				$errorCode = (int)$result->error->code;
				$errorMessage = $result->error->message;
				$this->log .= "Es trat ein Fehler auf. Bitte beheben Sie folgenden Eingabefehler: ". "'$errorCodes[$errorCode]' (Code $errorCode)";
			}
			
			return $result;
		} catch( Exception $e ) {
			$this->log .= $e->getMessage();
			return false;
		}
		
		return false;
		
	}
	
	public function validateResponse($response_obj) {
		return ($response_obj->success && $response_obj->success instanceof SimpleXMLElement);
	}
	
	///// UTILS /////
	
	public function getLog() {
		return $this->log;
	}
	
}

?>