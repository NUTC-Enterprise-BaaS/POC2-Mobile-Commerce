<?php 

class NoPayment implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	public function initialize($data){
		if(isset($data->type))
			$this->type =  $data->type;
		if(isset($data->name))
			$this->name =  $data->name;	
	}
	
	public function getPaymentGatewayUrl(){
	
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_CASH_PROC_INFO',true)."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
		$html  = '';
		return $html;
	}
	
	/**
	 * Process transaction with no payment
	 * @param unknown_type $data
	 */
	public function processTransaction($data){
	
		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount =   $data->amount;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->order_id = $data->id;
		$result->currency=  $data->currency;
		$result->processor_type = $this->type;
		$result->error_message = JText::_("COM_JBUSINESS_ERROR");
		if($data->amount==0){
			$result->payment_status = PAYMENT_STATUS_PAID;
			$result->status = PAYMENT_SUCCESS;
		}else{
			$result->payment_status = PAYMENT_STATUS_NOT_PAID;
			$result->status = PAYMENT_ERROR;
		}
		
		return $result;
	}
	

	public function getPaymentDetails($paymentDetails){
		//echo JText::_('LNG_PROCESSOR_NO_PAYMENT');
	}
}