<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	OS Services Booking
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;
class os_paypal_pro extends os_payment 
{
	/**
	 * Api username
	 *
	 * @var string
	 */
	var $API_USERNAME;
	/**
	 * Api password
	 *
	 * @var string
	 */
	var $API_PASSWORD;
	/**
	 * API signature
	 *
	 * @var string
	 */
	var $API_SIGNATURE;
	/**
	 * API endpoint, the url to call to process function
	 *
	 * @var string
	 */
	var $API_ENDPOINT;
	/**
	 * Version of API
	 *
	 * @var string
	 */		
	var $VERSION;	
	
	var $params = array();
	/**
	 * Construtor function
	 *
	 * @param string $API_USERNAME
	 * @param string $API_PASSWORD
	 * @param string $API_SIGNATURE
	 * @param boolean $IS_ONLINE
	 * @param string $VERSION
	 * @return os_paypal_pro
	 */
	function os_paypal_pro($params, $VERSION = '57.0')
	{
		parent::setName('os_paypal_pro') ;
		parent::os_payment() ;
		parent::setCreditCard(true);
		parent::setCardType(true);
		parent::setCardCvv(true);
		parent::setCardHolderName(false);
		$this->API_USERNAME = $params->get('api_username');
		$this->API_PASSWORD = $params->get('api_password');
		$this->API_SIGNATURE = $params->get('api_signature');
		$paypalProMode = $params->get('paypal_pro_mode', 0) ;
		if ($paypalProMode) {
			$this->API_ENDPOINT = 'https://api-3t.paypal.com/nvp';	
		} else {
			$this->API_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';	
		}			
		$this->VERSION = $VERSION;
	}	
	/**
	 * Set parametter for processing payment
	 *
	 * @param string $key
	 * @param string $value
	 */
	function setParam($key, $value) {
		$this->params[$key] = $value ;
	}
	/**
	 * Process Payment
	 *
	 * @param array $data
	 */
	function processPayment($row, $data) {	
		$db = JFactory::getDbo();
		$mainframe = & JFactory::getApplication();
    	$Itemid    = JRequest::getInt('Itemid'); 		
		$this->setParam('PAYMENTACTION', 'Sale');				
		$this->setParam('AMT', $row->order_upfront);			
		$this->setParam('CREDITCARDTYPE', $data['card_type']);														
		$this->setParam('ACCT', $data['x_card_num']);		
		$this->setParam('EXPDATE', $data['exp_month'].$data['exp_year']);		
		$this->setParam('CVV2', $data['x_card_code']);		
		$this->setParam('FIRSTNAME', $data['first_name']);		
		$this->setParam('LASTNAME', $data['last_name']);				
		$this->setParam('STREET', $data['address']);						
		$this->setParam('CITY', $data['city']);								
		$this->setParam('STATE', $data['state']);										
		$this->setParam('ZIP', $data['zip']) ;
		$this->setParam('COUNTRYCODE', $data['country']);
		$this->setParam('CURRENCYCODE', $data['currency']);				
		$this->setParam('METHOD','doDirectPayment') ;
		$this->setParam('VERSION', $this->VERSION);																										
		$this->setParam('PWD', $this->API_PASSWORD);		
		$this->setParam('USER', $this->API_USERNAME);		
		$this->setParam('SIGNATURE', $this->API_SIGNATURE);			
		$fields = '' ;
		foreach ($this->params as $key=>$value) {
			$fields.= "$key=" . urlencode($value) . "&";	
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($fields, "& "));		
		$response = curl_exec($ch);					
		$nvpResArray=$this->deformatNVP($response);								
		if ($nvpResArray['ACK'] == 'Success') {		
			$transaction_id = $nvpResArray['TRANSACTIONID'];
        	$db->setQuery("UPDATE #__app_sch_orders SET transaction_id = '$transaction_id', order_status = 'S' WHERE id = '$row->id'");
        	$db->query();
        	OsAppscheduleDefault::paymentComplete($row->id);
			$mainframe->redirect(JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=default_paymentreturn&id='.$row->id.'&Itemid='.$Itemid, false, false));
												
        	return true;
		} else {			
			$_SESSION['reason'] = $nvpResArray['L_LONGMESSAGE0'] ;     	        
        	$mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&task=default_paymentfailure&id='.$row->id.'&Itemid='.$Itemid, false, false));    	       	        	
        	        	        
        	return false;				
		}		
	}	
	/**
	 * Extract the result from Paypal and store it in an array
	 *
	 * @param string $nvpstr
	 * @return array
	 */	
	function deformatNVP($nvpstr)
	{
		$intial=0;
		$nvpArray = array();
		while(strlen($nvpstr))
		{
			$keypos= strpos($nvpstr,'='); 
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr); 
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}	
}