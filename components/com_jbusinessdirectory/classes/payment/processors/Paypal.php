<?php 

class Paypal implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	var $paypal_email;
	var $mode;
	var $paymentUrlTest = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	var $paymentUrl = 'https://www.paypal.com/cgi-bin/webscr';
	
	var $notifyUrl;
	var $returnUrl;
	var $cancelUrl;
	
	var $currencyCode;
	var $amount;
	var $itemNumber;
	var $itemName;
	

	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->paypal_email = $data->paypal_email;
	}
	
	
	public function getPaymentGatewayUrl(){
		if($this->mode=="test"){
			return $this->paymentUrlTest;
		}else{
			return $this->paymentUrl;
		}
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_PROCESSOR_PAYPAL_INFO',true)."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
		$html  = '';
		$html .= sprintf('<input type="hidden" name="cmd" id="cmd" value="_xclick"/>');
		$html .= sprintf('<input type="hidden" name="charset" id="charset" value="utf-8">');
		$html .= sprintf('<input type="hidden" name="item_name" id="item_name" value="%s">', $this->itemName);
		$html .= sprintf('<input type="hidden" name="item_number" id="item_number" value="%s">', $this->itemNumber);
		$html .= sprintf('<input type="hidden" name="no_shipping" id="no_shipping" value="1">');
		$html .= sprintf('<input type="hidden" name="business" id="business" value="%s">',$this->paypal_email);
		$html .= sprintf('<input type="hidden" name="cbt" id="cbt" value="%s">',JText::_('LNG_FINALIZE_PAYMENT',true));
		
		$html .= sprintf('<input type="hidden" name="notify_url" id="notify_url" value="%s">', $this->notifyUrl);
		$html .= sprintf('<input type="hidden" name="return" id="return" value="%s">', $this->returnUrl);
		$html .= sprintf('<input type="hidden" name="cancel_return" id="cancel_return" value="%s">', $this->cancelUrl);
		
		$html .= sprintf('<input type="hidden" name="amount" value="%.2f" />', $this->amount);
		$html .= sprintf('<input type="hidden" name="currency_code" value="%s" />', $this->currencyCode);
		$html .= sprintf('<input type="hidden" name="custom" value="%s" />', $this->itemNumber);
	
		return $html;
	}
	
	public function processTransaction($data){
		$this->returnUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse&processor=paypal',false,-1);
		$this->notifyUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processAutomaticResponse&processor=paypal',false,-1);
		$this->cancelUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processCancelResponse',false,-1);;
		$this->amount = $data->amount;
		$this->itemName = $data->service." ".$data->description;
		$this->itemNumber = $data->id;
		$this->currencyCode = $data->currency;
		
		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount =  $data->amount;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->order_id = $data->id;
		$result->currency=  $data->currency;
		$result->processor_type = $this->type;
		$result->status = PAYMENT_REDIRECT;
		$result->payment_status = PAYMENT_STATUS_PENDING;
		
		return $result;
	}
	
	
	public function processResponse($data){
		$result = new stdClass();
		$result->transaction_id = $data["txn_id"];
		$result->amount = $data["mc_gross"];
		$result->transactionTime = date("Y-m-d", strtotime($data["payment_date"]));
		$result->response_code = $data["payment_status"];
		$result->response_message = "";
		$result->order_id = $data["item_number"];
		$result->currency= $data["mc_currency"];
		$result->processor_type = $this->type;
		$result->payment_method = "";
		$result->status = PAYMENT_SUCCESS;
		$result->payment_status = PAYMENT_STATUS_PAID;
		
		return $result;
	}

	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_PROCESSOR_PAYPAL',true);
	}
}