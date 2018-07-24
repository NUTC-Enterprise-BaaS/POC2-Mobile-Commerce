<?php
/**
 * @version		1.1.1
 * @package		Joomla
 * @subpackage	OS Membership
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die ;

define( 'EWAY_DEFAULT_GATEWAY_URL', 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp' );
define( 'EWAY_DEFAULT_CUSTOMER_ID', '87654321' );
define( 'EWAY_CURL_ERROR_OFFSET', 1000 );
define( 'EWAY_XML_ERROR_OFFSET',  2000 );
define( 'EWAY_TRANSACTION_OK',       0 );
define( 'EWAY_TRANSACTION_FAILED',   1 );
define( 'EWAY_TRANSACTION_UNKNOWN',  2 );
/**
 * Eway payment class
 *
 */
class os_eway extends os_payment {
	/**
	 * XML parse
	 *
	 * @var object
	 */
	var $parser;

	/**
     * XML data passed to eway
     *
     * @var string
     */
	var $xmlData;

	/**
     * 
     *
     * @var string
     */
	var $currentTag;

	/**
     * Gateway URL to process payment
     *
     * @var string
     */
	var $myGatewayURL;

	/**
     * Eway customer ID
     *
     * @var string
     */
	var $myCustomerID;

	/**
     * Total amount
     *
     * @var Double
     */
	var $myTotalAmount;

	/**
     * Customer first name
     *
     * @var string
     */
	var $myCustomerFirstname;

	/**
     * Customer last name
     *
     * @var string
     */
	var $myCustomerLastname;

	/**
     * Customer email
     *
     * @var string
     */
	var $myCustomerEmail;

	/**
     * Customer address
     *
     * @var string
     */
	var $myCustomerAddress;

	/**
     * Customer postcode
     *
     * @var string
     */
	var $myCustomerPostcode;

	/**
     * Invoice description
     *
     * @var string
     */
	var $myCustomerInvoiceDescription;

	/**
     * Invoice reference : Order ID in our system
     *
     * @var string
     */
	var $myCustomerInvoiceRef;

	/**
     * Cart holders name
     *
     * @var string
     */
	var $myCardHoldersName;

	/**
     * Card numbder
     *
     * @var string
     */
	var $myCardNumber;

	/**
     * Card expiration month
     *
     * @var string
     */
	var $myCardExpiryMonth;

	/**
     * Card Expiration Year
     *
     * @var string
     */
	var $myCardExpiryYear;

	/**
     * Card CVN
     *
     * @var string
     */
	var $myCardCVN;

	/**
     * Transaction Number
     *
     * @var string
     */
	var $myTrxnNumber;

	/**
     * Option 1
     *
     * @var string
     */
	var $myOption1;

	/**
     * Option 2
     *
     * @var string
     */
	var $myOption2;

	/**
     * Option 3
     *
     * @var string
     */
	var $myOption3;

	/**
     * Transaction Status
     *
     * @var string
     */
	var $myResultTrxnStatus;

	/**
     * Trasnaction Number
     *
     * @var string
     */
	var $myResultTrxnNumber;

	/**
     * Result option 1
     *
     * @var string
     */
	var $myResultTrxnOption1;

	/**
     * Result option 2
     *
     * @var string
     */
	var $myResultTrxnOption2;

	/**
     * Result option 3
     *
     * @var string
     */
	var $myResultTrxnOption3;

	/**
     * Result Reference
     *
     * @var string
     */
	var $myResultTrxnReference;

	/**
     * 
     *
     * @var string
     */
	var $myResultTrxnError;

	/**
     * *
     *
     * @var String
     */
	var $myResultAuthCode;

	/**
     * Amount
     *
     * @var string
     */
	var $myResultReturnAmount;

	/**
     *
     *
     * @var String
     */
	var $myCardName;

	/**
	 * Error
	 *
	 * @var string
	 */
	var $myError;

