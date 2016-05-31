<?php

/**
 * Title: Cardsave
 * Description:
 * Copyright: Copyright (c) 2005 - 2012
 * Company: CMSJunkie
 * @author
 * @version 1.0
 */
class Cardsave implements IPaymentProcessor {


	/**
	 * The payment server URL
	 *
	 * @var string
	 */
	private $paymentServerUrl;
	
	/**
	 * Normal return URL
	 *
	 * @var string ANS512 url
	 */
	private $normalReturnUrl;

	/**
	 * Secret key
	 *
	 * @var string
	 */
	private $password;
	
	/**
	 * Merchant ID
	 *
	 * @var string N15
	 */
	private $merchantId;

	/**
	 *
	 * @var string
	 */
	private $email;

	/**
	 *
	 * @var string
	 */
	private $transactionType;

	//////////////////////////////////////////////////

	private $paymentUrlTest = 'https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx';
	private $paymentUrl = 'https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx';

	/**
	 * Constructs and initalize an Buckaroo object
	 */
	public function __construct() {
		$this->requestedServices = array();

	}
 
	/**
	 * Initialize processor with all data
	 * @param $data
	 */
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->preSharedKey = $data->preSharedKey;
		$this->password = $data->password;
		$this->merchantId = $data->merchantId;
		$transactionType ="SALE";

	}
	//////////////////////////////////////////////////

	/**
	 * Get the payment server URL
	 *
	 * @return the payment server URL
	 */
	public function getPaymentServerUrl() {
		return $this->paymentServerUrl;
	}

	/**
	 * Set the payment server URL
	 *
	 * @param string $url an URL
	 */
	public function setPaymentServerUrl($url) {
		$this->paymentServerUrl = $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the currency numeric code
	 *
	 * @return string currency numeric code
	 */
	public function getCurrencyCode() {
		return $this->currencyCode;
	}

	/**
	 * Set the currency code
	 *
	 * @param string $currencyCode
	 */
	public function setCurrencyCode($currencyCode) {
		$this->currencyCode = $currencyCode;
	}

	//////////////////////////////////////////////////

	/**
	 * Get merchant ID
	 *
	 * @return string
	 */
	public function getMerchantId() {
		return $this->merchantId;
	}

	/**
	 * Set the merchant ID
	 *
	 * @param string $merchantdId
	 */
	public function setMerchantId($merchantId) {
		$this->merchantId = $merchantId;
	}

	//////////////////////////////////////////////////

	/**
	 * Get normal return URL
	 *
	 * @return string
	 */
	public function getNormalReturnUrl() {
		return $this->normalReturnUrl;
	}

	/**
	 * Set the normal return URL
	 *
	 * LET OP! De URL mag geen parameters bevatten.
	 *
	 * @param string $normalReturnUrl
	 */
	public function setNormalReturnUrl($normalReturnUrl) {
		$this->normalReturnUrl = $normalReturnUrl;
	}

	//////////////////////////////////////////////////

	/**
	 * Get amount
	 *
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Get formmated amount
	 *
	 * @return int
	 */
	public function getFormattedAmount() {
		return round($this->amount * 100);
	}

	/**
	 * Set amount
	 *
	 * @param float $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}
	
	//////////////////////////////////////////////////

	
	/**
	 * Get secret key
	 *
	 * @return string
	 */
	public function getSecretKey() {
		return $this->secretKey;
	}

	/**
	 * Set secret key
	 *
	 * @return string
	 */
	public function setSecretKey($secretKey) {
		$this->secretKey = $secretKey;
	}

	/**
	 * Get payment processor html content
	 */
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		".JText::_('LNG_PROCESSOR_CARDSAVE_INFO',true)."
		</li>
		</ul>";

		return $html;
	}



	protected function getHash()
	{
		$HashString="PreSharedKey=" . $this->preSharedKey;
		$HashString=$HashString . '&MerchantID=' . $this->merchantId;
		$HashString=$HashString . '&Password=' . $this->password;
		$HashString=$HashString . '&Amount=' .  $this->getFormattedAmount();
		$HashString=$HashString . '&CurrencyCode=' .$this->currencyCode;
		$HashString=$HashString . '&EchoAVSCheckResult=' . 'true';
		$HashString=$HashString . '&EchoCV2CheckResult=' .'true';
		$HashString=$HashString . '&EchoThreeDSecureAuthenticationCheckResult=' . 'true';
		$HashString=$HashString . '&EchoCardType=' . 'true';
		$HashString=$HashString . '&OrderID=' . $this->orderId;
		$HashString=$HashString . '&TransactionType=' . $this->transactionType;
		$HashString=$HashString . '&TransactionDateTime=' . $this->transactionDateTime;
		$HashString=$HashString . '&CallbackURL=' . $this->returnUrl;
		
		$HashString=$HashString . '&OrderDescription=' . $this->orderDescription;
		$HashString=$HashString . '&CustomerName=' . '';
		$HashString=$HashString . '&Address1=' . '';
		$HashString=$HashString . '&Address2=' . '';
		$HashString=$HashString . '&Address3=' . '';
		$HashString=$HashString . '&Address4=' . '';
		$HashString=$HashString . '&City=' . '';
		$HashString=$HashString . '&State=' . '';
		$HashString=$HashString . '&PostCode=' . '';
		$HashString=$HashString . '&CountryCode=' . '';
		$HashString=$HashString . '&EmailAddress=' . '';  
		$HashString=$HashString . '&PhoneNumber=' . '';
		
		$HashString=$HashString . '&EmailAddressEditable=false';
		$HashString=$HashString . '&PhoneNumberEditable=false';
		$HashString=$HashString . "&CV2Mandatory=false";
		$HashString=$HashString . "&Address1Mandatory=false";
		$HashString=$HashString . "&CityMandatory=false";
		$HashString=$HashString . "&PostCodeMandatory=false";
		$HashString=$HashString . "&StateMandatory=false";
		$HashString=$HashString . "&CountryMandatory=false";
		$HashString=$HashString . "&ResultDeliveryMethod=" . 'SERVER'; 
		$HashString=$HashString . "&ServerResultURL=" . $this->notifyUrl;
		$HashString=$HashString . "&PaymentFormDisplaysResult=" . 'false';
		$HashString=$HashString . "&ServerResultURLCookieVariables=" . '';
		$HashString=$HashString . "&ServerResultURLFormVariables=" . '';
		$HashString=$HashString . "&ServerResultURLQueryStringVariables=" . '';
		$HashDigest=sha1($HashString);	
		
		return $HashDigest;
	}

	public function getPaymentGatewayUrl(){
		if($this->mode=="test"){
			return $this->paymentUrlTest;
		}else{
			return $this->paymentUrl;
		}
	}

	function gatewaydatetime()
	{
		$str= date('Y-m-d H:i:s O');
		return $str;
	}
	
	public function getHtmlFields() {
		$html  = '';
		$this->transactionDateTime = $this->gatewaydatetime();
		
		$html .= sprintf('<input type="hidden" name="HashDigest" value="%s"/>',$this->getHash());
		$html .= sprintf('<input type="hidden" name="MerchantID" value="%s"/>', $this->merchantId);
		$html .= sprintf('<input type="hidden" name="Amount" value="%s">', $this->getFormattedAmount());
		$html .= sprintf('<input type="hidden" name="CurrencyCode" value="%s">', $this->currencyCode);
		$html .= sprintf('<input type="hidden" name="EchoAVSCheckResult" value="true">');
		$html .= sprintf('<input type="hidden" name="EchoCV2CheckResult" value="true">');
		$html .= sprintf('<input type="hidden" name="EchoThreeDSecureAuthenticationCheckResult" value="true">');
		$html .= sprintf('<input type="hidden" name="EchoCardType" value="true">');
		$html .= sprintf('<input type="hidden" name="OrderID" value="%s">', $this->orderId);
		
		$html .= sprintf('<input type="hidden" name="TransactionType" value="%s">',$this->transactionType);
		$html .= sprintf('<input type="hidden" name="TransactionDateTime" value="%s">',$this->transactionDateTime);
		$html .= sprintf('<input type="hidden" name="CallbackURL" value="%s">', $this->returnUrl);
		$html .= sprintf('<input type="hidden" name="OrderDescription" value="%s" />', $this->orderDescription);
	
		$html .= sprintf('<input type="hidden" name="CustomerName" value="">');
		$html .= sprintf('<input type="hidden" name="Address1" value="">');
		$html .= sprintf('<input type="hidden" name="Address2" value="">');
		$html .= sprintf('<input type="hidden" name="Address3" value="">');
		$html .= sprintf('<input type="hidden" name="Address4" value="">');
		$html .= sprintf('<input type="hidden" name="City" value="">');
		$html .= sprintf('<input type="hidden" name="State" value="">');
		$html .= sprintf('<input type="hidden" name="PostCode" value="">');
		$html .= sprintf('<input type="hidden" name="CountryCode" value="">');
		$html .= sprintf('<input type="hidden" name="EmailAddress" value="">');
		$html .= sprintf('<input type="hidden" name="PhoneNumber" value="">');
		
		$html .= sprintf('<input type="hidden" name="EmailAddressEditable" value="false">');
		$html .= sprintf('<input type="hidden" name="PhoneNumberEditable" value="false">');
		$html .= sprintf('<input type="hidden" name="CV2Mandatory" value="false">');
		$html .= sprintf('<input type="hidden" name="Address1Mandatory" value="false">');
		$html .= sprintf('<input type="hidden" name="CityMandatory" value="false">');
		$html .= sprintf('<input type="hidden" name="PostCodeMandatory" value="false">');
		$html .= sprintf('<input type="hidden" name="StateMandatory" value="false">');
		$html .= sprintf('<input type="hidden" name="CountryMandatory" value="false">');
		
		$html .= sprintf('<input type="hidden" name="ResultDeliveryMethod" value="SERVER">');
		$html .= sprintf('<input type="hidden" name="ServerResultURL" id="ServerResultURL" value="%s">', $this->notifyUrl);
		$html .= sprintf('<input type="hidden" name="PaymentFormDisplaysResult" value="false">');
		$html .= sprintf('<input type="hidden" name="ServerResultURLCookieVariables" value="">');
		$html .= sprintf('<input type="hidden" name="ServerResultURLFormVariables" value="">');
		$html .= sprintf('<input type="hidden" name="ServerResultURLQueryStringVariables" value="">');
		$html .= sprintf('<input type="hidden" name="ThreeDSecureCompatMode" value="false">');
		$html .= sprintf('<input type="hidden" name="ServerResultCompatMode" value="false">');
		
		return $html;
	}
	
	public function processTransaction($data){
		$this->returnUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processCardSaveResponse',false,-1);
		$this->notifyUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processCardSaveAutomaticResponse',false,-1);
		$this->amount = $data->amount;
		$this->orderDescription = $data->service." ".$data->description;
		$this->orderId = $data->id;
		$this->transactionType = "SALE";
		$this->setCurrencyCode(826);

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


	public function processResponse($params){
		$result = new stdClass();
		
		$result->amount = $params["Amount"];
		$result->currencyCode =$params["CurrencyCode"];
		$result->transaction_id = $params["CrossReference"];
		$result->order_id =$params["OrderID"];
		$result->response_code =$params["StatusCode"];
		$result->response_message =$params["Message"];
	
		$result->transactionTime =$params["TransactionDateTime"];
		$result->payment_method = "Card";
	
		if($result->response_code== "0" ){
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
		}else {
			$result->status = PAYMENT_CANCELED;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}
		
		return $result;
	}
	
	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_CARD_SAVE');
	}
}
