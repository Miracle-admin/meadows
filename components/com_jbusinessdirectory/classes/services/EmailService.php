<?php 

class EmailService{
	
	public static function sendPaymentEmail($company, $paymentDetails){
	
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$billingInformation = self::getBillingInformation($company);
		
		$templ = self::getEmailTemplate("Order Email");
		
		$content = self::prepareEmail($paymentDetails, $company, $applicationSettings->company_name, $billingInformation, $templ->email_content, $applicationSettings->vat);
		$content = self::updateCompanyDetails($content);
		
		$subject = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_subject);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array($from);
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendPaymentDetailsEmail($company, $paymentDetails){

		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$billingInformation = self::getBillingInformation($company);
	
		$templ = self::getEmailTemplate("Payment Details Email");
	
		$content = self::prepareEmail($paymentDetails, $company, $applicationSettings->company_name, $billingInformation, $templ->email_content, $applicationSettings->vat);
		$content = str_replace(EMAIL_PAYMENT_DETAILS, $paymentDetails->details->details, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_subject);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array($from);
		
		$result = self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
		
		
		return $result;
	}
	
	public static function sendNewCompanyNotificaitonEmailToAdmin($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$templ = self::getEmailTemplate("New Company Notification Email");
		$content = self::prepareNotificationEmail($company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();

		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendNewCompanyNotificaitonEmailToOwner($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Listing Creation Notification");
		$content = self::prepareNotificationEmail($company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendNewOfferNotificaiton($offer){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Offer Creation Notification");
		if(empty($templ))
			return false;
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$content = str_replace(EMAIL_OFFER_NAME, $offer->subject, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendApproveOfferNotificaiton($offer, $companyEmail){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Offer Approval Notification");
		if(empty($templ))
			return false;
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$content = str_replace(EMAIL_OFFER_NAME, $offer->subject, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $companyEmail;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendNewEventNotificaiton($event){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Event Creation Notification");
		if(empty($templ))
			return false;
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$content = str_replace(EMAIL_EVENT_NAME, $event->name, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function sendApproveEventNotificaiton($event, $companyEmail){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Event Approval Notification");
		if(empty($templ))
			return false;
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$content = str_replace(EMAIL_EVENT_NAME, $event->name, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $companyEmail;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function prepareNotificationEmail($company, $emailTemplate){

		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$emailContent = $emailTemplate;
		
		$emailContent = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_NAME, $company->name, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_ADDRESS, JBusinessUtil::getAddressText($company), $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_WEBSITE, $company->website, $emailContent);
		
		$logoContent = '<img height="111" src="'.(JURI::root().PICTURES_PATH.'/no_image.jpg').'"/>';
		if(!empty($company->logoLocation)){
			$company->logoLocation = str_replace(" ","%20",$company->logoLocation);
			$logoContent = '<img height="111" src="'.(JURI::root().PICTURES_PATH.$company->logoLocation).'"/>';
		}
		$emailContent = str_replace(EMAIL_BUSINESS_LOGO, $logoContent, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_CATEGORY, $company->selectedCategories[0]->name, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_CONTACT_PERSON, $company->contact->contact_name, $emailContent);
		
		return $emailContent;
	}
	
	public static function sendApprovalEmail($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Approve Email");
	
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$content = str_replace(EMAIL_BUSINESS_NAME, $company->name, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml);
	}
	
	public static function getBillingInformation($company){
		$user = JFactory::getUser($company->userId);
		$inf = $user->username."<br/>";
		$inf = $inf.$company->name."<br/>";
		$inf = $inf.JBusinessUtil::getAddressText($company);
	
		return $inf;
	}
	
	public static function getEmailTemplate($template)
	{
		$db =JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_emails WHERE email_type = "'.$template.'"';
		$db->setQuery( $query );
		$templ= $db->loadObject();
		return $templ;
	}
	
	public static function prepareEmail($data, $company, $siteName, $billingInformation, $templEmail, $vat)
	{
		$user = JFactory::getUser($company->userId);
		$customerName= $user->username;
	
		$templEmail = str_replace(EMAIL_CUSTOMER_NAME,$customerName, $templEmail);
	
		$siteAddress = JURI::root();
		$templEmail = str_replace(EMAIL_SITE_ADDRESS, $siteAddress,	$templEmail);
		$templEmail = str_replace(EMAIL_COMPANY_NAME, $siteName, $templEmail);
		$templEmail = str_replace(EMAIL_ORDER_ID,$data->order_id, $templEmail);
	
		$paymentMethod=$data->details->processor_type;
		$templEmail = str_replace(EMAIL_PAYMENT_METHOD, $paymentMethod, $templEmail);
		
		if(!empty($data->paid_at))
			$templEmail = str_replace(EMAIL_ORDER_DATE, JBusinessUtil::getDateGeneralFormat($data->paid_at), $templEmail);
		else
			$templEmail = str_replace(EMAIL_ORDER_DATE, JBusinessUtil::getDateGeneralFormat($data->details->payment_date), $templEmail);
		
		$totalAmount = $data->amount_paid;
		if(empty($data->amount_paid))
			$totalAmount = $data->amount;
				
		$templEmail = str_replace(EMAIL_TOTAL_PRICE, JBusinessUtil::getPriceFormat($totalAmount), $templEmail);
		
		$templEmail = str_replace(EMAIL_TAX_AMOUNT, JBusinessUtil::getPriceFormat($data->package->price * $vat/100), $templEmail);
		$templEmail = str_replace(EMAIL_SUBTOTAL_PRICE, JBusinessUtil::getPriceFormat($data->package->price), $templEmail);
		
		$templEmail = str_replace(EMAIL_SERVICE_NAME, $data->service, $templEmail);
		$templEmail = str_replace(EMAIL_UNIT_PRICE, JBusinessUtil::getPriceFormat($data->package->price), $templEmail);
		$templEmail = str_replace(EMAIL_BILLING_INFORMATION, $billingInformation, $templEmail);
	
		return "<div style='width: 600px;'>".$templEmail.'</div>';
	}
	
	
	public static function sendEmail($from, $fromName, $replyTo, $toEmail, $cc, $bcc, $subject, $content, $isHtml){
		jimport('joomla.mail.mail');
	
		$mail = new JMail();
		$mail->setSender(array($from, $fromName));
		if(isset($replyTo))
			$mail->addReplyTo($replyTo);
		$mail->addRecipient($toEmail);
		if(isset($cc))
			$mail->addCC($cc);
		if(isset($bcc))
			$mail->addBCC($bcc);
		$mail->setSubject($subject);
		$mail->setBody($content);
		$mail->IsHTML($isHtml);

		$ret = $mail->send();
		
		$log = Logger::getInstance();
		$log->LogDebug("E-mail with subject ".$subject." sent from ".$from." to ".$toEmail." ".serialize($bcc)." result:".$ret);
		
		return $ret;
	}
	
	public static function updateCompanyDetails($emailContent){
		$logo = self::getCompanyLogoCode();
		$socialNetworks = self::getCompanySocialNetworkCode();
		$emailContent = str_replace(EMAIL_COMPANY_LOGO, $logo, $emailContent);
		$emailContent = str_replace(EMAIL_COMPANY_SOCIAL_NETWORKS, $socialNetworks, $emailContent);
		$link='<a style="color:#555;text-decoration:none" target="_blank" href="'.JURI::root(false).'">'.JURI::root(false).'</a>';
		$emailContent = str_replace(EMAIL_DIRECTORY_WEBSITE, $link, $emailContent);
		
		return $emailContent;
	}
	
	public static function getCompanyLogoCode(){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$code ="";
		if(!empty($applicationSettings->logo)){
			$applicationSettings->logo = str_replace(" ","%20",$applicationSettings->logo);
			$logoLocaiton = JURI::root().PICTURES_PATH.$applicationSettings->logo;
			$link = JURI::base();
			$code='<a target="_blank" href="'.$link.'"><img height="55" alt="'.$applicationSettings->company_name.'" src="'.$logoLocaiton.'" ></a>';
		}
		
		return $code;
	}
	
	public static function getCompanySocialNetworkCode(){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$code="";
		if(!empty($applicationSettings->twitter)){
			$code.='<a href="'.$applicationSettings->twitter.'" target="_blank"><img title="Twitter" src="'.JURI::root().PICTURES_PATH.'/twitter.png'.'" alt="Twitter" height="32" border="0" width="32"></a>';
		}
			
		if(!empty($applicationSettings->facebook)){
			$code.='<a href="'.$applicationSettings->facebook.'" target="_blank"><img title="Facebook" src="'.JURI::root().PICTURES_PATH.'/facebook.png'.'" alt="Facebook" height="32" border="0" width="32"></a>';
		}
		
		if(!empty($applicationSettings->linkedin)){
			$code.='<a href="'.$applicationSettings->linkedin.'" target="_blank"><img title="LinkedIN" src="'.JURI::root().PICTURES_PATH.'/linkedin.png'.'" alt="LinkedIN" height="32" border="0" width="32"></a>';
		}
		
		if(!empty($applicationSettings->googlep)){
			$code.='<a href="'.$applicationSettings->googlep.'" target="_blank"><img title="Google+" src="'.JURI::root().PICTURES_PATH.'/googlep.png'.'" alt="Google+" height="32" border="0" width="32"></a>';
		}
		
		if(!empty($applicationSettings->youtube)){
			$code.='<a href="'.$applicationSettings->youtube.'" target="_blank"><img title="Youtube" src="'.JURI::root().PICTURES_PATH.'/youtube.png'.'" alt="Youtube" height="32" border="0" width="32"></a>';
		}
		
		return $code;
	}
}

?>