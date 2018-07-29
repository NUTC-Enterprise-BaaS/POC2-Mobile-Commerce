<?php

require_once JPATH_COMPONENT_SITE.'/classes/payment/processors/Mollie/API/Autoloader.php';

class Mollie implements IPaymentProcessor {

    var $type;
    var $name;
    var $mode;

    private $apiKey;
    private $mollie;
    private $payment;
    private $paymentUrl;

    var $amount;
    var $description;
    var $redirectUrl;
    var $order_id;

    public function initialize($data){
        $this->apiKey = $data->api_key;
        $this->type = $data->type;
        $this->name = $data->name;
        $this->mode = $data->mode;
    }

    public function getPaymentGatewayUrl(){
        if($this->mode=="test"){
            return $this->paymentUrl;
        }else{
            return $this->paymentUrl;
        }
    }

    public function getPaymentProcessorHtml(){
        $html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_PROCESSOR_MOLLIE_INFO',true)."
		    </li>
		</ul>";

        return $html;
    }

    public function getHtmlFields(){
        return '';
    }

    /**
     * Process the transaction by calling the payment gateway
     * @param unknown_type $data
     * @throws Exception
     */
    public function processTransaction($data){
        $this->amount = $data->amount;
        $this->description = $data->description;
        $this->order_id = $data->id;
        $this->redirectUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse&processor=mollie&orderId='.$this->order_id,false,-1);

        $this->mollie = new Mollie_API_Client;
        $this->mollie->setApiKey($this->apiKey);
        $this->payment = $this->mollie->payments->create(
            array(
                'amount'      => $this->amount,
                'description' => $this->description,
                'redirectUrl' => $this->redirectUrl,
                'metadata'    => array(
                    'order_id' => $this->order_id
                )
            )
        );

        $this->paymentUrl = $this->payment->getPaymentUrl();

        $result = new stdClass();
        $result->transaction_id = $this->payment->id;
        $result->amount = $data->amount;
        $result->payment_date = date("Y-m-d");
        $result->order_id = $data->id;
        $result->currency=  $data->currency;
        $result->processor_type = $this->type;
        $result->status = PAYMENT_REDIRECT;
        $result->payment_status = PAYMENT_STATUS_PENDING;

        return $result;
    }

    public function processResponse($data){
        $result = new stdClass();

        if(isset($data->transaction_id)) {
            $this->mollie = new Mollie_API_Client;
            $this->mollie->setApiKey($this->apiKey);
            $payment = $this->mollie->payments->get($data->transaction_id);

            $result->transaction_id = $data->transaction_id;

            if($payment->isPaid()){
                $result->status = PAYMENT_SUCCESS;
                $result->payment_status = PAYMENT_STATUS_PAID;
                $result->order_id = $payment->metadata->order_id;
                $result->processor_type = $this->type;
            }
            else if(! $payment->isOpen()){
                $result->status = PAYMENT_CANCELED;
                $result->payment_status = PAYMENT_STATUS_CANCELED;
                $result->order_id = $payment->metadata->order_id;
            }
        }
        else{
            $result->status = PAYMENT_ERROR;
            $result->payment_status = PAYMENT_STATUS_FAILURE;
            $result->order_id = $data->order_id;
        }

        $result->processAutomatically = true;
        $result->amount = $data->amount;
        $result->payment_date = date("Y-m-d");

        return $result;
    }

    public function getPaymentDetails($paymentDetails){
        return JText::_('LNG_PROCESSOR_MOLLIE',true);
    }
}
