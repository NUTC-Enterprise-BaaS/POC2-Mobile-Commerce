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

class VikBookingCustomersPin {
	
	public $all_pins;
	public $is_admin;
	public $error;
	private $dbo;
	private $new_pin;
	private $new_customer_id;
	
	public function __construct() {
		$this->all_pins = false;
		$this->is_admin = false;
		$this->error = '';
		$this->dbo = JFactory::getDBO();
		$this->new_pin = '';
		$this->new_customer_id = '';
	}
	
	/**
	* Generates a unique PIN for the customer
	* @param notpush
	*/
	public function generateUniquePin($notpush = false) {
		$rand_pin = rand(10999, 99999);
		if($this->pinExists($rand_pin)) {
			while ($this->pinExists($rand_pin)) {
				$rand_pin += 1;
			}
		}
		if(!$notpush) {
			$this->all_pins[] = $rand_pin;
		}
		return $rand_pin;
	}

	/**
	* Checks if the pin already exists
	* @param pin
	* @param ignorepin
	*/
	public function pinExists($pin, $ignorepin = '') {
		$current_pins = $this->all_pins === false ? $this->getAllPins($ignorepin) : $this->all_pins;
		return in_array($pin, $current_pins);
	}

	/**
	* Fetches and sets all the pins currently stored in the database
	* @param ignorepin
	*/
	public function getAllPins($ignorepin = '') {
		$current_pins = array();
		$q = "SELECT `pin` FROM `#__vikbooking_customers`".(!empty($ignorepin) ? " WHERE `pin`!=".$this->dbo->quote($ignorepin) : "").";";
		$this->dbo->setQuery($q);
		$this->dbo->Query($q);
		if($this->dbo->getNumRows() > 0) {
			$pins = $this->dbo->loadAssocList();
			foreach ($pins as $v) {
				$current_pins[] = $v['pin'];
			}
		}
		$this->all_pins = $current_pins;
		return $this->all_pins;
	}

	/**
	* Attempts to fetch the customer details record by Joomla User ID
	*/
	private function getDetailsByUjid(&$customer_details) {
		$user = JFactory::getUser();
		if (!$user->guest && (int)$user->id > 0) {
			$q = "SELECT * FROM `#__vikbooking_customers` WHERE `ujid`=".intval($user->id)." ORDER BY `#__vikbooking_customers`.`id` DESC LIMIT 1;";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			if($this->dbo->getNumRows() == 1) {
				$customer = $this->dbo->loadAssoc();
				$customer['cfields'] = empty($customer['cfields']) ? array() : json_decode($customer['cfields'], true);
				$customer_details = $customer;
			}
		}
		return $customer_details;
	}

	/**
	* Attempts to fetch the customer details record by PIN Cookie
	*/
	private function getDetailsByPinCookie(&$customer_details) {
		$pin_cookie = $this->getPinCookie();
		$pin_cookie = empty($pin_cookie) ? (int)$this->getNewPin() : $pin_cookie;
		if($pin_cookie > 0) {
			$q = "SELECT * FROM `#__vikbooking_customers` WHERE `pin`=".$this->dbo->quote($pin_cookie)." ORDER BY `#__vikbooking_customers`.`id` DESC LIMIT 1;";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			if($this->dbo->getNumRows() == 1) {
				$customer = $this->dbo->loadAssoc();
				$customer['cfields'] = empty($customer['cfields']) ? array() : json_decode($customer['cfields'], true);
				$customer_details = $customer;
			}
		}
		return $customer_details;
	}

	/**
	* Gets "decoded" PIN from Cookie
	*/
	private function getPinCookie() {
		$pin_cookie = 0;
		$cookie = JFactory::getApplication()->input->cookie;
		$cookie_val = $cookie->get('vboPinData', '', 'string');
		if(!empty($cookie_val) && intval($cookie_val) > 0) {
			$cookie_val = intval(strrev( (string)$cookie_val )) / 1987;
			$pin_cookie = (int)$cookie_val > 0 ? $cookie_val : $pin_cookie;
		}
		return $pin_cookie;
	}