	/**
     * Error message
     *
     * @var string
     */
	var $myErrorMessage;
    /***********************************************************************
     *** Class Constructor                                               ***
     ***********************************************************************/
    function os_eway($params) {    	
    	parent::setName('os_eway');
		parent::os_payment();
		parent::setCreditCard(true);
		parent::setCardType(false);
		parent::setCardCvv(true);
		parent::setCardHolderName(true);
		$this->myCustomerID = $params->get('eway_customer_id');
		$ewayMode = $params->get('eway_mode', 0);
		$ewayCvn = $params->get('eway_cvn', 0);
		if ($ewayMode)
		{
			if ($ewayCvn)
			{
				$gatewayURL = 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp';
			}
			else
			{
				$gatewayURL = 'https://www.eway.com.au/gateway/xmlpayment.asp';
			}
		}
		else
		{
			//Test mode
			if ($ewayCvn)
			{
				$gatewayURL = 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp';
			}
			else
			{
				$gatewayURL = 'https://www.eway.com.au/gateway/xmltest/testpage.asp';
			}
		}
		$this->myGatewayURL = $gatewayURL;
    }
    /***********************************************************************
     *** XML Parser - Callback functions                                 ***
     ***********************************************************************/
	function epXmlElementStart($parser, $tag, $attributes)
	{
		$this->currentTag = $tag;
	}

	function epXmlElementEnd($parser, $tag)
	{
		$this->currentTag = "";
	}

	function epXmlData($parser, $cdata)
	{
		$this->xmlData[$this->currentTag] = $cdata;
	}

	/***********************************************************************
     *** SET values to send to eWAY                                      ***
     ***********************************************************************/
	function setCustomerID($customerID)
	{
		$this->myCustomerID = $customerID;
	}

	function setTotalAmount($totalAmount)
	{
		$this->myTotalAmount = $totalAmount;
	}

	function setCustomerFirstname($customerFirstname)
	{
		$this->myCustomerFirstname = $customerFirstname;
	}

	function setCustomerLastname($customerLastname)
	{
		$this->myCustomerLastname = $customerLastname;
	}

	function setCustomerEmail($customerEmail)
	{
		$this->myCustomerEmail = $customerEmail;
	}

	function setCustomerAddress($customerAddress)
	{
		$this->myCustomerAddress = $customerAddress;
	}

	function setCustomerPostcode($customerPostcode)
	{
		$this->myCustomerPostcode = $customerPostcode;
	}

	function setCustomerInvoiceDescription($customerInvoiceDescription)
	{
		$this->myCustomerInvoiceDescription = $customerInvoiceDescription;
	}

	function setCustomerInvoiceRef($customerInvoiceRef)
	{
		$this->myCustomerInvoiceRef = $customerInvoiceRef;
	}

	function setCardHoldersName($cardHoldersName)
	{
		$this->myCardHoldersName = $cardHoldersName;
	}

	function setCardNumber($cardNumber)
	{
		$this->myCardNumber = $cardNumber;
	}

	function setCardExpiryMonth($cardExpiryMonth)
	{
		$this->myCardExpiryMonth = $cardExpiryMonth;
	}

	function setCardExpiryYear($cardExpiryYear)
	{
		$this->myCardExpiryYear = $cardExpiryYear;
	}

	function setCardCVN($cardCVN)
	{
		$this->myCardCVN = $cardCVN;
	}

	function setTrxnNumber($trxnNumber)
	{
		$this->myTrxnNumber = $trxnNumber;
	}

	function setOption1($option1)
	{
		$this->myOption1 = $option1;
	}

	function setOption2($option2)
	{
		$this->myOption2 = $option2;
	}

	function setOption3($option3)
	{
		$this->myOption3 = $option3;
	}

	/***********************************************************************
     *** GET values returned by eWAY                                     ***
     ***********************************************************************/
	function getTrxnStatus()
	{
		return $this->myResultTrxnStatus;
	}

	function getTrxnNumber()
	{
		return $this->myResultTrxnNumber;
	}

	function getTrxnOption1()
	{
		return $this->myResultTrxnOption1;
	}

	function getTrxnOption2()
	{
		return $this->myResultTrxnOption2;
	}

