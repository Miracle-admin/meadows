<?php 

class EWay implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	var $customerId;
	var $userName;
	
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
		if (!function_exists('curl_init')) {
			throw new Exception('Authorize.net needs the CURL PHP extension.');
		}
		
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->customerId = $data->customer_id;
		$this->userName = $data->user_name;
	}
	
	public function getPaymentGatewayUrl(){
		return $this->paymentUrl;
	}
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".JText::_('LNG_PROCESSOR_EWAY_INFO',true)."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
		$html  = '';
	
		return $html;
	}
	
	function fetch_data($string, $start_tag, $end_tag)
	{
		$position = stripos($string, $start_tag);
		$str = substr($string, $position);
		$str_second = substr($str, strlen($start_tag));
		$second_positon = stripos($str_second, $end_tag);
		$str_third = substr($str_second, 0, $second_positon);
		$fetch_data = trim($str_third);
		return $fetch_data;
	}
	
	public function processTransaction($data){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$language = JBusinessUtil::getCurrentLanguageCode();
		$this->returnUrl = urlencode(JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse&processor=eway',false,-1));
		$this->cancelUrl = urlencode(JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processCancelResponse&processor=eway',false,-1));
		
		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount = $data->amount;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->order_id = $data->id;
		$result->currency=  $data->currency;
		$result->processor_type = $this->type;
		
		$ewayurl="";
		$ewayurl.="?CustomerID=".$this->customerId;
		$ewayurl.="&UserName=".$this->userName;
		$ewayurl.="&Amount=".$data->amount;
		$ewayurl.="&Currency=".$data->currency;
		$ewayurl.="&PageTitle=".$data->service;
		$ewayurl.="&PageDescription=".$data->service." ".$data->description;
		$ewayurl.="&Language=". $language;
		
		$ewayurl.="&CompanyName=".$appSettings->company_name;
		$ewayurl.="&CustomerFirstName=".$data->billingDetails->first_name;
		$ewayurl.="&CustomerLastName=".$data->billingDetails->last_name;
		$ewayurl.="&CustomerAddress=".$data->billingDetails->address;
		$ewayurl.="&CustomerCity=".$data->billingDetails->city;
		$ewayurl.="&CustomerState=".$data->billingDetails->region;
		$ewayurl.="&CustomerPostCode=".$data->billingDetails->postal_code;
		$ewayurl.="&CustomerCountry=".$data->billingDetails->country;
		$ewayurl.="&CustomerPhone=".$data->billingDetails->phone;
		$ewayurl.="&CustomerEmail=".$data->billingDetails->email;
		
		$ewayurl.="&InvoiceDescription=".$data->service." ".$data->description;
		$ewayurl.="&CancelURL=".$this->cancelUrl;
		$ewayurl.="&ReturnUrl=".$this->returnUrl ;
		//$ewayurl.="&CompanyLogo=".$_POST['CompanyLogo'];
		//$ewayurl.="&PageBanner=".$_POST['(PageBanner'];
		$ewayurl.="&MerchantReference=".$data->id;
		//$ewayurl.="&MerchantInvoice=".$_POST['Invoice'];

		$spacereplace = str_replace(" ", "%20", $ewayurl);
		$posturl="https://nz.ewaygateway.com/Request/$spacereplace";
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if (defined("CURL_PROXY_REQUIRED") && CURL_PROXY_REQUIRED == 'True'){
			$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		}
		
		$response = curl_exec($ch);
		$responsemode = $this->fetch_data($response, '<result>', '</result>');
		$responseurl = $this->fetch_data($response, '<uri>', '</uri>');
		$this->paymentUrl = $responseurl;
		 
		if($responsemode=="True"){
			$result->status = PAYMENT_REDIRECT;
			$result->payment_status = PAYMENT_STATUS_PENDING;
		}else{
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}

		return $result;
	}
	
	
	public function processResponse($data){
		$accessPaymentCode = JRequest::getVar("AccessPaymentCode");
		$querystring="CustomerID=$this->customerId&UserName=$this->userName&AccessPaymentCode=".$accessPaymentCode;
		$posturl="https://nz.ewaygateway.com/Result/?".$querystring;
				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if (defined("CURL_PROXY_REQUIRED") && CURL_PROXY_REQUIRED == 'True'){
			$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		}
	
		$response = curl_exec($ch);

		$authecode = $this->fetch_data($response, '<authCode>', '</authCode>');
		$responsecode = $this->fetch_data($response, '<responsecode>', '</responsecode>');
		$retrunamount = $this->fetch_data($response, '<returnamount>', '</returnamount>');
		$trxnnumber = $this->fetch_data($response, '<trxnnumber>', '</trxnnumber>');
		$trxnstatus = $this->fetch_data($response, '<trxnstatus>', '</trxnstatus>');
		$trxnresponsemessage = $this->fetch_data($response, '<trxnresponsemessage>', '</trxnresponsemessage>');
		
		$merchantoption1 = $this->fetch_data($response, '<merchantoption1>', '</merchantoption1>');
		$merchantoption2 = $this->fetch_data($response, '<merchantoption2>', '</merchantoption2>');
		$merchantoption3 = $this->fetch_data($response, '<merchantoption3>', '</merchantoption3>');
		$merchantreference = $this->fetch_data($response, '<merchantreference>', '</merchantreference>');
		$merchantinvoice = $this->fetch_data($response, '<merchantinvoice>', '</merchantinvoice>');
		
		$result = new stdClass();
		$result->transaction_id = $trxnnumber;
		$result->amount = $retrunamount;
		$result->transactionTime = date("Y-m-d hh:mm:ss");
		$result->response_code = $responsecode;
		$result->response_message = $trxnresponsemessage;
		$result->order_id = $merchantreference;
		//$result->currency= $data["mc_currency"];
		$result->processor_type = $this->type;
		$result->payment_method = "";
		
		if($result->response_code=="00"){
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
			$result->processAutomatically = 1;
		}else{
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}
		
		return $result;
	}

	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_PROCESSOR_EWAY',true);
	}
}