	/**
	* Sets "encoded" PIN to Cookie with a lifetime of 365 days
	* @param pin
	*/
	private function setPinCookie($pin) {
		$pin_cookie = 0;
		if(!empty($pin)) {
			$pin_cookie = (int)$pin * 1987;
			$pin_cookie = strrev( (string)$pin_cookie );
			$cookie = JFactory::getApplication()->input->cookie;
			$cookie->set( 'vboPinData', $pin_cookie, (time() + (86400 * 365)), '/' );
		}
		
		return $pin_cookie;
	}

	/**
	* Unsets PIN Cookie
	*/
	private function unsetPinCookie() {
		$cookie = JFactory::getApplication()->input->cookie;
		$cookie->set( 'vboPinData', $pin_cookie, (time() - (86400 * 365)), '/' );
		$cookie_val = $cookie->get('vboPinData', '', 'string');
		
		return $pin_cookie;
	}

	/**
	* Loads the customer details by Joomla User ID or by PIN Cookie
	* Returns an associative array with the record fetched from the DB
	*/
	public function loadCustomerDetails() {
		$customer_details = array();
		//First attempt is through Joomla User ID
		$this->getDetailsByUjid($customer_details);
		if(!(count($customer_details) > 0)) {
			//Second attempt is through PIN Cookie
			$this->getDetailsByPinCookie($customer_details);
		}

		return $customer_details;
	}

	/**
	* Attempts to fetch the customer details record by PIN code
	*/
	public function getCustomerByPin($pin) {
		$this->setNewPin($pin);
		$customer = array();
		if(!empty($pin)) {
			$q = "SELECT * FROM `#__vikbooking_customers` WHERE `pin`=".$this->dbo->quote($pin)." ORDER BY `#__vikbooking_customers`.`id` DESC LIMIT 1;";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			if($this->dbo->getNumRows() == 1) {
				$customer = $this->dbo->loadAssoc();
				$customer['cfields'] = empty($customer['cfields']) ? array() : json_decode($customer['cfields'], true);
				$this->setPinCookie($pin);
			}
		}
		return $customer;
	}

	/**
	* Checkes whether a customer with the same email address already exists
	* Returns false or the record of the existing customer
	*/
	public function customerExists($email) {
		if(empty($email)) {
			return false;
		}
		$q = "SELECT * FROM `#__vikbooking_customers` WHERE `email`=".$this->dbo->quote(trim($email))." ORDER BY `#__vikbooking_customers`.`id` DESC LIMIT 1;";
		$this->dbo->setQuery($q);
		$this->dbo->Query($q);
		if($this->dbo->getNumRows() == 1) {
			$customer = $this->dbo->loadAssoc();
			return $customer;
		}
		return false;
	}

	/**
	* Saves the customer in DB if it doesn't exist, generates the PIN and sets the cookie
	*/
	public function saveCustomerDetails($first_name, $last_name, $email, $phone_number, $country, $cfields) {
		if(empty($first_name) || empty($last_name) || empty($email)) {
			$this->setError('Missing fields for saving new customer');
			return false;
		}
		$customer = $this->customerExists($email);
		if($customer === false) {
			$new_pin = $this->generateUniquePin();
			$user = JFactory::getUser();
			$q = "INSERT INTO `#__vikbooking_customers` (`first_name`,`last_name`,`email`,`phone`,`country`,`cfields`,`pin`,`ujid`) VALUES(".$this->dbo->quote($first_name).", ".$this->dbo->quote($last_name).", ".$this->dbo->quote($email).", ".$this->dbo->quote($phone_number).", ".$this->dbo->quote($country).", ".(is_array($cfields) && count($cfields) ? $this->dbo->quote(json_encode($cfields)) : "NULL").", ".$this->dbo->quote($new_pin).", ".($this->is_admin ? '0' : intval($user->id)).");";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			$new_customer_id = $this->dbo->insertid();
			if(!empty($new_customer_id)) {
				$this->setNewPin($new_pin);
				$this->setNewCustomerId($new_customer_id);
			}
		}elseif(is_array($customer)) {
			$this->setNewPin($customer['pin']);
			$this->setNewCustomerId($customer['id']);
			$q = "UPDATE `#__vikbooking_customers` SET `first_name`=".$this->dbo->quote($first_name).",`last_name`=".$this->dbo->quote($last_name).",`email`=".$this->dbo->quote($email).",`phone`=".$this->dbo->quote($phone_number).",`country`=".$this->dbo->quote($country).(!$this->is_admin ? ",`cfields`=".(is_array($cfields) && count($cfields) ? $this->dbo->quote(json_encode($cfields)) : "NULL") : "")." WHERE `id`=".$customer['id'].";";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
		}
		return !$this->is_admin ? $this->storeCustomerCookie() : true;
	}