	function getTrxnOption3()
	{
		return $this->myResultTrxnOption3;
	}

	function getTrxnReference()
	{
		return $this->myResultTrxnReference;
	}

	function getTrxnError()
	{
		return $this->myResultTrxnError;
	}

	function getAuthCode()
	{
		return $this->myResultAuthCode;
	}

	function getReturnAmount()
	{
		return $this->myResultReturnAmount;
	}

	function getError()
	{
		if ($this->myError != 0)
		{
			// Internal Error
			return $this->myError;
		}
		else
		{
			// eWAY Error
			if ($this->getTrxnStatus() == 'True')
			{
				return EWAY_TRANSACTION_OK;
			}
			elseif ($this->getTrxnStatus() == 'False')
			{
				return EWAY_TRANSACTION_FAILED;
			}
			else
			{
				return EWAY_TRANSACTION_UNKNOWN;
			}
		}
	}

	function getErrorMessage()
	{
		if ($this->myError != 0)
		{
			// Internal Error
			return $this->myErrorMessage;
		}
		else
		{
			// eWAY Error
			return $this->getTrxnError();
		}
	}
   
    /***********************************************************************
     *** Business Logic                                                  ***
     ***********************************************************************/
    function processPayment($row, $data) {
    	$mainframe = & JFactory::getApplication() ;
    	$Itemid = JRequest::getInt('Itemid');    					
		$this->setCustomerFirstname($data['first_name']);
		$this->setCustomerLastname($data['last_name']);
		$this->setCustomerEmail($row->order_email);
		$this->setCustomerAddress($data['address']) ;
		$this->setCustomerPostcode($data['zip']);
		$this->setCustomerInvoiceDescription($data['item_name']);		
		$this->setCustomerInvoiceRef($row->id);
		$this->setCardHoldersName($data['card_holder_name']);
		$this->setCardNumber($data['x_card_num']);
		$this->setCardExpiryMonth(str_pad($data['exp_month'], 2, '0', STR_PAD_LEFT) );
		$this->setCardExpiryYear(substr($data['exp_year'], 2, 2));
		$this->setCardCVN($data['x_card_code']);
		$this->setTrxnNumber($row->transaction_id);
		$this->setTotalAmount($row->order_upfront*100);
        $xmlRequest = "<ewaygateway>" . 
        			  "<ewayCustomerID>" . htmlentities($this->myCustomerID) . "</ewayCustomerID>" . 
        			  "<ewayTotalAmount>" . htmlentities($this->myTotalAmount) . "</ewayTotalAmount>" . 
        			  "<ewayCustomerFirstName>" . htmlentities($this->myCustomerFirstname) . "</ewayCustomerFirstName>" . 
			 		  "<ewayCustomerLastName>" . htmlentities($this->myCustomerLastname) . "</ewayCustomerLastName>" . 
			 		  "<ewayCustomerEmail>" . htmlentities($this->myCustomerEmail) ."</ewayCustomerEmail>" . 
			 		  "<ewayCustomerAddress>" . htmlentities($this->myCustomerAddress) . "</ewayCustomerAddress>" . 
			 		  "<ewayCustomerPostcode>" . htmlentities($this->myCustomerPostcode) . "</ewayCustomerPostcode>" .
			 		  "<ewayCustomerInvoiceDescription>" . htmlentities($this->myCustomerInvoiceDescription) . "</ewayCustomerInvoiceDescription>" . 
			 		  "<ewayCustomerInvoiceRef>" . htmlentities($this->myCustomerInvoiceRef) . "</ewayCustomerInvoiceRef>" .
			 
			 		  "<ewayCardHoldersName>" . htmlentities($this->myCardHoldersName) . "</ewayCardHoldersName>" . 
			 		  "<ewayCardNumber>" . htmlentities($this->myCardNumber) . "</ewayCardNumber>" . 
			 		  "<ewayCardExpiryMonth>" .  htmlentities($this->myCardExpiryMonth) . "</ewayCardExpiryMonth>" . 
			 		  "<ewayCardExpiryYear>" . htmlentities($this->myCardExpiryYear) . "</ewayCardExpiryYear>" . 
			 		  "<ewayTrxnNumber>" . htmlentities($this->myTrxnNumber) . "</ewayTrxnNumber>" .
			 		  "<ewayOption1>" . htmlentities($this->myOption1) . "</ewayOption1>" . 
			 		  "<ewayOption2>" . htmlentities($this->myOption2) . "</ewayOption2>" . 
			 		  "<ewayOption3>" . htmlentities($this->myOption3) . "</ewayOption3>" . 
			 		  "<ewayCVN>" .htmlentities($this->myCardCVN) . "</ewayCVN>" . "</ewaygateway>";
			 		  
        /* Use CURL to execute XML POST and write output into a string */
        $ch = curl_init( $this->myGatewayURL );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $xmlRequest );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 240 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $xmlResponse = curl_exec( $ch );
		//exit;
        
