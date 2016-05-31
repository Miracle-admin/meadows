<?php 

class TwoCheckout implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	var $accountNumber;
	var $secreatWord;
	var $mode;
	var $paymentUrlTest = ' https://sandbox.2checkout.com/checkout/purchase';
	var $paymentUrl = 'https://www.2checkout.com/checkout/purchase';
	
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
		$this->accountNumber = $data->account_number;
		$this->secreatWord = $data->secret_word;
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
		    ".JText::_('LNG_PROCESSOR_2CHECKOUT_INFO',true)."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
		$language = JBusinessUtil::getCurrentLanguageCode();
		
		$html  = '';
		$html .= sprintf('<input type="hidden" name="sid" id="sid" value="%s">', $this->accountNumber);
		$html .= sprintf('<input type="hidden" name="mode" id="mode" value="2CO">');
		$html .= sprintf('<input type="hidden" name="li_0_type" id="li_#_type" value="product">');
		$html .= sprintf('<input type="hidden" name="li_0_name" id="li_#_name" value="%s">', $this->itemName);
		$html .= sprintf('<input type="hidden" name="li_0_tangible" id="li_#_tangible" value="N">');
		$html .= sprintf('<input type="hidden" name="li_0_quantity" id="li_#_quantity" value="1">');
		
		$html .= sprintf('<input type="hidden" name="x_receipt_link_url" id="x_receipt_link_url" value="%s">', $this->returnUrl);
		
		$html .= sprintf('<input type="hidden" name="li_0_price" value="%.2f" />', $this->amount);
		$html .= sprintf('<input type="hidden" name="currency_code" value="%s" />', $this->currencyCode);
		$html .= sprintf('<input type="hidden" name="merchant_order_id" value="%s" />', $this->itemNumber);
		$html .= sprintf('<input type="hidden" name="lang" value="%s" />', $language);
		
		$html .= sprintf('<input type="hidden" name="street_address" value="%s" />', $this->billingDetails->address);
		$html .= sprintf('<input type="hidden" name="city" value="%s" />', $this->billingDetails->city);
		$html .= sprintf('<input type="hidden" name="state" value="%s" />', $this->billingDetails->region);
		$html .= sprintf('<input type="hidden" name="zip" value="%s" />', $this->billingDetails->postal_code);
		$html .= sprintf('<input type="hidden" name="country" value="%s" />', $this->billingDetails->country);
		$html .= sprintf('<input type="hidden" name="phone" value="%s" />', $this->billingDetails->phone);
		$html .= sprintf('<input type="hidden" name="card_holder_name" value="%s" />', $this->billingDetails->first_name." ".$this->billingDetails->last_name);
		$html .= sprintf('<input type="hidden" name="email" value="%s" />', $this->billingDetails->email);
		
		return $html;
	}
	
	public function processTransaction($data){
		$this->returnUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse&processor=twocheckout',false,-1);
	
		$this->amount = $data->amount;
		$this->itemName = $data->service." ".$data->description;
		$this->itemNumber = $data->id;
		$this->currencyCode = $data->currency;
		
		$this->billingDetails = $data->billingDetails;
		
		
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
		$result->transaction_id = $data["invoice_id"];
		$result->amount = $data["total"];
		$result->transactionTime = date("Y-m-d");
		//$result->response_code = $data["payment_status"];
		$result->response_message = "";
		$result->order_id = $data["merchant_order_id"];
		$result->currency= $data["currency_code"];
		$result->processor_type = $this->type;
		$result->payment_method = $data["pay_method"];
		
		if($this->validateResponse($data)){
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
			$result->processAutomatically = 1;
		}else{
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}
		
		return $result;
	}
	
	public function validateResponse($data){
		
		$hashSecretWord = $this->secreatWord;
		$hashSid = $this->accountNumber;
		$hashTotal = $data["total"];
		$hashOrder = $data["order_number"];
		$stringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
		
		if ($stringToHash != $data['key']) {
			$result = false;//'Fail - Hash Mismatch';
		} else {
			$result = true;//'Success - Hash Matched';
		}
		
		return  $result;
	}

	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_PROCESSOR_PAYPAL',true);
	}
}