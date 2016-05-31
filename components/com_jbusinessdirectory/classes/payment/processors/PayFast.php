<?php 

class PayFast implements IPaymentProcessor {

	var $type;
	var $name;

	var $merchant_id;
	var $merchant_key;
	var $mode;
	var $paymentUrlTest = 'https://sandbox.payfast.co.za/eng/process';
	var $paymentUrl = 'https://www.payfast.co.za/eng/process';

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
		$this->merchant_id = $data->merchant_id;
		$this->merchant_key = $data->merchant_key;
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
		".JText::_('LNG_PROCESSOR_PAYFAST_INFO',true)."
		</li>
		</ul>";

		return $html;
	}

	public function getHtmlFields() {
		$html  = '';

		$html .= sprintf('<input type="hidden" name="merchant_id" id="merchant_id" value="%s">', $this->merchant_id);
		$html .= sprintf('<input type="hidden" name="merchant_key" id="merchant_key" value="%s">', $this->merchant_key);

		$html .= sprintf('<input type="hidden" name="return_url" id="return_url" value="%s">', $this->returnUrl);
		$html .= sprintf('<input type="hidden" name="cancel_url" id="cancel_url" value="%s">', $this->cancelUrl);
		$html .= sprintf('<input type="hidden" name="notify_url" id="notify_url" value="%s">', $this->notifyUrl);

		$html .= sprintf('<input type="hidden" name="name_first" value="%s" />', $this->billingDetails->first_name);
		$html .= sprintf('<input type="hidden" name="name_last" value="%s" />', $this->billingDetails->last_name);
		$html .= sprintf('<input type="hidden" name="email_address" value="%s" />', $this->billingDetails->email);

		$html .= sprintf('<input type="hidden" name="m_payment_id" value="%s" />', $this->itemNumber);
		$html .= sprintf('<input type="hidden" name="amount" value="%.2f" />', $this->amount);
		$html .= sprintf('<input type="hidden" name="item_name" id="item_name" value="%s">', $this->itemName);

		return $html;
	}
 
	public function processTransaction($data){
		$this->returnUrl = urldecode(JRoute::_('index.php?option=com_jbusinessdirectory&view=orders&processor=payfast',false,-1));
		$this->notifyUrl = urldecode(JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processAutomaticResponse&processor=payfast',false,-1));
		$this->cancelUrl = urldecode(JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processCancelResponse',false,-1));
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
		$result->transaction_id = $data["pf_payment_id"];
		$result->amount = $data["amount_gross"];
		$result->transactionTime = date("Y-m-d", strtotime($data["payment_date"]));
		$result->response_code = 0;
		$result->response_message = $data['payment_status'];
		$result->order_id = $data["m_payment_id"];
		$result->processor_type = $this->type;
		$result->payment_method = "";

		if($this->checkTransactionValidity()){
				
			switch( $data['payment_status'] )
			{
				case 'COMPLETE':
					$result->status = PAYMENT_SUCCESS;
					$result->payment_status = PAYMENT_STATUS_PAID;
					break;
				case 'FAILED':
					$result->status = PAYMENT_ERROR;
					$result->payment_status = PAYMENT_STATUS_FAILURE;
					break;
				case 'PENDING':
					$result->status = PAYMENT_WAITING;
					$result->payment_status = PAYMENT_STATUS_PENDING;
					// The transaction is pending, please contact a member of PayFast's support team for further assistance
					break;
				default:
					// If unknown status, do nothing (safest course of action)
					break;
			}
		}else{
			$result->status = PAYMENT_ERROR;
			$result->payment_status = PAYMENT_STATUS_FAILURE;
		}

		return $result;
	}

	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_PROCESSOR_PAYFAST',true);
	}

	public function checkTransactionValidity(){
		$validHosts = array(
				'www.payfast.co.za',
				'sandbox.payfast.co.za',
				'w1w.payfast.co.za',
				'w2w.payfast.co.za',
		);

		$validIps = array();
		foreach( $validHosts as $pfHostname )
		{
			$ips = gethostbynamel( $pfHostname );

			if( $ips !== false )
			{
				$validIps = array_merge( $validIps, $ips );
			}
		}

		// Remove duplicates
		$validIps = array_unique( $validIps );
		if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) )
		{
			return false;
		}

		return true;
	}
}