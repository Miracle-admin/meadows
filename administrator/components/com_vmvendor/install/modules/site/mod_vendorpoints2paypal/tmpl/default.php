<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Access denied)');
$db					= JFactory::getDBO();
$doc				= JFactory::getDocument();
$user 				= JFactory::getUser();
$app				= JFactory::getApplication();
$jinput				= $app->input;
$juri 				= JURI::base();

$minreq 			= $params->get('minreq', '100');
$pointsname			= $params->get('pointsname','points');
$cparams 			= JComponentHelper::getParams('com_vmvendor');
$ratio 				= $cparams->get('aup_ratio','1');
$userpoints 		= $results;
$currency_code 		= $currency[0];
$decim				= $currency[3];

$paypal_useremail	= $jinput->post->get( 'paypal_useremail' );
$validpointsamount 	= $jinput->post->get('pointsamount', '' , 'int');
$validmoneyamount	= $jinput->post->get('total', '' , 'int');
$receivecopy		= $jinput->post->get('receivecopy');

if( $paypal_useremail != '' )
{
	$message 	= JFactory::getMailer(); 
	$config 	= JFactory::getConfig();
	$sendto 	= $params->get('sendto',0);
	$customemail = $params->get('customemail');
	if ($sendto == 1 && $customemail !='')
		$emailtosendto=$customemail;
	else
		$emailtosendto= $config->get( 'config.mailfrom' );

	if ( $validpointsamount >= $minreq && $validpointsamount <= $userpoints )
	{
		$info_data=JText::_('UPTS2PPL_PPLPAYMENTREQTO').' '.$user->username.' '.JText::_('UPTS2PPL_FOR').' ('.$currency_code.') '.$validmoneyamount;
		$q = "INSERT INTO #__vmvendor_userpoints_details 
		( userid , points, insert_date , status , keyreference , datareference )
		VALUES ('".$user->id."' , '". -$validpointsamount ."' , '".date('Y-m-d H:i:s')."' , '1' , '' , '".$info_data."' ) ";	
		$db->setQuery( $q );
		if (!$db->query()) die($db->stderr(true));
		$q = "UPDATE #__vmvendor_userpoints SET points = points - $validpointsamount WHERE userid='".$user->id."' "	;
		$db->setQuery( $q );
		if (!$db->query()) die($db->stderr(true));
			
			
		$aup_page = 'http://'.$_SERVER["SERVER_NAME"].'administrator/'.JRoute::_('index.php?option=com_vmvendor&view=pointsactivity&userid='.$user->id);
		
		$purpose=urlencode(JText::_('UPTS2PPL_PAYTO').' '.$user->username.' '.JText::_('UPTS2PPL_FOR').' '.$validpointsamount.' '.$pointsname.'. http://'.$_SERVER["SERVER_NAME"]);
		$paypal_page = 'https://www.paypal.com/cgi-bin/webscr/?cmd=_donations&business='.$paypal_useremail.'&item_name='.$purpose.'&amount='.$validmoneyamount.'&no_shipping=1&currency_code='.$currency_code.'&tax=0&bn=PP-DonationsBF';
		
		$mailfrom =	$config->get( 'config.mailfrom' );
		$fromname = $config->get( 'config.fromname' );
		
		$subject = JText::_('UPTS2PPL_PPLPAYMENTREQTO').' '.$user->username.' '.JText::_('UPTS2PPL_FOR').' '.$validmoneyamount.' ('.$currency_code.'). ';
		
		$body = ucfirst($user->username).' '.JText::_('UPTS2PPL_ISREQUESTING').' '.$validmoneyamount.' ('.$currency_code.'). ';
		$body .= JText::_('UPTS2PPL_FIRSTCHECK').' ';
		$body .= $validpointsamount.' '.$pointsname.' '.JText::_('UPTS2PPL_HAVEBEENDEDUCTED');	
		$body .= ': '.$aup_page;
		$body .='. '.JText::_('UPTS2PPL_PROCESSHERE').': ';	
		$body .= $paypal_page;							
		
		$mailerror = '<font color="red"><i class="vmv-icon-cancel"></i> <b>'.JText::_('UPTS2PPL_EMAILERROR').'</b></font>';
  		
		$message->addRecipient($emailtosendto);
		if( $receivecopy =='on' && $emailtosendto !=$user->email ){
			$message->addRecipient($user->email);
			$subject .= JText::_('UPTS2PPL_REQUESTEDCOPY');
			$body 	.= ' '.JText::_('UPTS2PPL_REQUESTEDCOPY');
		}
		$message->setSubject($subject);
		$message->setBody($body);
		$sender = array( $mailfrom, $fromname );
  		$message->setSender($sender);
  		$sent = $message->send();
  		if ($sent == 1)
		{
			$app->enqueueMessage( JText::_('UPTS2PPL_REQUESTSENT') );
		}
		else
			echo  $mailerror;		
	}
	$userpoints = $userpoints  -$validpointsamount;
}
echo '<div>';
echo '<div class="well">
<img src="'.$juri.'modules/mod_vendorpoints2paypal/img/header.png" width="182" height="62" alt="img" />
<div>'.JText::_('UPTS2PPL_INTRO').'<br /><br /></div>';
echo '<div>'.JText::_('UPTS2PPL_YOUR').': <span class="badge">'.$userpoints.'</span></div>';
echo '</div>';
if( $userpoints < $minreq )
{
	if($user->id ==0)
	{
		echo '<div class="alert alert-warning">';
		echo JText::_('UPTS2PPL_YOUNEEDTO').' '.$pointsname.' '.JText::_('UPTS2PPL_4PPLCASH');
		echo '</div>';
	}
	else
	{
		echo '<div>';
		echo JText::_('UPTS2PPL_NOTENOUGHYET1').' '.$pointsname.' '.JText::_('UPTS2PPL_NOTENOUGHYET2');
		echo '<br /><br />';
		echo '<div ><table  class="table ">';
		echo '<tr >';
		echo '<td>'.JText::_('UPTS2PPL_CURRENT').'</td>';
		echo '<td>'.JText::_('UPTS2PPL_REQUIRED').'</td>';
		echo '<td>'.JText::_('UPTS2PPL_MISSING').'</td>';
		echo '</tr>';
		if (!$userpoints)
			$userpoints = 0;
		echo '<tr>';
		echo '<td>'.$userpoints.'</td>';
		echo '<td>'. $minreq.'</td>';
		echo '<td>'. ($minreq - $userpoints) .'</td>';
		echo '</tr>';
		echo '</table></div>';
		echo '</div>';
	}
}
elseif($userpoints>=$minreq)
{
	echo '<script language = "Javascript">
		function echeck(str) {
		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		var emailID=document.frmSample.paypal_useremail
		if (str.indexOf(at)==-1){		   
			emailID.style.backgroundColor = "#ff9999"
		   	alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
		   return false
		   
		}
		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
			emailID.style.backgroundColor = "#ff9999"
		   alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
		   return false
		   
		}
		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
			emailID.style.backgroundColor = "#ff9999"
			alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
			return false
			
		}
		 if (str.indexOf(at,(lat+1))!=-1){
			emailID.style.backgroundColor = "#ff9999"
			alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
			return false
			
		 }
		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
			emailID.style.backgroundColor = "#ff9999"
			alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
			return false
			
		 }
		 if (str.indexOf(dot,(lat+2))==-1){
			emailID.style.backgroundColor = "#ff9999"
			alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
			return false
			
		 }
		 if (str.indexOf(" ")!=-1){
			emailID.style.backgroundColor = "#ff9999"
			alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
			return false
			
		 }
 		 return true					
	}
