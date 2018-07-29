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

class os_authnet extends os_payment {
	var $login    = "";
    var $transkey = "";
    var $params   = array();
    var $results  = array();
    var $approved = false;
    var $declined = false;
    var $error    = true;
    var $mode;
    var $fields;
    var $response;
    /**
     * Constructor function
     *
     * @param object $config
     */    
    function os_authnet($params)
    {
    	parent::setName('os_authnet');    	
    	parent::os_payment();    	
    	parent::setCreditCard(true);
    	parent::setCardType(false);
    	parent::setCardCvv(true);
    	parent::setCardHolderName(false);    	        	    	    	
        $this->mode = $params->get('authnet_mode', 0) ;        
        $this->login = $params->get('x_login');
        $this->transkey = $params->get('x_tran_key');
     	if ($this->mode)
        {
        	$this->url = "https://secure.authorize.net/gateway/transact.dll";              
        }
        else
        {
            $this->url = "https://test.authorize.net/gateway/transact.dll";    
        }       
        $this->params['x_delim_data']     = "TRUE";
        $this->params['x_delim_char']     = "|";
        $this->params['x_relay_response'] = "FALSE";
        $this->params['x_url']            = "FALSE";
        $this->params['x_version']        = "3.1";
        $this->params['x_method']         = "CC";
        $this->params['x_type']           = "AUTH_CAPTURE";
        $this->params['x_login']          = $this->login;
        $this->params['x_tran_key']       = $this->transkey;
        $this->params['x_invoice_num']	  = $this->_invoiceNumber();	                    
    }
    /**
     * Process payment with the posted data
     *
     * @param array $data array
     * @return void
     */
    function processPayment($row, $data)
    {    	
    	$mainframe = & JFactory::getApplication() ;
    	$db = JFactory::getDbo();
    	$Itemid = JRequest::getInt('Itemid');    	 
		$data['x_description'] = $data['item_name'] ;
		$data['x_exp_date'] = str_pad($data['exp_month'], 2, '0', STR_PAD_LEFT) .'/'.substr($data['exp_year'], 2, 2) ;
		$data['amount']  = round($data['amount'], 2) ;
    	$retries = 1;
    	$testing = $this->mode ? "FALSE":"TRUE";  
    	$cc_num = $this->_ccNumber($data["x_card_num"]);      	
    	//Set more parameters for the payment gateway to user
    	$authnetValues				= array
		(										
		//Payment information
		 	"x_test_request"		=> $testing,
			"x_card_num"			=> $data['x_card_num'],
			"x_exp_date"			=> $data['x_exp_date'],
			"x_card_code"			=> $data['x_card_code'],
			"x_description"			=> $data['x_description'],	
			"x_amount"				=> $row->order_upfront,		    
		//  ###########3  CUSTOMER DETAILS  ################3
			"x_first_name"			=> $data['first_name'],
			"x_last_name"			=> $data['last_name'],		
			"x_address"				=> $data['address'],
			"x_city"				=> $data['city'],
			"x_state"				=> $data['state'],		
			"x_phone"				=> $data['phone'],		
			"x_zip"					=> $data['zip'],		
			"x_company"				=> '',	
			"x_email"				=> $row->order_email,
			"x_country"				=>  $data['country'] , 
		//  ###########3  SHIPPING DETAILS  ################3
			"x_ship_to_first_name" 	=> $data['first_name'],
			"x_ship_to_last_name" 	=> $data['last_name'],
			"x_ship_to_address"  	=> $data['address'],
			"x_ship_to_city" 		=> $data['city'],
			"x_ship_to_state" 		=> $data['state'],
			"x_ship_to_country" 	=> $data['country'],
			"x_ship_to_zip" 		=> $data['zip'],
			"x_ship_to_phone" 		=> $data['phone'],
			"x_ship_to_email" 		=> $data['email'],
		//  ###########3  MERCHANT REQUIRED DETAILS  ################3
			"cc_number" 			=> $cc_num ,
			"cc_expdate" 			=> $data['x_exp_date'] ,
			"cc_emailid" 			=> $row->order_email	
		);    	    	    						
		foreach ($authnetValues as $key=>$value){
			$this->setParameter($key,$value);
		}							
        $this->_prepareParameters();
        $ch = curl_init($this->url);
        $count = 0;                        
        while ($count < $retries)
        {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($this->fields, "& "));                                   
            //Uncomment this line if you get no response from payment gateway
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            //If you are using goodaddy hosting, please uncomment the two below lines                        	
            //curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			//curl_setopt ($ch, CURLOPT_PROXY,"http://proxy.shr.secureserver.net:3128");           
            $this->response = curl_exec($ch);
            $this->_parseResults();
            if ($this->getResultResponseFull() == "Approved")
            {
                $this->approved = true;
                $this->declined = false;
                $this->error    = false;
                break;
            }
            else if ($this->getResultResponseFull() == "Declined")
            {
                $this->approved = false;
                $this->declined = true;
                $this->error    = false;
                break;
            }
            $count++;
        }
        curl_close($ch);        
        if($this->approved){          	
        	$db->setQuery("UPDATE #__app_sch_orders SET transaction_id = '', order_status = 'S' WHERE id = '$row->id'");
        	$db->query();
        	OsAppscheduleDefault::paymentComplete($row->id);      																	
			$mainframe->redirect(JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=default_paymentreturn&id='.$row->id, false, false));									
        	return true;
        }
        else{        	        
        	$_SESSION['reason'] = $this->getResponseText() ;        	        
        	$mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&task=default_paymentfailure&id='.$row->id, false, false),$this->getResponseText());         	        
        	return false;
        }        
    }
    function _parseResults()
    {
        $this->results = explode("|", $this->response);
    }
    function setParameter($param, $value)
    {
        $param                = trim($param);
        $value                = trim($value);
        $this->params[$param] = $value;
    }  
    function _prepareParameters()
    {
        foreach($this->params as $key => $value)
        {
            $this->fields .= "$key=" . urlencode($value) . "&";
        }
    }
    function getResultResponse()
    {
        return $this->results[0];
    }
    function getResultResponseFull()
    {
        $response = array("", "Approved", "Declined", "Error");
        return $response[$this->results[0]];
    }
    function getResponseText()
    {
        return $this->results[3];
    }
    function getTransactionID()
    {
        return $this->results[6];
    }
    function getResultResponseText()
    {
    	$res="";
    	switch($this->results[0])
    	{
    		case 1:
    			$res="This transaction has been approved";
    			break;
    		case 2:
    			$res="This transaction has been declined";
    			break;
    		case 3:
    			$res="There has been an error processing this transaction";
    			break;
    		case 4:
    			$res="This transaction is being held for review";
    			break;
    		default:
    			$res="Unknown the transaction status text";
    			break;		    				    			
    	}
    	return $res;    	
    }
	/*
     * Helper function to generate invoice number
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */    
    function _invoiceNumber($prefix="DC-",$length=6)
    {
    	$chars = "0123456789";
    	$invoiceNumber="";
		srand((double)microtime()*1000000);
		for($i=0; $i<$length; $i++)
	    {	
	   		$invoiceNumber .= $chars[rand()%strlen($chars)];	
	    }
		$invoiceNumber = $prefix.$invoiceNumber;
		return $invoiceNumber;
    }
    /**
     * Generate credit card number
     *
     * @param string $card_num
     * @return string
     */
    function _ccNumber($card_num){
    	$num=strlen($card_num);
    	$cc_num="";
    	for($i=0;$i<=$num-5;$i++)
		{
			$cc_num.="x";
		}
		$cc_num.="-";
		$cc_num.=substr($card_num,$num-4,4);
		return $cc_num;
    }             
}