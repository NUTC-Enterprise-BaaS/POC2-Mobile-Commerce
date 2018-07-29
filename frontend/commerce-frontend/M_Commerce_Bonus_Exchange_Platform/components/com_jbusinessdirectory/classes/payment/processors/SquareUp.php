<?php
/**
 * Stripe payment processor class
 * 
 * process the payment using Stripe payment gateway 
 */

//include the main library
require_once JPATH_COMPONENT_SITE.'/classes/payment/processors/squareup/autoload.php';

class SquareUp implements IPaymentProcessor{    

    private $transactionKey = "";
    private $secretKey = "";
    
    var $type;
    var $name;
  	var $mode;
	
  	/**
  	 * Initialize the payment processor
  	 * @param unknown_type $data
  	 * @throws Exception
  	 */
	public function initialize($data){
		if (!function_exists('curl_init')) {
			throw new Exception('stripe needs the CURL PHP extension.');
		}

		$this->type = $data->type; 
		$this->name = $data->name;
		$this->mode = $data->mode;
		
		$this->applicationID = $data->application_id;
		$this->accessToken = $data->access_token;
		$this->locationId = $data->location_id;
		
	}
        
	/**
	 * Generates the payment processor html
	 */
	public function getPaymentProcessorHtml(){
		$html ="
	         <script type=\"text/javascript\" src=\"https://js.squareup.com/v2/paymentform\"></script>
			  <script>
			  var paymentForm = new SqPaymentForm({
			    applicationId: '$this->applicationID', // <-- REQUIRED: Add Application ID
			    inputClass: 'sq-input',
			    inputStyles: [
			      {
			        fontSize: '15px'
			      }
			    ],
			    cardNumber: {
			      elementId: 'sq-card-number',
			      placeholder: '**** **** **** ****'
			    },
			    cvv: {
			      elementId: 'sq-cvv',
			      placeholder: 'CVV'
			    },
			    expirationDate: {
			      elementId: 'sq-expiration-date',
			      placeholder: 'MM/YY'
			    },
			    postalCode: {
			      elementId: 'sq-postal-code'
			    },
			    callbacks: {
			      cardNonceResponseReceived: function(errors, nonce, cardData) {
			        if (errors) {
			          // handle errors
			          errors.forEach(function(error) { console.log(error.message); });
			        } else {
			          // handle nonce
			          console.log('Nonce received:');
			          console.log(nonce);
			          jQuery(\"#nonce\").val(nonce);
			           var form$ = jQuery(\"#payment-form\");
			           form$.get(0).submit();
			        }
			      },
			      unsupportedBrowserDetected: function() {
			        // Alert the buyer that their browser is not supported
			      }
			    }
			  });
			
			     jQuery('#payment-form').submit(function(event) {
					
			   	   if(!jQuery('#p_method_squareup').attr('checked')){
						paymentForm.destroy();
						return true;
					}
					else{
					    event.preventDefault();
					    paymentForm.requestCardNonce();
			   		    return false; // submit from callback
			   		}    
               });
			  </script>
			  <style type=\"text/css\">
			    .sq-input {
			      border: 1px solid rgb(223, 223, 223);
			      outline-offset: -2px;
			      margin-bottom: 5px;
			      min-height: 25px;
			    }
			    .sq-input--focus {
			      /* how your inputs should appear when they have focus */
			      outline: 5px auto rgb(59, 153, 252);
			    }
			    .sq-input--error {
			      /* how your inputs should appear when invalid */
			      outline: 5px auto rgb(255, 97, 97);
			    }
			  </style>
		        <ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		        	<li>
				         <label>Card Number</label>
						  <div id=\"sq-card-number\"></div>
						  <label>CVV</label>
						  <div id=\"sq-cvv\"></div>
						  <label>Expiration Date</label>
						  <div id=\"sq-expiration-date\"></div>
						  <label>Postal Code</label>
						  <div id=\"sq-postal-code\"></div>
				  	</li>
				 </ul>

        	<input type=\"hidden\" name=\"nonce\" id=\"nonce\" value=\"\"/>
		";
		
		return $html;
	}
	
	/**
	 * Process the transaction by calling the payment gateway
	 * @param unknown_type $data
	 * @throws Exception
	 */
	public function processTransaction($data){
		$log = Logger::getInstance();
    	$log->LogDebug("process transaction squareup");
    	
    	$result = new stdClass();
    	$result->status = PAYMENT_ERROR;
    	$result->payment_status = PAYMENT_STATUS_FAILURE;
	
		$nonce = JRequest::getVar("nonce",null);
		
		$transaction_api = new \SquareConnect\Api\TransactionApi();
		$request_body = array (
		
				"card_nonce" => $nonce,
		
				# Monetary amounts are specified in the smallest unit of the applicable currency.
				# This amount is in cents. It's also hard-coded for $1, which is not very useful.
				"amount_money" => array (
						"amount" => intval($data->amount*100),
						"currency" => $data->currency
				),
		
				# Every payment you process for a given business have a unique idempotency key.
				# If you're unsure whether a particular payment succeeded, you can reattempt
				# it with the same idempotency key without worrying about double charging
				# the buyer.
				"idempotency_key" => uniqid()
		);
		
		# The SDK throws an exception if a Connect endpoint responds with anything besides 200 (success).
		# This block catches any exceptions that occur from the request.
		try {
			$transaction = $transaction_api->charge($this->accessToken, $this->locationId, $request_body);
			
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
				
						
			$result->transaction_id = $transaction["id"];
			$result->amount = $data->amount;
			$result->transactionTime = date("Y-m-d", $transaction["created_at"]);
			$result->order_id = $data->id;
			$result->currency= $data->currency;
			$result->processor_type = $this->type;
			$result->payment_method = "card";
		
		} catch (Exception $e) {
			$log->LogDebug($e->getMessage());
		  	$result->error_message = $e->getMessage();
		}
      
		return $result;
	
	}
	
	/**
	 * Get the payment details
	 * @param unknown_type $paymentDetails
	 * @param unknown_type $amount
	 * @param unknown_type $cost
	 */
	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_PROCESSOR_SQUARE_UP',true);
	}
	
	/**
	 * There are no html field
	 */
    public function getHtmlFields() {
    	return "";
    }
}
?>