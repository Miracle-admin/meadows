<?php 

class Braintree implements IPaymentProcessor {
	
	   var $type;
	   var $name;
	   var $mode;
	   var $privateKey;
	   var $Btpublickey;
	   var $bmid;
	   var $customer = false;
	   var $pmToken  = "";
	   var $planId;
	   var $currencyCode;
	   var $amount;
	   var $itemNumber;
	   var $itemName;
	   var $company;
	   var $extendedAddress;
       var $locality;
       var $region;
	   var $billingDetails;
       var $email;
	   var $firstName;
	   var $lastName;
	   var $phone;
	

	public function initialize($data){
		
		$apiBt=JPATH_SITE.DS.'components'.DS.'com_jbusinessdirectory'.DS.'libraries'.DS.'bt'.DS.'lib'.DS.'Braintree.php';
	
        include_once($apiBt);
		$data             = (array)$data;
		
		$mode             = $data['mode']=="test"?"sandbox":"production";
        $this->type       = $data['type'];
		$this->name       = $data['name'];
		$this->mode       = $mode;
	    $this->privateKey = $data['Private Key'];
	    $this->Btpublickey=$data['Braintree Public Key'];
	    $this->bmid       =$data['Braintree Merchant ID'];
		
		
		
		
		
		Braintree\Configuration::environment($mode);
        Braintree\Configuration::merchantId($this->bmid);
        Braintree\Configuration::publicKey($this->Btpublickey);
        Braintree\Configuration::privateKey($this->privateKey); 
		
		
		
	}
	
	
	
	
	public function getPaymentProcessorHtml(){
		$html ="<ul id=\"payment_form_$this->type\" style=\"display:none\" class=\"form-list\">
		<li>
		    ".$this->getCreditCardForm()."
		    </li>
		</ul>";
		
		return $html;
	}
	
	public function getHtmlFields() {
	
	$nonce=JRequest::getVar('payment_method_nonce','');
    $html = "<img src='images/loading-bar.gif'><input type='hidden' name ='payment_method_nonce' value='".$nonce."'/>";
	
	$html.="<input type='hidden' name ='company' value='".$this->company."'/>";
	
	$html.="<input type='hidden' name ='extendedAddress' value='".$this->extendedAddress."'/>";
	
	$html.="<input type='hidden' name ='locality' value='".$this->locality."'/>";
	
	$html.="<input type='hidden' name ='region' value='".$this->region."'/>";
	
	$html.="<input type='hidden' name ='billingDetails' value='".$this->billingDetails."'/>";
	
	$html.="<input type='hidden' name ='email' value='".$this->email."'/>";
	
	$html.="<input type='hidden' name ='firstName' value='".$this->firstName."'/>";
	
	$html.="<input type='hidden' name ='lastName' value='".$this->lastName."'/>";
	
	$html.="<input type='hidden' name ='phone' value='".$this->phone."'/>";
	
	$html.="<input type='hidden' name ='amount' value='".$this->amount."'/>";
	
	$html.="<input type='hidden' name ='itemNumber' value='".$this->itemNumber."'/>";
	
	$html.="<input type='hidden' name ='currencyCode' value='".$this->currencyCode."'/>";
	
	
	
	
	
	
	
	return $html;
				
				
	}
	
