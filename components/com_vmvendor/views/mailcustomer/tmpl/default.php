<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL
 * @Website : http://www.nordmograph.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$cparams 					= JComponentHelper::getParams('com_vmvendor');
$user 			= JFactory::getUser();
$app 			= JFactory::getApplication();
$doc 			= JFactory::getDocument();
$juri 			= JURI::base();
echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/fontello.css">';
//$profileman 				= $cparams->get('profileman');
$naming 				= $cparams->get('naming', 'username');
//echo '<link rel="stylesheet" href="'.$juri.'components/com_vmvendor/assets/css/askvendor.css" type="text/css" />';
				
$yourname = 	'';
$youremail=		'';
if($user->id>0){
	$yourname = 	$user->$naming;
	$youremail=		$user->email;
}
$product_url = $app->input->get('href');
		
	$customer_name = $this->customercontacts[0];
	$emailto		= $this->customercontacts[1];
	$product_name = $this->orderitem[2];
	$order_number = $this->orderitem[4];
if($app->input->get('customer_userid')=='0'){
	$guest_contact = VmvendorModelMailcustomer::getGuestContact();
	$customer_name = $guest_contact[0].' '.$guest_contact[1].' '.$guest_contact[2];
	$emailto		= $guest_contact[3];
	
}
echo '<script type="text/javascript">
	function validateForm(it){
			var warning = "'.JText::_('COM_VMVENDOR_VMVENADD_JS_FIXTHIS').' \n";
			var same = warning;
			if (it.formname.value==""){ warning += " * '.JText::_('COM_VMVENDOR_ASKVENDOR_JS_NAMEREQUIRED').' \n";}
			if (it.formemail.value==""){ warning += " * '.JText::_('COM_VMVENDOR_ASKVENDOR_JS_EMAILREQUIRED').' \n";}
			if (it.formsubject.value==""){ warning += " * '.JText::_('COM_VMVENDOR_ASKVENDOR_JS_SUBJECTREQUIRED').' \n";}
			if (it.formmessage.value==""){ warning += " * '.JText::_('COM_VMVENDOR_ASKVENDOR_JS_MESSAGEREQUIRED').' \n";}
			
			if (warning == same) {;
						return true;
						}
						else
							{ alert(warning); return false;}
						}</script>';
	
	
	$default_subject = JText::_('COM_VMVENDOR_CUSTOMERCONTACT_ABOUTYOURORDER').' '.$order_number.' '.JText::_('COM_VMVENDOR_CUSTOMERCONTACT_ABOUTITEM').' '.ucfirst($product_name);
	
	$default_message = JText::_('COM_VMVENDOR_CUSTOMERCONTACT_DEFAULT_MESS_HELLO').' '.ucfirst($customer_name).', ';
	$default_message .= JText::_('COM_VMVENDOR_CUSTOMERCONTACT_DEFAULT_MESS_THANKYOU').' ';
	$default_message .= $default_subject;
	
	echo '<form method=POST onsubmit="return validateForm(this);" >';
	echo '<input type="hidden" name="formhref" value="'.$app->input->get('href').'">';
	echo '<table class="table table-striped table-condensed">
	<tr><td>'.JText::_('COM_VMVENDOR_ASKVENDOR_YOURNAME').':</td>
	<td><input type="text" name="formname" size="50" value="'.ucfirst($yourname).'" style="background-color:#cccccc" class="form-control" readonly></td></tr>
	<tr><td>'.JText::_('COM_VMVENDOR_ASKVENDOR_YOURMAIL').':</td>
	<td><input type="text" name="formemail" size="50" value="'.$youremail.'" style="background-color:#cccccc" class="form-control" readonly></td></tr>';
	echo '<tr><td>'.JText::_('COM_VMVENDOR_ASKVENDOR_SUBJECT').':</td>
	<td><input type="text" name="formsubject" size="50" value="'.$default_subject.'" class="form-control"></td></tr>
	<tr><td colspan=2>'.JText::_('COM_VMVENDOR_ASKVENDOR_MESSAGE').':<br>';
	echo ' <textarea COLS="50" ROWS="6" name="formmessage" class="form-control" >'.$default_message.'</textarea>';
	echo '</td></tr>
	</table></div>';
	

echo '<div class="modal-footer">
<input type="submit" value="'.JText::_('COM_VMVENDOR_ASKVENDOR_SEND').'" class="btn btn-lg btn-primary" /> ';
echo '<input type="reset" value="'.JText::_('COM_VMVENDOR_ASKVENDOR_RESET').'" class="btn btn-sm btn-default" />
<input type="hidden" name="emailto" value="'.$emailto.'" />
<input type="hidden" name="option" value="com_vmvendor" />
<input type="hidden" name="controller" value="mailcustomer" />
<input type="hidden" name="task" value="mailcustomer" />
</div></form>';