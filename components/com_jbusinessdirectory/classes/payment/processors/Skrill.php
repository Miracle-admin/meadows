<?php

class Skrill{    

	var $type;
	var $name;
	
	var $paypal_email;
	var $mode;
	var $paymentUrlTest = 'https://www.moneybookers.com/app/payment.pl';
	var $paymentUrl = 'https://www.moneybookers.com/app/payment.pl';
	
	var $notifyUrl;
	var $returnUrl;
	var $cancelUrl;
	var $userData;
	var $status_url;
	var $errorsArray=array(
		"1"=>"Referred",
		"2"=>"Invalid merchant number",
		"3"=>"Pick-up card",
		"4"=>"Authorisation declined",
		"5"=>"Other error",
		"6"=>"CVV is mandatory, but not set or invalid",
		"7"=>"Approved authorisation, honour with identification",
		"8"=>"Delayed processing",
		"09"=>"Invalid transaction",
		"10"=>"Invalid currency",
		"11"=>"Invalid amount / available limit exceeded / amount too high",
		"12"=>"Invalid credit card or bank account",
		"13"=>"Invalid card Issuer",
		"14"=>"Annulation by client",
		"15"=>"Duplicate transaction",
		"16"=>"Acquirer error",
		"17"=>"Reversal not processed, matching authorisation not found",
		"18"=>"File transfer not available/unsuccessful",
		"19"=>"Reference number error",
		"20"=>"Access denied",
		"21"=>"File transfer failed",
		"22"=>"Format error",
		"23"=>"Unknown acquirer",
		"24"=>"Card expired",
		"25"=>"Fraud suspicion",
		"26"=>"Security code expired",
		"27"=>"Requested function not available",
		"28"=>"Lost/stolen card",
		"29"=>"Stolen card, pick up",
		"30"=>"Duplicate authorisation",
		"31"=>"Limit exceeded",
		"32"=>"Invalid Security Code",
		"33"=>"Unknown or Invalid Card/Bank account",
		"34"=>"Illegal Transaction",
		"35"=>"Transaction Not Permitted",
		"36"=>"Card blocked in local blacklist",
		"37"=>"Restricted card/bank account",
		"38"=>"Security rules violation",
		"39"=>"The transaction amount of the referencing transaction is higher than the transaction amount of the original transaction",
		"40"=>"Transaction frequency limit exceeded, override is possible",
		"41"=>"Incorrect usage count in the Authorisation System exceeded",
		"42"=>"Card blocked",
		"43"=>"Rejected by Credit Card Issuer",
		"44"=>"Card Issuing Bank or Network is not available",
		"45"=>"The card type is not processed by the authorisation centre / Authorisation System has determined incorrect routing",
		"47"=>"Processing temporarily not possible",
		"48"=>"Security Breach",
		"49"=>"Date / time not plausible, trace-no. not increasing",
		"50"=>"Error in PAC encryption detected",
		"51"=>"System error",
		"52"=>"MB denied - potential fraud",
		"53"=>"Mobile verification failed",
		"54"=>"Failed due to internal security restrictions",
		"55"=>"Communication or verification problem",
		"56"=>"3D verification failed",
		"57"=>"AVS check failed",
		"58"=>"Invalid bank code",
		"59"=>"Invalid account code",
		"60"=>"Card not authorised",
		"61"=>"No credit worthiness",
		"62"=>"Communication error",
		"63"=>"Transaction not allowed for cardholder",
		"64"=>"Invalid data in request",
		"65"=>"Blocked bank code",
		"66"=>"CVV2/CVC2 failure",
		"99"=>"General error");
	
	public function initialize($data){
		if (!function_exists('curl_init')) {
			throw new Exception('Skrill needs the CURL PHP extension.');
		}
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->merchant_email = $data->fields['merchant_email'];
		
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
						".JText::_('LNG_PROCESSOR_SKRILL_INFO',true)."
					</li>
				</ul>";
	
		return $html;
	}
	
	
	
