<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class NewsletterController extends acymailingController{

	var $aclCat = 'newsletters';

	function replacetags(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		$this->store();
		return $this->edit();

	}

	function copy(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::checkToken() or JSession::checkToken( 'get' ) or die( 'Invalid Token' );

		$cids = JRequest::getVar( 'cid', array(), '', 'array' );
		$db = JFactory::getDBO();
		$time = time();

		$my = JFactory::getUser();
		$creatorId = intval($my->id);

		$addSendDate = '';
		if(!empty($this->copySendDate)) $addSendDate = ', `senddate`';

		foreach($cids as $oneMailid){
			$query = 'INSERT INTO `#__acymailing_mail` (`subject`, `body`, `altbody`, `published`'. $addSendDate . ', `created`, `fromname`, `fromemail`, `replyname`, `replyemail`, `type`, `visible`, `userid`, `alias`, `attach`, `html`, `tempid`, `key`, `frequency`, `params`,`filter`,`metakey`,`metadesc`)';
			$query .= " SELECT CONCAT('copy_',`subject`), `body`, `altbody`, `published`". $addSendDate .", '.$time.', `fromname`, `fromemail`, `replyname`, `replyemail`, `type`, `visible`, '.$creatorId.', `alias`, `attach`, `html`, `tempid`, ".$db->Quote(md5(rand(1000,999999))).', `frequency`, `params`,`filter`,`metakey`,`metadesc` FROM `#__acymailing_mail` WHERE `mailid` = '.(int) $oneMailid;
			$db->setQuery($query);
			$db->query();
			$newMailid = $db->insertid();
			$db->setQuery('INSERT IGNORE INTO `#__acymailing_listmail` (`listid`,`mailid`) SELECT `listid`,'.$newMailid.' FROM `#__acymailing_listmail` WHERE `mailid` = '.(int) $oneMailid);
			$db->query();
		}

		return $this->listing();
	}

	function store(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$app = JFactory::getApplication();

		$mailClass = acymailing_get('class.mail');
		$status = $mailClass->saveForm();
		if($status){
			$app->enqueueMessage(JText::_( 'JOOMEXT_SUCC_SAVED' ), 'message');
		}else{
			$app->enqueueMessage(JText::_( 'ERROR_SAVING' ), 'error');
			if(!empty($mailClass->errors)){
				foreach($mailClass->errors as $oneError){
					$app->enqueueMessage($oneError, 'error');
				}
			}
		}
	}

	function unschedule(){
		if(!$this->isAllowed($this->aclCat,'schedule')) return;
		$mailid = acymailing_getCID('mailid');

		(JRequest::checkToken() && !empty($mailid)) or die( 'Invalid Token' );
		$mail = new stdClass();
		$mail->mailid = $mailid;
		$mail->senddate = 0;
		$mail->published = 0;

		$mailClass = acymailing_get('class.mail');
		$mailClass->save($mail);

		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('SUCC_UNSCHED'));

		return $this->preview();
	}

	function remove(){
		if(!$this->isAllowed($this->aclCat,'delete')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$cids = JRequest::getVar( 'cid', array(), '', 'array' );

		$class = acymailing_get('class.mail');
		$num = $class->delete($cids);

		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::sprintf('SUCC_DELETE_ELEMENTS',$num), 'message');

		return $this->listing();
	}

	function savepreview(){
		$this->store();
		return $this->preview();
	}

	function preview(){
		JRequest::setVar( 'layout', 'preview'  );
		JRequest::setVar('hidemainmenu',1);
		return parent::display();
	}

	function sendtest(){
		$this->_sendtest();
		return $this->preview();
	}

	function _sendtest(){
		JRequest::checkToken() or die( 'Invalid Token' );

		$mailid = acymailing_getCID('mailid');

		$receiver_type = JRequest::getVar('receiver_type','','','string');

		if(empty($mailid) OR empty($receiver_type)) return false;

		$app = JFactory::getApplication();
		$mailer = acymailing_get('helper.mailer');
		$mailer->forceVersion = JRequest::getVar('test_html',1,'','int');
		$mailer->autoAddUser = true;
		if($app->isAdmin()) $mailer->SMTPDebug = 1;
		$mailer->checkConfirmField = false;
		$comment = JRequest::getString('commentTest', '');
		if(!empty($comment))
			$mailer->introtext = '<div align="center" style="width:600px;margin:auto;margin-top:10px;margin-bottom:10px;padding:10px;border:1px solid #cccccc;background-color:#f6f6f6;color:#333333;">'.nl2br($comment).'</div>';

		$receivers = array();
		if($receiver_type == 'user'){
			$user = JFactory::getUser();
			$receivers[] = $user->email;
		}elseif($receiver_type == 'other'){
			$receiverEntry = JRequest::getVar('test_email','','','string');
			if(substr_count($receiverEntry,'@')>1){
				$receivers = explode(' ',trim(preg_replace('# +#',' ',str_replace(array(';',','),' ',$receiverEntry))));
			}else{
				$receivers[] = trim($receiverEntry);
			}
		}else{
			$gid = substr($receiver_type,strpos($receiver_type,'_')+1);
			if(empty($gid)) return false;
			$db = JFactory::getDBO();
			$db->setQuery('SELECT email from '.acymailing_table('users',false).' WHERE gid = '.intval($gid));
			$receivers = acymailing_loadResultArray($db);
		}

		if(empty($receivers)){
			$app->enqueueMessage(JText::_('NO_SUBSCRIBER'), 'notice');
			return false;
		}

		$result = true;
		foreach($receivers as $receiver){
			$result = $mailer->sendOne($mailid,$receiver) && $result;
		}

		return $result;
	}

	function upload(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::setVar( 'layout', 'upload'  );
		return parent::display();
	}

	function abtesting(){
		JRequest::setVar( 'layout', 'abtesting'  );
		return parent::display();
	}

	function abtest(){
		$nbTotalReceivers = JRequest::getInt('nbTotalReceivers');
		$mailids = JRequest::getString('mailid');
		$mailsArray = explode(',', $mailids);
		JArrayHelper::toInteger($mailsArray);

		$db = JFactory::getDBO();

		$abTesting_prct = JRequest::getInt('abTesting_prct');
		$abTesting_delay = JRequest::getInt('abTesting_delay');
		$abTesting_action = JRequest::getString('abTesting_action');

		if(empty($abTesting_prct)) {
			acymailing_display(JText::_('ABTESTING_NEEDVALUE'), 'warning');
			$this->abtesting();
			return;
		}

		$newAbTestDetail = array();
		$newAbTestDetail['mailids'] = implode(',',$mailsArray);
		$newAbTestDetail['prct'] = (!empty($abTesting_prct)?$abTesting_prct:'');
		$newAbTestDetail['delay'] = (!empty($abTesting_delay)?$abTesting_delay:'2');
		$newAbTestDetail['action'] = (!empty($abTesting_action)?$abTesting_action:'manual');
		$newAbTestDetail['time'] = time();
		$newAbTestDetail['status'] = 'inProgress';
		$mailClass = acymailing_get('class.mail');
		$nbReceiversTest = $mailClass->ab_test($newAbTestDetail, $mailsArray, $nbTotalReceivers);

		acymailing_display(JText::sprintf('ABTESTING_SUCCESSADD', $nbReceiversTest), 'info');
		JRequest::setVar('validationStatus', 'abTestAdd');
		$this->abtesting();
	}

	function complete_abtest(){
		$mailid = JRequest::getInt('mailToSend');
		$mailClass = acymailing_get('class.mail');
		$newMailid = $mailClass->complete_abtest('manual', $mailid);

		$finalMail = $mailClass->get($newMailid);
		acymailing_display(JText::sprintf('ABTESTING_FINALSEND', $finalMail->subject), 'info');
		JRequest::setVar('validationStatus', 'abTestFinalSend');
		$this->abtesting();
	}

	function douploadnewsletter(){
		if(!$this->isAllowed($this->aclCat,'manage')) return;
		JRequest::checkToken() or die( 'Invalid Token' );

		$templateClass = acymailing_get('class.template');
		$templateClass->checkAreas = false;
		$statusUpload = $templateClass->doupload();

		if($statusUpload){
			$mailClass = acymailing_get('class.mail');
			$mail = new stdClass();
			$newTemplate = $templateClass->get($templateClass->templateId);
			$mail->subject = $newTemplate->name;
			$mail->body = $newTemplate->body;
			$mail->tempid = $templateClass->templateId;

			$idMailCreated = $mailClass->save($mail);
			if($idMailCreated){
				acymailing_display(JText::_('NEWSLETTER_INSTALLED'),'success');
				$js = "setTimeout('redirect()',2000); function redirect(){window.top.location.href = 'index.php?option=com_acymailing&ctrl=newsletter&task=edit&mailid=" .$idMailCreated . "'; }";
				$doc = JFactory::getDocument();
				$doc->addScriptDeclaration( $js );
				return;
			} else{
				acymailing_display(JText::_('ERROR_SAVING'),'error');
				return $this->upload();
			}
		} else{
			return $this->upload();
		}
	}
	
	function viewContextualTags()
	{
    $tags = array("New client posted project(A)."=>array("cname","sitename","siteurl","username","password","useremail","projectname","categories","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","projecturlb","projecturlf","activationlink"),
	
	"New client posted project(C)."=>array("cname","sitename","siteurl","username","password","useremail","projectname","categories","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","projecturlb","projecturlf","activationlink"),
	
	"Email validated notification to administrator."=> array("cname","sitename","siteurl","username","useremail","projectname","categories","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","projecturlb","projectupgrades" ),
	
	"Client purchased an upsell(A)." => array("cname","sitename","siteurl","username","password","useremail","projectupgrades","projectname","categories","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","projecturlb","projecturlf","activationlink"),
	
	"Client purchased an upsell(C)." => array("cname","sitename","siteurl","username","password","useremail","projectupgrades","projectname","categories","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","projecturlb","projecturlf","activationlink"),
	
	"Project invitation to developer."=>array("sitename","siteurl","projectname","categories","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","projecturlf"),
	
	"New bid notification(C)."=> array("sitename","siteurl","projectname","categories","currencysym"    ,"currencycode","budgetmin","budgetmax","startdate","expire","projecturlf","publishername","biddername","bidderusername","bidamount","delivery"),
	
	"Lower Bid(D)."=> array("sitename","siteurl","projectname","currencysym","currencycode","budgetmin","budgetmax","startdate","expire","bidderusername","bidamount","delivery"),
	
	"Bid Won(D)."=> array("sitename","siteurl","projectname","currencysym","currencycode","biddername","bidderusername","bidamount","delivery"),
	
	"Offer Denied."=>  array(
				     "sitename","siteurl","projectname","publishername","biddername",
                     "bidderusername"
					 ),
	
	"Bidder accepted the offer.(C)"=>array(
				     "sitename","siteurl","projectname","publishername","biddername",
                     "bidderusername"
					 ),
	
	
	"You(Developer) accepted the offer.(D)"=>array("sitename","siteurl","projectname","publishername","biddername","bidderusername"),
	
	
	"Mail to bid looser."=>array("sitename","siteurl","projectname"),
		
	
	
	"Project pending approval administrator."=>array("sitename","projectname","adminurl","publisherusername"
					 ),
	
	
	"Project Approved(D)"=>array("sitename","projectname","projecturlf","publishername"),
	
	"Project payment complete notification."=>array("sitename","siteurl","projectname","recipientUsername","markerUsername" ),
	
	
	"Project progress notification."=>array("sitename","siteurl","projectname","buyerUsername","project_id","percent","status"),
	
	
	"Withdraw fund request to administrator."=>array("username","adminurl","name","invoiceNo","gateway"),
	
	"Withdraw request approved."=>array("sitename","siteurl","currencysym","name","invoiceNo",
					                  "amount"),
	
	
	"Escrow payment released."=>array("sitename","siteurl","projectname","currencysym","amount","senderUsername","receiveUsername","releaseDate","note"),
	
	
	"Escrow payment accepted."=>array("sitename","siteurl","projectname","currencysym","amount","senderUsername","receiveUsername","releaseDate","note"),
		
	
	"Deposit fund alert to admin."=>array("username","currencysym","adminurl","status","name","invoiceNo","gateway","amount"
					 ),
	
	"User deposit fund approved."=>array("sitename","siteurl","currencysym","name","invoiceNo","amount"),
	
	"PM notification to the recipient."=> array("sitename","siteurl","msgRecipientName", "msgSenderName","msg_subject","msg_body"),
	
	"Send message pending approval to admin."=>array("sitename","siteurl","adminurl","msgRecipientInfo",
					 "msgSenderInfo","msg_subject","msg_body"),
	
	
	"Send reporting default action."=>array("sitename","type","count","action","itemlink"));
	
	

	$table = '<table class="table table-bordered">
  <thead>
    <tr>
   
      <th>Email</th>
      <th>Tags</th>
    </tr>
  </thead>
  <tbody>';
  $i=1;
  foreach($tags as $th=>$tv)
  {
  
  $table.='<tr>
    <td><strong>'.$i.'.</strong>'.$th.'</td><td style="border-bottom: 1px solid rgb(221, 221, 221); width: 682px;"><span onclick=showTag('.$i.') id="viewav-'.$i.'" class="btn  btn-info">View available tags</span></td></tr>
    <td id="avtags-'.$i.'" align="right" style="display:none;">
	<table style="float: right; position: relative; left: 676px;" class="table table-hover">';
	foreach($tv as $tvv)
	{
	$table.='<tr title="Name of the client posting project"><td>'.$tvv.'</td><td><span class="icon-publish"></span></td></tr>';
	};
$table.='</table>     
	</td>
    </tr>';
	$i++;
	
	}
 
  $table.='</tbody>
</table>
<style>
.table.table-hover {
    border-bottom: 1px solid #dddddd;
    border-right: 1px solid #dddddd;
    border-top: 1px solid #dddddd;
	}

</style>
<script type="text/javascript">

function showTag(x){

var vis = jQuery("#avtags-"+x);
vis.fadeToggle(1000);


}

</script>
';

echo $table;

	
	}
	
	function viewContextualTagsSub()
	{
	    $tags = array("User Subscribed new plan.(U)"=>array("username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","transid","date_buy","date_approval","date_expire","billing_day","createdAt","updatedAt","currentBillingCycle","status","planId","token","bin","last4","cardType","expirationDate","customerLocation","cardholderName","imageUrl","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","maskedNumber"),
		
		
		"User Subscribed new plan.(A)"=>array("username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","transid","date_buy","date_approval","date_expire","billing_day","createdAt","updatedAt","currentBillingCycle","status","planId","token","bin","last4","cardType","expirationDate","customerLocation","cardholderName","imageUrl","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","maskedNumber"),
		
		"User Undated plan.(U)"=>array("username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","transid","date_buy","date_approval","date_expire","billing_day","createdAt","updatedAt","currentBillingCycle","status","planId","token","bin","last4","cardType","expirationDate","customerLocation","cardholderName","imageUrl","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","maskedNumber"),
		
		
		"User Undated plan.(A)"=>array("username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","transid","date_buy","date_approval","date_expire","billing_day","createdAt","updatedAt","currentBillingCycle","status","planId","token","bin","last4","cardType","expirationDate","customerLocation","cardholderName","imageUrl","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","maskedNumber"),
		
		"User canceled his plan"=>array( "username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","status","planId","canceledOn"),
		
		"User canceled his plan.(A)"=>array( "username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","status","planId","canceledOn"),
		
		"User's plan went overdue"=>array( "username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","status","planId","overdueOn"),
		
		"User's plan went overdue.(A)"=>array( "username","subscriptionId","subscriptionName","gateway","gatewayImage","amount","status","planId","overdueOn"),
		
		"User added new card"=>array("username","token","bin","last4","cardType","expirationDate","customerLocation","cardholderName","cardImage","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","maskedNumber")
	
	                  );
	
	

	$table = '<table class="table table-bordered">
  <thead>
    <tr>
   
      <th>Email</th>
      <th>Tags</th>
    </tr>
  </thead>
  <tbody>';
  $i=1;
  foreach($tags as $th=>$tv)
  {
  
  $table.='<tr>
    <td><strong>'.$i.'.</strong>'.$th.'</td><td style="border-bottom: 1px solid rgb(221, 221, 221); width: 682px;"><span onclick=showTag('.$i.') id="viewav-'.$i.'" class="btn  btn-info">View available tags</span></td></tr>
    <td id="avtags-'.$i.'" align="right" style="display:none;">
	<table style="float: right; position: relative; left: 676px;" class="table table-hover">';
	foreach($tv as $tvv)
	{
	$table.='<tr ><td>'.$tvv.'</td><td><span class="icon-publish"></span></td></tr>';
	};
$table.='</table>     
	</td>
    </tr>';
	$i++;
	
	}
 
  $table.='</tbody>
</table>
<style>
.table.table-hover {
    border-bottom: 1px solid #dddddd;
    border-right: 1px solid #dddddd;
    border-top: 1px solid #dddddd;
	}

</style>
<script type="text/javascript">

function showTag(x){

var vis = jQuery("#avtags-"+x);
vis.fadeToggle(1000);


}

</script>
';

echo $table;
	
	}
	
	
	
	function viewContextualTagsCredmkt()
	{
		    $tags = array(
			"User added funds"=>array("customerName","customerId","gateway","gatewayImage","transaction_id","transaction_status","transaction_type","transaction_currencyIsoCode","transaction_amount","transaction_created_at","transaction_updatedAt","card_token","cardbin","cardlast4","cardType","expirationMonth","expirationYear","customerLocation","cardholderName","cardImage","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","venmoSdk","expirationDate","maskedNumber"),
			
			"User added funds(A)"=>array("customerName","customerId","gateway","gatewayImage","transaction_id","transaction_status","transaction_type","transaction_currencyIsoCode","transaction_amount","transaction_created_at","transaction_updatedAt","card_token","cardbin","cardlast4","cardType","expirationMonth","expirationYear","customerLocation","cardholderName","cardImage","prepaid","healthcare","debit","durbinRegulated","commercial","payroll","issuingBank","countryOfIssuance","productId","uniqueNumberIdentifier","venmoSdk","expirationDate","maskedNumber")
			
			
			
			);
	
	

	$table = '<table class="table table-bordered">
  <thead>
    <tr>
   
      <th>Email</th>
      <th>Tags</th>
    </tr>
  </thead>
  <tbody>';
  $i=1;
  foreach($tags as $th=>$tv)
  {
  
  $table.='<tr>
    <td><strong>'.$i.'.</strong>'.$th.'</td><td style="border-bottom: 1px solid rgb(221, 221, 221); width: 682px;"><span onclick=showTag('.$i.') id="viewav-'.$i.'" class="btn  btn-info">View available tags</span></td></tr>
    <td id="avtags-'.$i.'" align="right" style="display:none;">
	<table style="float: right; position: relative; left: 676px;" class="table table-hover">';
	foreach($tv as $tvv)
	{
	$table.='<tr ><td>'.$tvv.'</td><td><span class="icon-publish"></span></td></tr>';
	};
$table.='</table>     
	</td>
    </tr>';
	$i++;
	
	}
 
  $table.='</tbody>
</table>
<style>
.table.table-hover {
    border-bottom: 1px solid #dddddd;
    border-right: 1px solid #dddddd;
    border-top: 1px solid #dddddd;
	}

</style>
<script type="text/javascript">

function showTag(x){

var vis = jQuery("#avtags-"+x);
vis.fadeToggle(1000);


}

</script>
';

echo $table;
	
	}
}
