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

class vikBookingPayment {
	
	private $order_info;
	private $params;
	private $sandbox;
	
	/**
	 * Do not edit this function unless you know what you are doing
	 * it is just meant to define the parameters of the payment method
	 */
	static function getAdminParameters () {
		return array(
				'logo' => array('type' => 'custom', 'label' => '', 'html' => '<img src="https://www.paypalobjects.com/webstatic/i/ex_ce2/logo/logo_paypal_106x29.png"/>'),
				'account' => array('type' => 'text', 'label' => 'PayPal Account:'),
				'sandbox' => array('type' => 'select', 'label' => 'Test Mode://if ON, the PayPal Sandbox will be used', 'options' => array('OFF', 'ON'))
		);
	}
	
	public function __construct ($order, $params = array()) {
		$this->order_info=$order;
		$this->params = $params;
		$this->sandbox = $params['sandbox'] == 'ON' ? 1 : 0;
	}
	
	public function showPayment () {
		$depositmess="";
		//coupon
		if ($this->order_info['total_tax'] < 0) {
			$this->order_info['total_tax'] = 0;
		}
		if (($this->order_info['total_net_price'] + $this->order_info['total_tax']) > $this->order_info['total_to_pay']) {
			$this->order_info['total_net_price'] = $this->order_info['total_to_pay'];
			$this->order_info['total_tax'] = 0;
		}
		//
		if($this->sandbox == 1) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		$paypalaccount = !empty($this->params['account']) ? $this->params['account'] : $this->order_info['account_name'];
		$form="<form action=\"".$paypal_url."\" method=\"post\">\n";
		$form.="<input type=\"hidden\" name=\"business\" value=\"".$paypalaccount."\"/>\n";
		$form.="<input type=\"hidden\" name=\"cmd\" value=\"_xclick\"/>\n";
		$form.="<input type=\"hidden\" name=\"amount\" value=\"".number_format($this->order_info['total_net_price'], 2)."\"/>\n";
		$form.="<input type=\"hidden\" name=\"item_name\" value=\"".$this->order_info['transaction_name']."\"/>\n";
		$form.="<input type=\"hidden\" name=\"item_number\" value=\"".$this->order_info['rooms_name']."\"/>\n";
		$form.="<input type=\"hidden\" name=\"quantity\" value=\"1\"/>\n";
		$form.="<input type=\"hidden\" name=\"tax\" value=\"".number_format($this->order_info['total_tax'], 2)."\"/>\n";
		$form.="<input type=\"hidden\" name=\"shipping\" value=\"0.00\"/>\n";
		$form.="<input type=\"hidden\" name=\"currency_code\" value=\"".$this->order_info['transaction_currency']."\"/>\n";
		$form.="<input type=\"hidden\" name=\"no_shipping\" value=\"1\"/>\n";
		$form.="<input type=\"hidden\" name=\"rm\" value=\"2\"/>\n";
		$form.="<input type=\"hidden\" name=\"notify_url\" value=\"".$this->order_info['notify_url']."\"/>\n";
		$form.="<input type=\"hidden\" name=\"return\" value=\"".$this->order_info['return_url']."\"/>\n";
		$form.="<input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_paynow_SM.gif\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">\n";
		$form.="</form>\n";
		if($this->order_info['leave_deposit']) {
			$depositmess="<p class=\"vbo-leave-deposit\"><span>".JText::_('VBLEAVEDEPOSIT')."</span>".$this->order_info['currency_symb']." ".number_format($this->order_info['total_to_pay'], 2)."</p><br/>";
		}
		//output form
		echo $depositmess;
		echo $this->order_info['payment_info']['note'];
		echo $form;
		
		return true;
	}
	
	public function validatePayment () {
		$log="";
		$array_result=array();
		$array_result['verified']=0;
		
		//cURL Method HTTP1.1 October 2013
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
			$log.=$key.": ".$value."\n";
		}
		
		if(!function_exists('curl_init')) {
			$log = "FATAL ERROR: cURL is not installed on the server\n\n".$log;
			$array_result['log']=$log;
			return $array_result;
		}
		
		if($this->sandbox == 1) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		
		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			$log = "Curl error: ".curl_error($ch)."\n\n".$log;
			$array_result['log']=$log;
			return $array_result;
		}
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if($this->sandbox == 1) {
			//TLS 1.2
			curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		}
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and copy it in the same folder as this php file
		// This is mandatory for some environments.
		//$cert = dirname(__FILE__) . "/cacert.pem";
		//curl_setopt($ch, CURLOPT_CAINFO, $cert);
		
		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) {
			$log .= date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL;
			curl_close($ch);
			$array_result['log']=$log;
			return $array_result;
		} else {
			$log .= date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL;
			$log .= date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL;
			curl_close($ch);
		}
		
		if (strcmp (trim($res), "VERIFIED") == 0) {
			$array_result['tot_paid']=$_POST['mc_gross'];
			$array_result['verified'] = 1;
		}elseif (strcmp ($res, "INVALID") == 0) {
			$log .= date('[Y-m-d H:i e] '). "Invalid IPN: $req"."\n$res" . PHP_EOL;
		}
		
		//END cURL Method HTTP1.1 October 2013
		
		//old IPN method before October 2013
//		$req = 'cmd=_notify-validate';
//		foreach ($_POST as $k => $v) {
//			$req .= "&".$k."=".urlencode(stripslashes($v));
//			$log.=$k.": ".$v."\n";
//			if($k=='mc_gross') {
//				//cannot be in decimals
//				$array_result['tot_paid']=$v;
//			}
//		}
//		$array_result['log']=$log;
//		$sheader .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
//		$sheader .= "Content-Type: application/x-www-form-urlencoded\r\n";
//		$sheader .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//		
//		$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
//		$res = "";
//		if ($fp) {
//			fputs ($fp, $sheader.$req);
//			while (!feof($fp)) {
//				$res .= fgets ($fp, 1024);
//			}
//			fclose ($fp);
//			if (strcmp ($res, "VERIFIED") == 0 || substr($res, -8, 8) == "VERIFIED") {
//				$array_result['verified']=1;
//			}
//		}
		//END old IPN method before October 2013
		
		$log .= "\n\n".$res;
		$array_result['log']=$log;
		
		return $array_result;
	}
	
}


?>