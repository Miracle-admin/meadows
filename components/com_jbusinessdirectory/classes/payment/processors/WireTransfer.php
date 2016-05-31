<?php 

class WireTransfer implements IPaymentProcessor {
	
	var $type;
	var $name;
	
	public function initialize($data){
		$this->type =  $data->type;
		$this->name =  $data->name;
		$this->mode = $data->mode;
		$this->bank_name = $data->bank_name;
		$this->bank_address = $data->bank_address;
		$this->bank_city = $data->bank_city;
		$this->bank_country = $data->bank_country;
		$this->bank_holder_name = $data->bank_holder_name;
		$this->bank_account_number = $data->bank_account_number;
		$this->timeout = $data->timeout;
		$this->swift_code = $data->swift_code;
		$this->iban=$data->iban;
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
			<TABLE>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_AMOUNT',true)?> : </b>
					</TD> 
					<TD>
						 <?php echo  JBusinessUtil::getPriceFormat($paymentDetails->amount)?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_BANK_NAME',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->bank_name?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_BANK_HOLDER_NAME',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->bank_holder_name?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_IBAN',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->iban?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_BANK_ACCOUNT_NUMBER',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->bank_account_number?>
					</TD>
				</TR>
								<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_BANK_COUNTRY',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->bank_country?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_BANK_CITY',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->bank_city?>
					</TD>
				</TR>
				<TR>
					<TD align=left width=40% nowrap>
						<b><?php echo JText::_('LNG_SWIFT_CODE',true)?> :</b>
					</TD> 
					<TD>
						<?php echo $this->swift_code?>
					</TD>
				</TR>
			</TABLE>
		<?php
		$result = $result.ob_get_contents();
		
		ob_end_clean();
		
		return $result;
	}
}