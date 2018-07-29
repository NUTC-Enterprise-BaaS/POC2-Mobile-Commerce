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

class os_paypal extends os_payment {
	/**
	 * Paypal mode 
	 *
	 * @var boolean live mode : true, test mode : false
	 */
	var $_mode = 0 ;
	/**
	 * Paypal url
	 *
	 * @var string
	 */
	var $_url = null ;
	/**
	 * Array of params will be posted to server
	 *
	 * @var string
	 */
	var $_params = array();
	/**
	 * Array containing data posted from paypal to our server
	 *
	 * @var array
	 */
	var $_data = array();
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $config
	 */
	function os_paypal($params) {
		parent::setName('os_paypal');
		parent::os_payment();						
		parent::setCreditCard(false);		
    	parent::setCardType(false);
    	parent::setCardCvv(false);
    	parent::setCardHolderName(false);
		$this->ipn_log = false ;
		$this->ipn_log_file = '';
		$this->_mode = $params->get('paypal_mode') ;
		if ($this->_mode)
			$this->_url = 'https://www.paypal.com/cgi-bin/webscr';
		else
			$this->_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';											
		$this->setParam('business', $params->get('paypal_id'));	
		$this->setParam('rm', 2);
		$this->setParam('cmd', '_xclick');
		$this->setParam('no_shipping', 1);
		$this->setParam('no_note', 1);
		$this->setParam('lc', 'US');
		$this->setParam('currency_code', $params->get('paypal_currency', 'USD'));
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
	 * Process Payment
	 *
	 * @param object $row
	 * @param array $params
	 */
	function processPayment($row, $data) {
		$Itemid = JRequest::getInt('Itemid');		
		$siteUrl = JURI::base() ;
		$db = & JFactory::getDBO() ;										
		$this->setParam('item_name', $data['item_name']); 
		$this->setParam('amount', $data['amount']);
	
		$this->setParam('custom', $row->id);																								
		$this->setParam('return', $root_link."index.php?option=com_osservicesbooking&task=default_paymentreturn&id=$row->id&Itemid=".JRequest::getVar('Itemid'));	
		$this->setParam('cancel_return', $siteUrl.'index.php?option=com_osservicesbooking&task=default_paymentcancel&id='.$row->id);
		$this->setParam('notify_url', $siteUrl.'index.php?option=com_osservicesbooking&task=defaul_paymentconfirm&payment_method=os_paypal');		
		$this->setParam('address1', $data['address']);		
		$this->setParam('address2', '');		
		$this->setParam('city', $data['city']);		
		$this->setParam('country', $data['country']);		
		$this->setParam('first_name', $data['first_name']);
		$this->setParam('last_name', $data['last_name']);
		$this->setParam('state', $data['state']);
		$this->setParam('zip', $data['zip']);
		$this->setParam('email', $row->order_email) ;		
		$this->submitPost();				
	}
	/**
	 * Submit post to paypal server
	 *
	 */	
	function submitPost() {		
	?>
		<div class="contentheading"><?php echo  JText::_('OS_WAIT_PAYPAL'); ?></div>
		<form method="post" action="<?php echo $this->_url; ?>" name="osm_form" id="osm_form">
			<?php
				foreach ($this->_params as $key=>$val) {
					echo '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
					echo "\n";	
				}
			?>
			<script type="text/javascript">
				function redirect() {
					document.osm_form.submit();
				}
				
				setTimeout('redirect()',7000);
			</script>
		</form>
	<?php	
	}
	/**
	 * Validate the post data from paypal to our server
	 *
	 * @return string
	 */
	function _validate() {
		$errNum="";
	   	$errStr="";
	    $urlParsed = parse_url($this->_url);
	    $host = $urlParsed['host'];
	    $path = $urlParsed['path'];        
	    $postString = ''; 
	    $response = '';   
	    foreach ($_POST as $key=>$value) { 
	       $this->_data[$key] = $value;
	       $postString .= $key.'='.urlencode(stripslashes($value)).'&'; 
	    }
	    $postString .='cmd=_notify-validate';
	    $fp = fsockopen($host , '80', $errNum, $errStr, 30); 
	      if(!$fp) {	                
	         return false;
	      } else {	      		
	         fputs($fp, "POST $path HTTP/1.1\r\n"); 
	         fputs($fp, "Host: $host\r\n"); 
	         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
	         fputs($fp, "Content-length: ".strlen($postString)."\r\n"); 
	         fputs($fp, "Connection: close\r\n\r\n"); 
	         fputs($fp, $postString . "\r\n\r\n"); 
	         while(!feof($fp)) { 
	            $response .= fgets($fp, 1024); 
	         } 
	         fclose($fp);
	      }	      	
	     $this->ipn_response = $response ;       
	     $this->log_ipn_results(true); 
	     if ($this->_mode) {
	         if (stristr($response, "VERIFIED") && ($this->_data['payment_status'] == 'Completed')) 	         
    	       	 return true;       
    	     else 	           
    	         return false;
	     } else {	     	
	     	//Always return true for test mode, prevent unnecessary support requests	     	
	         return true ;    
	     }    	     	
	}
	/**
	 * Log IPN result
	 *
	 * @param string $success
	 */
	function log_ipn_results($success) {
	  /*
      if (!$this->ipn_log) return;
      $text = '['.date('m/d/Y g:i A').'] - '; 
      if ($success) $text .= "SUCCESS!\n";
      	else $text .= 'FAIL: '.$this->last_error."\n"; 
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->_data as $key=>$value) {
         $text .= "$key=$value, ";
      }
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 
      fclose($fp);  // close file
	  */
   }
	/**
	 * Process payment 
	 *
	 */
	function verifyPayment() {
		$ret = $this->_validate();				
		if ($ret) {
			//$config = OSMembershipHelper::getConfig() ;
			require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."tables".DS."order.php");
			$row = JTable::getInstance('Order', 'OsAppTable');
			$id = $this->_data['custom'];
			$db = JFactory::getDbo();
			$db->setQuery("Select order_status from #__app_sch_orders where id = '$id'");
			$order_status = $db->loadResult();
			if($order_status != "S"){ //only running when the system already update Order status
	   			$transactionId = $this->_data['txn_id'];
	   			$amount = $this->_data['mc_gross'];   			
	   			if ($amount < 0)
	   				return false ;   						
				$row->load($id);
				if ($row->published)
					return false ;
	        	$row->transaction_id = $transactionId;
	        	$row->order_status = "S";
	        	$row->store();	
				OsAppscheduleDefault::paymentComplete($row->id);
	   			return true;
			}
		} else {
			return false;
		}		     
	}	
}