function ValidateForm(){
	var emailID=document.frmSample.paypal_useremail
	var pointsamount=document.frmSample.pointsamount
	var loading=document.frmSample.loading
	if ((pointsamount.value<'.$minreq.')||(pointsamount.value>'.$userpoints.')){
		pointsamount.style.backgroundColor = "#ff6666"
		alert("'.JText::_('UPTS2PPL_AMNTNOTALLOWED').'")
		pointsamount.focus()
		return false
	}
	if ((emailID.value==null)||(emailID.value=="")){
		emailID.style.backgroundColor = "#ff9999"
		alert("'.JText::_('UPTS2PPL_INVALIDEMAIL').'")
		emailID.focus()
		emailID.style.backgroundColor = "#ffffff"
		return false
	}
	if (echeck(emailID.value)==false){
		emailID.style.backgroundColor = "#ff9999"
		emailID.value=""
		emailID.focus()
		emailID.style.backgroundColor = "#ffffff"
		return false
	}
	input_box=confirm("'.ucfirst($pointsname).' '.JText::_('UPTS2PPL_AREGOINGTO').'");
							if (input_box!=true){
								emailID.style.backgroundColor = "#ff9999"
								emailID.focus()
								return false;
							}
	emailID.style.backgroundColor = "#ffffff"
	loading.style.display = ""
	return true
 }
