<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OS Services Booking
 * @author  	Tuan Pham Ngoc
 * @copyright	Copyright (C) 2010 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
class os_sagepay extends  os_payment {	
	/**
	 * 
	 * Sagepay URL
	 * @var string
	 */
	var $url = null ;
	/**
	 * Sagepay protocol	 
	 * @var string
	 */
	var $protocol = '2.23' ;
	/**
	 * Vendor name	 
	 * @var string
	 */
	var $vendor_name = null ;
	/**
	 * Currency	 
	 * @var string
	 */
	var $currency = null ;	
	/**
	 * Encryption password	 
	 * @var string
	 */
	var $encryption_password = null ;
	/**
	 * Sagepay param
	 *
	 * @var array
	 */
	var $_params = array() ;	
	/**
	 * Array containing data posted from googlecheckout to our server
	 *
	 * @var array
	 */
	var $_data = array();
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $config
	 */
	function os_sagepay($params) {		
		parent::setName('os_sagepay');
		parent::os_payment();		
		parent::setCreditCard(false);		
    	parent::setCardType(false);
    	parent::setCardCvv(false);
    	parent::setCardHolderName(false);
    	if ($params->get('sg_mode', 0)) {
    		$this->url = 'https://live.sagepay.com/gateway/service/vspform-register.vsp' ;    		
    	} else {    		
    		$this->url = 'https://test.sagepay.com/gateway/service/vspform-register.vsp' ;
    	}	
    	$this->vendor_name = $params->get('sg_vendor_name');
    	$this->currency = $params->get('sg_currency', 'GBP');			
    	$this->encryption_password = $params->get('sg_encryption_password');
	}	
	/**
	 * Set param value
	 *
	 * @param string $name
	 * @param string $val
	 */
	function setParam($name, $val) {
		$this->_params[$name] = $val;
	}
	/**
	 * Setup payment parameter
	 *
	 * @param array $params
	 */
	function setParams($params) {
		foreach ($params as $key => $value) {
			$this->_params[$key] = $value ;
		}
	}
	/**
	 * Display a form with googlecheckout button to allow users to check on it and process the checkout
	 *
	 */	
	function submitPost($gcCart) {
	?>
		<div class="contentheading"><?php echo  JText::_('OS_SAGEPAY_CHECKOUT_INSTRUCTION'); ?></div>	
	<?php
		
	}
	/**
	 * Validate the post data from paypal to our server
	 *
	 * @return string
	 */
	function _validate() {		       	     
	    foreach ($_POST as $key=>$value) { 
	       $this->_data[$key] = $value;	       
	    }	   	    	      		       
	    $this->log_ipn_results(true); 
     	return true ;	
	}
	/**
	 * Log IPN result
	 *
	 * @param string $success
	 */
	function log_ipn_results($success) {
      if (!$this->ipn_log) return;
      $text = '['.date('m/d/Y g:i A').'] - '; 
      if ($success) $text .= "SUCCESS!\n";
      	else $text .= 'FAIL: '.$this->last_error."\n"; 
      $text .= "IPN POST Vars from Gogle checkout:\n";
      foreach ($this->_data as $key=>$value) {
         $text .= "$key=$value, ";
      }    
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 
      fclose($fp);  // close file
   }
   /**
    * Process payment
    *
    * @param object $row
    * @param array $data
    */   
   function processPayment($row, $data) {
   		require_once JPATH_COMPONENT.DS.'plugins'.DS.'sagepay'.DS.'includes.php';
   		$Itemid = JRequest::getInt('Itemid');   		   	
   		$siteUrl = JURI::base() ;   		   		
   		//Build the list of params passed to Sagepay   		
   		$this->setParam('VendorTxCode', $row->id) ;
   		$this->setParam('Amount', $row->order_upfront) ;
   		$this->setParam('Currency', $data['currency']) ;
   		$this->setParam('Description', $data['item_name']) ;
   		$this->setParam('SuccessURL', $siteUrl.'index.php?option=com_osservicesbooking&task=defaul_paymentconfirm&payment_method=os_sagepay&Itemid='.$Itemid) ;
   		$this->setParam('FailureURL', $siteUrl.'index.php?option=com_osservicesbooking&task=defaul_paymentconfirm&payment_method=os_sagepay&Itemid='.$Itemid) ;
   		$this->setParam('CustomerName', $row->order_name) ;
   		$this->setParam('BillingFirstnames', $data['first_name']) ;
   		$this->setParam('BillingSurname', $data['last_name']) ;
   		$this->setParam('BillingAddress1', $$data['address']) ; 	
   		$this->setParam('BillingAddress2', '') ;
   		$this->setParam('BillingCity',$data['city']) ;
   		$this->setParam('BillingPostCode', $data['zip']) ;
   		$this->setParam('BillingCountry', $data['country']) ;
   		$this->setParam('BillingState', $data['state']) ;
   		$this->setParam('BillingPhone', $row->order_phone) ;
   		
   		$this->setParam('DeliveryFirstnames', $data['first_name']) ;
   		$this->setParam('DeliverySurname', $data['last_name']) ;
   		$this->setParam('DeliveryAddress1', $data['address']) ; 	
   		$this->setParam('DeliveryAddress2', '') ;
   		$this->setParam('DeliveryCity', $data['city']) ;
   		$this->setParam('DeliveryPostCode', $data['zip']) ;
   		$this->setParam('DeliveryCountry', $data['country']) ;
   		$this->setParam('DeliveryState', $data['state']) ;
   		$this->setParam('DeliveryPhone', $row->order_phone) ;
   		
   		$this->setParam('AllowGiftAid', 0) ;
   		$this->setParam('ApplyAVSCV2', 0) ;
   		$this->setParam('Apply3DSecure', 0) ;		
   		$params = '' ;
   		foreach ($this->_params as $key => $value) {
   			if (strlen($value))
   				$params .= $key.'='.$value.'&' ;
   		}
   		//Remove the last & in the url
   		$params = substr($params, 0, strlen($params) - 1) ;   		
   		$crypt = base64Encode(SimpleXor($params, $this->encryption_password)) ;		
   ?>
   		<div class="contentheading"><?php echo  JText::_('OS_WAIT_SAGEPAY'); ?></div>
   		<form action="<?php echo $this->url; ?>" method="POST" id="os_form" name="os_form"> 
			<input type="hidden" name="navigate" value="" />
			<input type="hidden" name="VPSProtocol" value="<?php echo $this->protocol ; ?>" />
			<input type="hidden" name="TxType" value="PAYMENT">
			<input type="hidden" name="Vendor" value="<?php echo $this->vendor_name ; ?>" />
			<input type="hidden" name="Crypt" value="<?php echo $crypt ; ?>" />						
			<script type="text/javascript">
				function redirect() {
					document.os_form.submit();
				}
				setTimeout('redirect()',5000);
			</script>
		</form>
   <?php				
   }
	/**
	 * Process payment 
	 *
	 */
	function verifyPayment() {
		require_once JPATH_COMPONENT.DS.'payments'.DS.'sagepay'.DS.'includes.php';
		$mainframe = & JFactory::getApplication();
		$strCrypt=$_REQUEST["crypt"];
		if (empty($strCrypt))
			return false ;
		$strDecoded=simpleXor(Base64Decode($strCrypt), $this->encryption_password);
		$values = getToken($strDecoded);
		// Split out the useful information into variables we can use
		$strStatus=$values['Status'];
		$strStatusDetail=$values['StatusDetail'];
		$strVendorTxCode=$values["VendorTxCode"];
		$strVPSTxId=$values["VPSTxId"];
		$strTxAuthNo=$values["TxAuthNo"];
		$strAmount=$values["Amount"];
		$strAVSCV2=$values["AVSCV2"];
		$strAddressResult=$values["AddressResult"];
		$strPostCodeResult=$values["PostCodeResult"];
		$strCV2Result=$values["CV2Result"];
		$strGiftAid=$values["GiftAid"];
		$str3DSecureStatus=$values["3DSecureStatus"];
		$strCAVV=$values["CAVV"];
		$strCardType=$values["CardType"];
		$strLast4Digits=$values["Last4Digits"];
		$strAddressStatus=$values["AddressStatus"]; // PayPal transactions only
		$strPayerStatus=$values["PayerStatus"];   
		if (strtoupper($strStatus) == 'OK') {
			$row = JTable::getInstance('Order', 'OsAppTable');
   			$row->load((int)$strVendorTxCode);
   			if (!$row->id)
   				return false ;
        	       
    		$row->transaction_id = $strVPSTxId ;
    		$row->order_status   = "S";
    		$row->store();
    		OsAppscheduleDefault::paymentComplete($row->id);
			$mainframe->redirect(JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=default_paymentreturn&id='.$row->id.'&Itemid='.$Itemid, false, false));
		} else {
			$_SESSION['reason'] = $strStatusDetail ;        	        
        	$mainframe->redirect(JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=default_paymentfailure&id='.$row->id.'&Itemid='.$Itemid, false, false));        	       	        	        	
        	return false;
		}							
	}				
}