        // Check whether the curl_exec worked.
        if( curl_errno( $ch ) == CURLE_OK ) {
            // It worked, so setup an XML parser for the result.
            $this->parser = xml_parser_create();
            
            // Disable XML tag capitalisation (Case Folding)
            xml_parser_set_option ($this->parser, XML_OPTION_CASE_FOLDING, FALSE);
            
            // Define Callback functions for XML Parsing
            xml_set_object($this->parser, $this);
            xml_set_element_handler ($this->parser, "epXmlElementStart", "epXmlElementEnd");
            xml_set_character_data_handler ($this->parser, "epXmlData");
            
            // Parse the XML response
            xml_parse($this->parser, $xmlResponse, TRUE);
            
            if( xml_get_error_code( $this->parser ) == XML_ERROR_NONE ) {
                // Get the result into local variables.
                $this->myResultTrxnStatus = $this->xmlData['ewayTrxnStatus'];
                $this->myResultTrxnNumber = $this->xmlData['ewayTrxnNumber'];
                $this->myResultTrxnOption1 = $this->xmlData['ewayTrxnOption1'];
                $this->myResultTrxnOption2 = $this->xmlData['ewayTrxnOption2'];
                $this->myResultTrxnOption3 = $this->xmlData['ewayTrxnOption3'];
                $this->myResultTrxnReference = $this->xmlData['ewayTrxnReference'];
                $this->myResultAuthCode = $this->xmlData['ewayAuthCode'];
                $this->myResultReturnAmount = $this->xmlData['ewayReturnAmount'];
                $this->myResultTrxnError = $this->xmlData['ewayTrxnError'];
                $this->myError = 0;
                $this->myErrorMessage = '';
            } else {
                // An XML error occured. Return the error message and number.
                $this->myError = xml_get_error_code( $this->parser ) + EWAY_XML_ERROR_OFFSET;
                $this->myErrorMessage = xml_error_string( $myError );
            }
            // Clean up our XML parser
            xml_parser_free( $this->parser );
        } else {
            // A CURL Error occured. Return the error message and number. (offset so we can pick the error apart)
            $this->myError = curl_errno( $ch ) + EWAY_CURL_ERROR_OFFSET;
            $this->myErrorMessage = curl_error( $ch );
        }
        // Clean up CURL, and return any error.
        curl_close( $ch );
        $result = $this->getError();
        if ($result == EWAY_TRANSACTION_OK) {
        	$db = JFactory::getDbo();
			$db->setQuery("UPDATE #__app_sch_orders SET transaction_id = '".$this->getTrxnNumber()."', order_status = 'S' WHERE id = '$row->id'");
        	$db->query();
        	OsAppscheduleDefault::paymentComplete($row->id);
        	$mainframe->redirect(JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=default_paymentreturn&id='.$row->id.'&Itemid='.$Itemid, false, false));
        	        	
        	return true ;        	
        } else {
        	$_SESSION['reason'] = $this->getErrorMessage() ;        	        
        	$mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&task=default_paymentfailure&id='.$row->id.'&Itemid='.$Itemid, false, false),$this->getErrorMessage());      	       	        	
        	        	        
        	return false;
        }
    }
} 
?>