	public function storeCustomerCookie() {
		$pin = $this->getNewPin();
		$customer_id = $this->getNewCustomerId();
		if(empty($pin) || empty($customer_id)) {
			return false;
		}
		$this->setPinCookie($pin);
		return true;
	}

	/**
	* Stores a relation between the Customer ID and the Booking ID
	* This method should be called after the saveCustomerDetails() because
	* it requires the methods setNewPin and setNewCustomerId to be called before.
	* @param orderid
	*/
	public function saveCustomerBooking($orderid) {
		$pin = $this->getNewPin();
		$customer_id = $this->getNewCustomerId();
		if(empty($orderid) || empty($pin) || empty($customer_id)) {
			return false;
		}
		$q = "INSERT INTO `#__vikbooking_customers_orders` (`idcustomer`,`idorder`) VALUES(".$this->dbo->quote($customer_id).", ".$this->dbo->quote($orderid).");";
		$this->dbo->setQuery($q);
		$this->dbo->Query($q);
		return true;
	}

	/**
	* Takes the Customer PIN from the Order ID
	* @param orderid
	*/
	public function getPinCodeByOrderId($orderid) {
		$pin = '';
		if(!empty($orderid)) {
			$q = "SELECT `o`.`id`,`oc`.`idcustomer`,`c`.`pin` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `oc` ON `oc`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`oc`.`idcustomer` WHERE `o`.`id`=".intval($orderid)." AND `oc`.`idcustomer` IS NOT NULL;";
			$this->dbo->setQuery($q);
			$this->dbo->Query($q);
			if($this->dbo->getNumRows() > 0) {
				$custdata = $this->dbo->loadAssocList();
				if(!empty($custdata[0]['pin'])) {
					$pin = $custdata[0]['pin'];
				}
			}
		}
		
		return $pin;
	}

	/**
	* Sets the current customer PIN
	* @param pin
	*/
	public function setNewPin($pin = '') {
		$this->new_pin = $pin;
	}

	/**
	* Get the current customer PIN
	*/
	public function getNewPin() {
		return $this->new_pin;
	}

	/**
	* Sets the current customer ID
	* @param cid
	*/
	public function setNewCustomerId($cid = '') {
		$this->new_customer_id = $cid;
	}

	/**
	* Get the current customer ID
	*/
	public function getNewCustomerId() {
		return $this->new_customer_id;
	}

	/**
	* Explanation of the XML error
	* @param error
	*/
	public function libxml_display_error($error) {
		$return = "\n";
		switch ($error->level) {
			case LIBXML_ERR_WARNING :
				$return .= "Warning ".$error->code.": ";
				break;
			case LIBXML_ERR_ERROR :
				$return .= "Error ".$error->code.": ";
				break;
			case LIBXML_ERR_FATAL :
				$return .= "Fatal Error ".$error->code.": ";
				break;
		}
		$return .= trim($error->message);
		if ($error->file) {
			$return .= " in ".$error->file;
		}
		$return .= " on line ".$error->line."\n";
		return $return;
	}

	/**
	* Get the XML errors occurred
	*/
	public function libxml_display_errors() {
		$errorstr = "";
		$errors = libxml_get_errors();
		foreach ($errors as $error) {
			$errorstr .= $this->libxml_display_error($error);
		}
		libxml_clear_errors();
		return $errorstr;
	}

	private function setError($str) {
		$this->error .= $str."\n";
	}

	public function getError() {
		return nl2br(rtrim($this->error, "\n"));
	}
	
}

?>