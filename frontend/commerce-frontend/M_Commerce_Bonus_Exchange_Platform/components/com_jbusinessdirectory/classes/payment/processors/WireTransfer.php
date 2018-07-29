<?php 

class WireTransfer implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		if(isset($data->fields))
			$this->fields = $data->fields;
	}
	
	public function getPaymentGatewayUrl(){
	
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		".JText::_('LNG_WIRE_TRANSFER_INFO',true)."
				</li>
				</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
		$html  = '';
		return $html;
	}
	
	public function processTransaction($data){
		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount = $data->amount;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->order_id = $data->id;
		$result->currency=  $data->currency;
		$result->processor_type = $this->type;
		$result->status = PAYMENT_WAITING;
		$result->payment_status = PAYMENT_STATUS_WAITING;

		return $result;
	}
	
	public function processResponse($data){
		$result = new stdClass();
			
		return $result;
	}

	public function getPaymentDetails($paymentDetails){
		$result = "";
		
		ob_start();
		?>

		<br/><br/>
		<TABLE>
			<?php
			if(!empty($this->fields))
				foreach($this->fields as $column=>$value){?>
					<TR>
						<TD align=left width=40% nowrap>
							<b><?php echo JText::_('LNG_'.strtoupper($column),true);?> :</b>
						</TD>
						<TD>
							<?php echo $value?>
						</TD>
					</TR>
				<?php } ?>
		</TABLE>
		<br/><br/>

		<?php
		$result = $result.ob_get_contents();
		
		ob_end_clean();
		
		return $result;
	}
}