	public function getHtmlFields() {
	
		$sessionId = $this->startSession();
			
		$html  = '';
		$html .= sprintf('<input type="hidden" name="pay_to_email" id="pay_to_email" value="%s">',$this->merchant_email);
		$html .= sprintf('<input type="hidden" name="sid" id="sid" value="%s">',$sessionId);
		$html .= sprintf('<input type="hidden" name="transaction_id" id="transaction_id" value="%s">',$this->itemNumber);
		$html .= sprintf('<input type="hidden" name="cancel_url" id="notify_url" value="%s">', $this->cancelUrl);
		$html .= sprintf('<input type="hidden" name="return_url" id="return_url" value="%s">', $this->notifyUrl);
		$html .= sprintf('<input type="hidden" name="status_url" id="status_url" value="%s">', $this->status_url);
		$html .= sprintf('<input type="hidden" name="return_url_target" id="return_url_target" value="2">');
		$html .= sprintf('<input type="hidden" name="cancel_url_target" id="cancel_url_target" value="2">');
		$html .= sprintf('<input type="hidden" name="language" id="language" value="EN">');
		$html .= sprintf('<input type="hidden" name="return_url_text" id="return_url_text" value="%s">', JText::_('LNG_CONTINUE_RESERVATION'));
		$html .= sprintf('<input type="hidden" name="firstname" id="firstname" value="%s">', $this->userData->first_name);
		$html .= sprintf('<input type="hidden" name="lastname" id="lastname" value="%s">', $this->userData->last_name);
		$html .= sprintf('<input type="hidden" name="address" id="address" value="%s">', $this->userData->address);
		$html .= sprintf('<input type="hidden" name="phone_number" id="phone_number" value="%s">', $this->userData->phone);
		$html .= sprintf('<input type="hidden" name="postal_code" id="postal_code" value="%s">', $this->userData->postal_code);
		$html .= sprintf('<input type="hidden" name="city" id="city" value="%s">', $this->userData->city);
		$html .= sprintf('<input type="hidden" name="country" id="country" value="%s">', $this->userData->country);
		$html .= sprintf('<input type="hidden" name="pay_from_email" id="pay_from_email" value="%s">', $this->userData->email);
		
		
		$html .= sprintf('<input type="hidden" name="amount" id="amount" value="%s">', $this->amount);
		$html .= sprintf('<input type="hidden" name="currency" id="currency" value="EUR">');
		$html .= sprintf('<input type="hidden" name="detail1_description" id="detail1_description" value="%s">', $this->itemName);
		$html .= sprintf('<input type="hidden" name="detail1_text" id="detail1_text" value="%s">', $this->itemName);
		$html .= sprintf('<input type="hidden" name="confirmation_note" id="confirmation_note" value="%s">', $this->itemName);
	
		return $html;
	}
	
	public function startSession(){
		try{
	
			$this->cancelUrl.="&processor=skrill";
			$this->status_url.="&processor=skrill";
	
			$url = "https://www.moneybookers.com/app/payment.pl";
			$url.="?pay_to_email=".$this->merchant_email."&language=EN&prepare_only=1&currency=EUR";
			$url.="&transaction_id=".$this->itemNumber;
			$url.="&amount=".$this->amount;
			$url.="&detail1_text=".urlencode($this->itemName)."&confirmation_note=".urlencode($this->itemName);
			$url.="&return_url_target=2&cancel_url_target=2";
			$url.="&firstname=".$this->userData->first_name;
			$url.="&lastname=".$this->userData->last_name;
			$url.="&address=".urlencode($this->userData->address);
			$url.="&phone_number=".$this->userData->phone;
			$url.="&postal_code=".$this->userData->postal_code;
			$url.="&city=".$this->userData->city;
			$url.="&return_url_text=".urlencode(JText::_('LNG_CONTINUE_RESERVATION'));
			$url.="&state=".$this->userData->state;
			$url.="&country=".$this->userData->country;
			$url.="&pay_from_email=".$this->userData->email;
	
			$url.="&return_url=".urlencode($this->notifyUrl);
			$url.="&cancel_url=".urlencode($this->cancelUrl);
			$url.="&status_url=".urlencode($this->status_url);
	
			$ch = curl_init();
			$timeout = 10;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$rawdata = curl_exec($ch);
			curl_close($ch);
	
			$this->log->LogDebug("process transaction skrill - ");
	
			return $rawdata;
		}
		catch(Exception $e){
			print_r($e);exit;
		}
	}
	
	

	public function processTransaction($data)
    {
    	
    	$this->returnUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse',false,-1);
    	$this->notifyUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processAutomaticResponse',false,-1);
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
    	$this->log->LogDebug("process response skrill - ".serialize($data));
    	$status = $data['status'];
    	$result = new stdClass();
    	//transaction approved
    	if($status==2){
    		$result->status = PAYMENT_SUCCESS;
    		$result->payment_status = PAYMENT_STATUS_PAID;
    
    	}//Token,hash de-activation -0  && Declined = 2
    	else if($status==-2){
    		$result->status = PAYMENT_ERROR;
    		$result->payment_status = PAYMENT_STATUS_FAILURE;
    		$result->error_message=$errorsArray[$data['failed_reason_code']];
    	}
    	else if($status==0){
    		$result->status = PAYMENT_CANCELED;
    		$result->payment_status = PAYMENT_STATUS_CANCELED;
    		$result->error_message=$errorsArray[$data['failed_reason_code']];
    	}
    
    	$result->transaction_id = $data["transaction_id"];
    	$result->amount = $data["amount"];
    	$result->payment_date = date('Y-m-d H:i:s');
    	$result->payment_method= $this->type;
    	$result->response_code = $data['failed_reason_code'];
    	$result->confirmation_id = $result->transaction_id ;
    	$result->currency= $data["mc_currency"];
    	$result->processor_type = $this->type;
    	 
    	return $result;
    }
    
   	public function getPaymentDetails($paymentDetails, $amount, $cost){
		return JText::_('LNG_PROCESSOR_SKRILL',true);
	}

}
?>