<?php

/**
 * Title: Buckaroo
 * Description:
 * Copyright: Copyright (c) 2005 - 2012
 * Company: CMSJunkie
 * @author
 * @version 1.0
 */
class Buckaroo implements IPaymentProcessor {


	/**
	 * The payment server URL
	 *
	 * @var string
	 */
	private $paymentServerUrl;

	/**
	 * Currency code
	 */
	private $currencyCode;

	/**
	 * Merchant ID
	 *
	 * @var string N15
	 */
	private $merchantId;

	/**
	 * Normal return URL
	 *
	 * @var string ANS512 url
	 */
	private $normalReturnUrl;

	/**
	 * Amount
	 *
	 * @var string N12
	 */
	private $amount;

	/**
	 * Invoice number
	 *
	 * @var string AN35
	 */
	private $invoiceNumber;

	/**
	 * Culture
	 *
	 * @var string AN35
	 */
	private $culture;

	/**
	 * Payment mean brand list
	 *
	 * @var array
	 */
	private $requestedServices;


	/**
	 * Customer language in ISO 639â€�1 Alpha2
	 *
	 * @doc http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	 * @var string A2
	 */
	private $customerLanguage;


	/**
	 * Secret key
	 *
	 * @var string
	 */
	private $secretKey;

	/**
	 * First Name
	 *
	 * @var string
	 */
	private $firstName;

	/**
	 *
	 * @var string
	 */
	private $lastName;

	/**
	 *
	 * @var string
	 */
	private $email;

	/**
	 *
	 * @var string
	 */
	private $customerGender;

	/**
	 *
	 * @var string
	 */
	private $aditionalService;

	//////////////////////////////////////////////////

	private $paymentUrlTest = 'https://testcheckout.buckaroo.nl/html/';
	private $paymentUrl = 'https://checkout.buckaroo.nl/html/';

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
		$this->secretKey = $data->secretKey;
		$this->merchantId = $data->merchantId;

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
	 * Get transaction reference
	 *
	 * @return string
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	/**
	 * Set transaction reference
	 * AN..max35 (AN = Alphanumeric, free text)
	 *
	 * @param string $transactionReference
	 */
	public function setInvoiceNumber($invoiceNumber) {
		$this->invoiceNumber = substr($invoiceNumber, 0, 255);
	}

	//////////////////////////////////////////////////

	/**
	 * Get customer language
	 *
	 * @return string
	 */
	public function getCulture() {
		return $this->culture;
	}

	/**
	 * Set customer language
	 *
	 * @param string $customerLanguage
	 */
	public function setCulture($culture) {
		$this->culture = $culture;
	}


	/**
	 * Get aditional service
	 *
	 * @return string
	 */
	public function getAditionalService() {
		return $this->aditionalService;
	}

	/**
	 * Set aditional service
	 *
	 * @param string $customerLanguage
	 */
	public function setAditionalService($aditionalService) {
		$this->aditionalService = $aditionalService;
	}

	//////////////////////////////////////////////////

	/**
	 * Add the specified payment service
	 *
	 * @param string $requestedService
	 */
	public function addRequestedService($requestedService) {
		$this->requestedServices[] = $requestedService;
	}

	/**
	 * Get payment mean brand list
	 *
	 * @return string ANS128 listString comma separated list
	 */
	public function getRequestedService() {
		return implode(', ', $this->requestedServices);
	}

	//////////////////////////////////////////////////

	/**
	 * Get data
	 *
	 * @return string
	 */
	public function getData() {

		$data = array(
				// Payment Request - required fields
				'brq_amount' => $this->getAmount() ,
				'brq_culture' => $this->getCulture() ,
				'brq_currency' => $this->getCurrencyCode() ,
				'brq_invoicenumber' => $this->getInvoiceNumber() ,
				'brq_return' => $this->getNormalReturnUrl(),
				'brq_websitekey' => $this->getMerchantId()
				//'brq_requestedservices' => $this->getRequestedService()
		);

		if(count($this->requestedServices)>0){
			$data['brq_requestedservices'] =  $this->getRequestedService();
		}

		if(isset($this->aditionalService)){
			$data['brq_additional_service'] =  $this->aditionalService;
		}

		return $data;
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
		".JText::_('LNG_PROCESSOR_BUCKAROO_INFO',true)."
		</li>
		</ul>";

		return $html;
	}

	//////////////////////////////////////////////////