	public function processTransaction($data)
	{
	$this->returnUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processResponse&processor=braintree',false,-1);
		$this->notifyUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processAutomaticResponse&processor=braintree',false,-1);
		$this->cancelUrl = JRoute::_('index.php?option=com_jbusinessdirectory&task=payment.processCancelResponse',false,-1);;
		$this->amount = $data->amount;
		$this->itemName = $data->service." ".$data->description;
		$this->itemNumber = $data->id;
		$this->currencyCode = $data->currency;
		
		
		
		//set params
		$this->company          = $data->billingDetails->company_name;
        $this->extendedAddress  = $data->billingDetails->address;
        $this->locality         = $data->billingDetails->city;
        $this->region           = $data->billingDetails->region;
        $this->email            = $data->billingDetails->email;
		$this->firstName        = $data->billingDetails->first_name;
		$this->lastName         = $data->billingDetails->last_name;
		$this->phone            = $data->billingDetails->phone;
	    
		
		
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
	
	public function getPaymentGatewayUrl()
	{
	return $this->notifyUrl;
	}
	
	
	public function processResponse($data)
	{
	
	
	
	    $nonce = $data["payment_method_nonce"];
		
		$user   = JFactory::getUser();
		
		$app    = JFactory::getApplication();
		
		
		
		$result = Braintree_Transaction::sale([
                  'amount'             => $data["amount"],
                  'paymentMethodNonce' => $nonce,
				  'billing'            =>[
				                         'company'          => $data["company"],
                                         'extendedAddress'  => $data["extendedAddress"],
                                         'locality'         => $data["locality"],
                                         'region'           => $data["region"]
                                         ],
				  'customer'          => [
				                         'company'          => $data["company"],
										 'email'            => $data["email"],
										 'firstName'        => $data["firstName"],
										 'lastName'         => $data["lastName"],
										 'phone'            => $data["phone"]
										 ],					 
                  'options'           => [
                                         'submitForSettlement' => True
                                         ]
                                             ]);
											 
										
		            if($result->success)
					{
					$transaction              = $result->transaction;
					$result                   = new stdClass();
		            $result->transaction_id   = $transaction->id;
		            $result->amount           = $transaction->amount;
					$result->payment_date     = $transaction->createdAt->format("Y-m-d h:i:s");
		            $result->transactionTime  = $transaction->createdAt->format("Y-m-d h:i:s");
		            $result->response_code    = 1;
		            $result->response_message = $transaction->processorResponseText;
		            $result->order_id         = $data['itemNumber'];
		            $result->currency         = $data['currencyCode'];
		            $result->processor_type   = $this->type;
		            $result->payment_method   = "";
		            $result->status           = PAYMENT_SUCCESS;
		            $result->payment_status   = PAYMENT_STATUS_PAID;
					$result->processAutomatically=1;
					
					return $result;
					}
					else
					{
					echo $result->message;
					die;
					}
	}

	public function getPaymentDetails($paymentDetails){
		return JText::_('LNG_PROCESSOR_BRAINTREE_INFO',true);
	}
	
	private function getCreditCardForm()
	{
	$year =date("Y");
	$form.="<div>";
	$form='<div class="form-group">
           <label class="col-md-4 control-label required" for="cdnum">Card Number</label>  
           <div class="col-md-6">
           <input value="371449635398431" id="cdnum" data-braintree-name="number"   placeholder="xxxxxxxxxxxxxxxx" class="form-control input-md" type="text" data-validation="creditcard,required" data-validation-error-msg="You did not enter valid Credit/Debit card number." data-braintree-name="number" data-validation-optional="false">
           <div class="tip">{tip Enter your card number. } <img src="images/help.png"/>{/tip}</div>
           </div>
           </div>';
	
    $form.=	'<div class="form-group">
             <label class="col-md-4 control-label required" for="expirationMonth">Expiration Month</label>
             <div class="col-md-6">
             <select data-braintree-name="expiration_month" id="expirationMonth"  class="form-control expdt" data-validation="date" data-validation-format="mm" data-validation-error-msg="You have not given a correct month.">
             <option value="">Select Month</option>
            <option selected value="01">Jan</option>
            <option value="02">Feb</option>
            <option value="03">Mar</option>
            <option value="04">Apr</option>
            <option value="05">May</option>
            <option value="06">Jun</option>
            <option value="07">Jul</option>
            <option value="08">Aug</option>
            <option value="09">Sep</option>
            <option value="10">Oct</option>
            <option value="11">Nov</option>
            <option value="12">Dec</option>
            </select>
	        <div class="tip">{tip Enter the expiration month of your card. } <img src="images/help.png"/>{/tip}</div>
            </div>
            </div>';
			
	$form.='<div class="form-group">
           <label class="col-md-4 control-label required" for="exp_year">Expiration Year</label>
           <div class="col-md-6">
           <select data-braintree-name="expiration_year"   id="exp_year" name="exp_year" class="form-control expdt" data-validation="date" data-validation-format="yyyy" data-validation-error-msg="You have not given a correct year">
	       <option value="">Select Year</option>';	

           for($i=$year;$i<=2050;$i++)
	       {
	       $form.= '<option value="'.$i.'">'.$i.'</option>';
	       }		   
	
    $form.='</select>
	        <div class="tip">{tip Enter the expiration year of your card. } <img src="images/help.png"/>{/tip}</div>
			</div>
            </div>';

    $form.='<div class="form-group">
           <label class="col-md-4 control-label required"  for="cdcvv">Cvv number</label>  
           <div class="col-md-6">
           <input data-validation="number,length" data-validation-length="max4" data-validation-error-msg="Please enter a valid cvv number"  data-braintree-name="cvv" id="cdnum"  placeholder="" class="form-control input-md" type="password" value="1234" >
           <div class="tip">{tip <img src="';
		 
	$form.= JUri::root();
	
	$form.='images/cvvback.jpg"> } <img src="images/help.png"/>{/tip}</div>
           <i>This card will be considered as your default payment method from now.</i>  
           </div>
           </div>';			
	
    $form.="</div>";	
	
	JHTML::script(Juri::base().'media/com_alphauserpoints/bt/bt.js');
    JHtml::stylesheet(Juri::base(). 'media/com_jblance/jQuery-Form-Validator-master/form-validator/theme-default.min.css');
    JHTML::script(Juri::base(). 'media/com_jblance/jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js');
	
	$form.='<script type="text/javascript">

           jQuery.formUtils.addValidator({
           name : \'cart_ammount\',
           validatorFunction : function(value, $el, config, language, $form) {
           if (typeof window.cartammount != "undefined") {
           if (parseInt(value, 10) < window.cartammount)
            {
           return false;
            }
            else
           {
 
           return true;
           }
           }
           else
           {
           return true;
           }
           },
           errorMessage : \'Insufficient ammount to proceed with this order\',
           errorMessageKey: \'badEvenNumber\'
           });
           jQuery.validate({
           modules : "security,toggleDisabled",
           onSuccess : function(form){
		   jQuery("#waiting-nonce").show();
           window.form=form;
           jQuery("#loading_nonce").show();
           return false;}
           });

           braintree.setup("'.$this->getToken().'", "custom", {
                id: "payment-form",
                onReady:function(){},
                onPaymentMethodReceived:function(obj){
                var nonce = obj.nonce;
                jQuery("input[name=\'payment_method_nonce\']").val(nonce);
                if(typeof window.form != "undefined")
                  {
				  jQuery("#waiting-nonce").hide();
                var winform = window.form;
                window.form.off();
                jQuery("#loading_nonce").hide();
                window.form.submit();
                 }

                  }
                  })
                jQuery(function(){
                jQuery("#view_more").on(\'click\',function(e){
                e.preventDefault();
                jQuery(".cart_products li").fadeIn(500);
                })
                jQuery("#add_new_pm").on("click",function(e){
  
                e.preventDefault();
                jQuery(".payment_method").fadeToggle(800,function(){
                var sel = jQuery("#pm");
                var currval=parseInt(sel.val());
                var fmctrl = jQuery(".form-control").not("#amt");
                if(currval==0)
                 {
	            jQuery("#add_new_pm").text("Use old card"); 
    
                sel.val(1);
                fmctrl.removeAttr("disabled","disabled");
                }
                else
                {
                jQuery("#add_new_pm").text("Add new card"); 
                sel.val(0);
                fmctrl.attr("disabled","disabled");
                }
  
                });
                })
                })
                </script>';
	
	
	return $form;	   
	}
	
	private function getToken()
	{
	
	$apiBt=JPATH_SITE.DS.'components'.DS.'com_jbusinessdirectory'.DS.'libraries'.DS.'bt'.DS.'lib'.DS.'Braintree.php';
	
	include_once($apiBt);
	
	Braintree\Configuration::environment($this->mode);
    Braintree\Configuration::merchantId($this->bmid);
    Braintree\Configuration::publicKey($this->Btpublickey);
    Braintree\Configuration::privateKey($this->privateKey); 
	    
	return Braintree_ClientToken::generate();
	
	}
	
	
	
}