</script>';
	echo '<SCRIPT language="javascript" >
            function calculate(){
                var pointsamount = Number(document.getElementById("pointsamount").value);             
				if((document.getElementById("pointsamount").value>'.$userpoints.')||(document.getElementById("pointsamount").value<'.$minreq.')){
					var total = "'.JText::_('UPTS2PPL_NOTALLOWED').'";
					document.getElementById("total").value = total;
					document.getElementById("total").style.color = "red";
					document.getElementById("pointsamount").style.backgroundColor = "#ff9999"
				}
				else{
					var total = Number(pointsamount /'.$ratio.');
                	document.getElementById("total").value = total.toFixed('.$decim.');
					document.getElementById("total").style.color = "#000000";
					document.getElementById("pointsamount").style.backgroundColor = ""
				}
            }
        </SCRIPT>';
	echo '<div>';
	
	echo '<form name="frmSample" onSubmit="return ValidateForm()" method="post" >';
	
	echo '<table class="table table-condensed">';
	
	echo '<tr><td>';	
	echo '<label for="pointsamount">'.ucfirst($pointsname).' '.JText::_('UPTS2PPL_TOCONVERT').'</label> ';
	echo '<input type="text" name="pointsamount" id="pointsamount" onkeyup="javascript:calculate()" value="'.$minreq.'" class="form-control"/>';
	echo '</td></tr>';
	echo '<tr><td>';	
	echo '<label>'.$currency_code.' '.JText::_('UPTS2PPL_AMOUNT').':</label>';
	echo '<input style="border:0;background-color:transparent; " type="text" name="total" id="total" readonly size="10" value="'.number_format( $minreq/$ratio , $decim ).'" class="form-control"/>';
	echo '</td></tr>';
	
	
	echo '<tr><td>';	
	echo '<label for="paypal_useremail">'.JText::_('UPTS2PPL_YOURPPLEMAIL').':</label> ';
	echo ' <input type="text" name="paypal_useremail" id="paypal_useremail" size="20" value="'.$paypal_email.'" class="form-control" placeholder="'.JText::_('UPTS2PPL_YOURPPLEMAIL').'" />';
	echo '</td></tr>';
	
	
	
	echo '<tr><td style="color:#D3D3D3;text-align:right;">';	
	echo JText::_('UPTS2PPL_PPLFEE');
	echo '</td></tr>';
	echo '</table>';
	echo '<div class="checkbox">
    <label><input  type="checkbox" name="receivecopy" id="receivecopy" class="checkbox" checked />'.JText::_('UPTS2PPL_RECEIVECOPY').'</label>
	</div>';
	
	//echo '<input type="image" src="'.$juri.'modules/mod_vendorpoints2paypal/img/header.png" name="sendrequest" width="182" height="62" title="'.JText::_('UPTS2PPL_SENDPAYMENTREQ').'" alt="" class="btn btn-lg btn-block btn-default jomNameTips" />';
	echo '<button  class="btn btn-large btn-block btn-primary">'.JText::_('UPTS2PPL_SENDPAYMENTREQ').'</button>';
	echo ' <img src="'.$juri.'modules/mod_vendorpoints2paypal/img/loader.gif" alt="" width="24" height="24" border="0" name = "loading" id= "loading"  style="display: none;" />';

	
	echo '</form>';
	echo '</div>';
}
echo '</div>';