	/**
	 * Get HTML fields
	 *
	 * @return string
	 */
	public function getHtmlFields() {
		$html  = '';
		$htmlFields =$this->getData();
		$signature = $this->calculateSignature();
		$htmlFields['brq_signature']=$signature;
		foreach($htmlFields as $k=>$v){
			$html .='<input type="hidden" name="'.$k.'" value="'.$v.'" />';
		}
		return $html;
	}


	//////////////////////////////////////////////////

	public function getResponseCodeDescription() {
		return array(
				'00' => 'Transaction success, authorization accepted'

		);
	}

	protected function calculateSignature()
	{
		$origArray = $this->getData();
		unset($origArray['brq_signature']);
		//dump($origArray);
		//sort the array
		$sortableArray = $this->buckarooSort($origArray);
		//dump($sortableArray);
		//turn into string and add the secret key to the end
		$signatureString = '';
		foreach($sortableArray as $key => $value) {
			//dump($key);
			//dump($value);
			$value = urldecode($value);
			$signatureString .= $key . '=' . $value;
		}
		$signatureString .= $this->secretKey;

		//return the SHA1 encoded string for comparison
		$signature = SHA1($signatureString);
			
		return $signature;
	}

	public function buckarooSort($array)
	{
		$arrayToSort = array();
		$origArray = array();
		foreach ($array as $key => $value) {
			$arrayToSort[strtolower($key)] = $value;
			//stores the original value in an array
			$origArray[strtolower($key)] = $key;
		}

		ksort($arrayToSort);

		$sortedArray = array();
		foreach($arrayToSort as $key => $value) {
			//switch the lowercase keys back to their originals
			$key = $origArray[$key];
			$sortedArray[$key] = $value;
		}

		return $sortedArray;
	}

	public function getPaymentGatewayUrl(){
		if($this->mode=="test"){
			return $this->paymentUrlTest;
		}else{
			return $this->paymentUrl;
		}
	}

	public function processTransaction($data){
		$this->normalReturnUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse&processor=buckaroo',false,-1);
		$this->amount = $data->amount;
		$this->itemName = $data->service." ".$data->description;
		$this->itemNumber = $data->order_id;

		$this->setInvoiceNumber($data->order_id);
		$this->setCurrencyCode($data->currency);
		$this->setCulture('nl-NL');

		$result = new stdClass();
		$result->transaction_id = 0;
		$result->amount =  $data->amount;
		$result->payment_date = date("Y-m-d");
		$result->response_code = 0;
		$result->order_id = $data->order_id;
		$result->currency=  $data->currency;
		$result->processor_type = $this->type;
		$result->status = PAYMENT_REDIRECT;
		$result->payment_status = PAYMENT_STATUS_PENDING;

		return $result;
	}


	public function processResponse($params){
		$result = new stdClass();
		
		$result->amount = $params["brq_amount"];
		$result->currencyCode =$params["brq_currency"];
		$result->transaction_id = $params["brq_transactions"];
		$result->order_id =$params["brq_invoicenumber"];
		$result->response_code =$params["brq_statuscode"];
		$result->response_message =$params["brq_statusmessage"];
	
		$result->transactionTime =$params["brq_timestamp"];
		if(isset($params["brq_payment"]))
			$result->transaction_id =$params["brq_payment"];
		if(isset($params["brq_transaction_method"]))
			$result->payment_method = $params["brq_transaction_method"];
	
		if($result->response_code== "190" ){
			$result->status = PAYMENT_SUCCESS;
			$result->payment_status = PAYMENT_STATUS_PAID;
		}else if($result->response_code== "790"
				|| $result->response_code== "791"
				|| $result->response_code== "792"
				|| $result->response_code== "793"){
			$result->status = PAYMENT_WAITING;
			$result->payment_status = PAYMENT_STATUS_WAITING;
		}else if($result->response_code== "890" ){
			$result->status = PAYMENT_CANCELED;
			$result->payment_status = PAYMENT_STATUS_CANCELED;
		}else if($result->response_code== "490" ){
			$result->status = PAYMENT_CANCELED;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}else{
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_CANCELED;
		}
		
		return $result;
	}
	
	public function getPaymentDetails($paymentDetails){
		if(isset($paymentDetails->payment_method) && strlen($paymentDetails->payment_method)>0)
			return JText::_('LNG_BUCKAROO_'.strtoupper($paymentDetails->payment_method)); 
		else
			return JText::_('LNG_BUCKAROO');
	}
}
