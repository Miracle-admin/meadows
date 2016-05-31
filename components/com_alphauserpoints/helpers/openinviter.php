<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 - Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

/* 
Import emails from addressbook in a textarea on parent page
*/

$inviter 		= new OpenInviter();
$oi_services 	= $inviter->getPlugins();

if(isset($_POST['provider_box'])) 
{
	if (isset($oi_services['email'][$_POST['provider_box']])) $plugType='email';
	elseif (isset($oi_services['social'][$_POST['provider_box']])) $plugType='social';
	else $plugType='';
}
else $plugType = '';

function ers($ers)
	{
	if (!empty($ers))
		{
		$contents="<table cellspacing='0' cellpadding='0' style='border:1px solid red;' align='center' class='tbErrorMsgGrad'><tr><td valign='middle' style='padding:3px' valign='middle' class='tbErrorMsg'><img src='/images/ers.gif'></td><td valign='middle' style='color:red;padding:5px;'>";
		foreach ($ers as $key=>$error)
			$contents.="{$error}<br >";
		$contents.="</td></tr></table><br >";
		return $contents;
		}
	}
	
function oks($oks)
	{
	if (!empty($oks))
		{
		$contents="<table border='0' cellspacing='0' cellpadding='10' style='border:1px solid #5897FE;' align='center' class='tbInfoMsgGrad'><tr><td valign='middle' valign='middle' class='tbInfoMsg'><img src='/images/oks.gif' ></td><td valign='middle' style='color:#5897FE;padding:5px;'>	";
		foreach ($oks as $key=>$msg)
			$contents.="{$msg}<br >";
		$contents.="</td></tr></table><br >";
		return $contents;
		}
	}

if (!empty($_POST['step'])) $step=$_POST['step'];
else $step='get_contacts';

$ers=array();$oks=array();
$import_ok=false;
$done=false;

$provider;

if ($_SERVER['REQUEST_METHOD']=='POST')
	{
	if ($step=='get_contacts')
		{
		if (empty($_POST['email_box']))
			$ers['email']= JText::_( 'AUP_OI_EMAIL_MISSING' );
		if (empty($_POST['password_box']))
			$ers['password']= JText::_( 'AUP_OI_PASSWORD_MISSING' );
		if (empty($_POST['provider_box']))
			$ers['provider']= JText::_( 'AUP_OI_PROVIDER_MISSING' );
		if (count($ers)==0)
			{
			$inviter->startPlugin($_POST['provider_box']);
			$internal=$inviter->getInternalError();
			if ($internal)
				$ers['inviter']=$internal;
			elseif (!$inviter->login($_POST['email_box'],$_POST['password_box']))
				{
				$internal=$inviter->getInternalError();
				$ers['login']=($internal?$internal: JText::_( 'AUP_OI_LOGIN_FAILED' ));				
				}
			elseif (false===$contacts=$inviter->getMyContacts())
				$ers['contacts'] = JText::_( 'AUP_OI_UNABLE_TO_GET_CONTACTS' );
			else
				{
				$import_ok=true;
				$step='send_invites';
				$provider = $_POST['provider_box'];
				$_POST['oi_session_id']=$inviter->plugin->getSessionID();
				$_POST['message_box']='';
				}
			}
		}	
	}	
else
	{
	$_POST['email_box']='';
	$_POST['password_box']='';
	$_POST['provider_box']='';
	}

$contents = "<script type='text/javascript'>
	function toggleAll(element) 
	{
	var form = document.forms.openinviter, z = 0;
	for(z=0; z<form.length;z++)
		{
		if(form[z].type == 'checkbox')
			form[z].checked = element.checked;
	   	}
	}
</script>";

$contents .= "<form action='' method='POST' name='openinviter'>".'<br />'.ers($ers);

if (!$done)
	{
	if ($step=='get_contacts')
		{
		$contents = $contents. "<table width='100%' align='center' style='border:none;'>
			<tr>
				<td width=25%' align='right'>
					<label for='email_box'>".JText::_( 'AUP_EMAIL' )."</label>
				</td>
				<td width='75%'>
					<input class='thTextbox' type='text' name='email_box' value='{$_POST['email_box']}'>
				</td>
			</tr><td></td><td style='color:#999999; font-size:80%;'>".JText::_( 'AUP_OI_DESCRIPTION_FIELD_EMAIL' )."</td></tr>
			<tr>
				<td width='25%' align='right'>
					<label for='password_box'>".JText::_( 'AUP_PASSWORD' )."</label>
				</td>
				<td width='75%'>
					<input class='thTextbox' type='password' name='password_box' value='{$_POST['password_box']}'>
				</td>
			</tr><td></td><td style='color:#999999;font-size:80%;'>".JText::_( 'AUP_OI_DESCRIPTION_FIELD_PASSWORD' )."</tr>
			<tr>
				<td width='25%' align='right'>
					<label for='provider_box'>".JText::_( 'AUP_OI_ACCOUNT_WEBSITE' )."</label>
				</td>
				<td width='75%'>
					<select class='thSelect' name='provider_box'><option value=''></option>";
					
						foreach ($oi_services as $type=>$providers)	
						{
							$contents.="<option disabled>".$inviter->pluginTypes[$type]."</option>";
							foreach ($providers as $provider=>$details)
								$contents.="<option value='{$provider}'".($_POST['provider_box']==$provider?' selected':'').">{$details['name']}</option>";
						}
						$contents.="</select>
				</td>
			</tr><td></td><td style='color:#999999;font-size:80%;'>".JText::_( 'AUP_OI_DESCRIPTION_FIELD_ACCOUNT' )."</tr>
			<tr class='thTableImportantRow'>
				<td></td><td align='left'> <br />
					<input class='thButton' type='submit' name='import' value='".JText::_( 'AUP_OI_IMPORT_CONTACTS' )."'>
				</td>
			</tr>
		</table>
		<input type='hidden' name='step' value='get_contacts'>";
		}		
	}
if (!$done)
	{
		if ($step=='send_invites')
		{
		if (1 || $inviter->showContacts())
			{
			$contents.="<table width='100%' align='center'>
			<tr>
				<td style='background:#f6f6f6;' colspan='3'>
					".JText::_( 'AUP_OI_FETCH_CONTACTS' )."
				</td>
			</tr>";
			if (count($contacts)==0)
				$contents.="<tr><td  style='padding:20px;background:#FFCCFF;' colspan='3'>".JText::_( 'AUP_OI_YOU_DONT_HAVE_ANY_CONTACTS' )."</td></tr>";
			else if($provider=='facebook' || $provider=='linkedin')
			{
				// facebook and linkedin does not allow you to import emails
				$contents.="<td colspan='3' style='padding:3px;'>
					<input type='submit' name='send_invites' value='".JText::_( 'AUP_INVITE' )."'>
					</td>
				</tr>
				<tr>
				<td style='background:#f6f6f6;'  width='15%'>
					<input type='checkbox' onChange='toggleAll(this)' name='toggle_all' title='".JText::_( 'AUP_OI_SELECT_DESELECT_ALL' )."' checked>
				</td>
				<td style='background:#f6f6f6;'  width='75%'>
					".JText::_( 'AUP_NAME' )."
				</td>
				<td style='background:#f6f6f6;'  width='10%'>
				</td> </tr>";
				$odd=true;$counter=0;
				foreach ($contacts as $email=>$name)
					{
						$counter++;
						$contents.="<tr>
						<td>
						<input name='check_{$counter}' value='{$counter}' type='checkbox' class='thCheckbox' checked>
						<input type='hidden' name='email_{$counter}' value='{$email}'>
						<input type='hidden' name='name_{$counter}' value='{$name}'>
						</td>
						<td>{$name}</td>
						<td></td></tr>";
					}
				$contents.="<tr><td colspan='3' style='padding:3px;'>";
				$contents.="<input type='submit' name='send' value='".JText::_( 'AUP_INVITE' )."'";
				$contents.="<input type='hidden' name='step' value='send_invites'>
					<input type='hidden' name='provider_box' value='{$_POST['provider_box']}'>
					<input type='hidden' name='email_box' value='{$_POST['email_box']}'>
					<input type='hidden' name='oi_session_id' value='{$_POST['oi_session_id']}'>";
				$contents.= "<input type='hidden' name='message_box' value='".$inviter->settings."'>";
				$contents.=	"</td></tr>";
			}
			else
				{
				
				$contents.="<td colspan='3' style='padding:3px;'>
					<input type='button' name='send2' value='".JText::_( 'AUP_OI_ADD_SELECTED_EMAILS' )."' onClick=\"pushContacts();\">
					</td>
				</tr>
				<tr>
				<td style='background:#f6f6f6;'  width='15%'>
					<input type='checkbox' onChange='toggleAll(this)' name='toggle_all' title='".JText::_( 'AUP_OI_SELECT_DESELECT_ALL' )."' checked>
				</td>
				<td style='background:#f6f6f6;'  width='25%'>
					".JText::_( 'AUP_NAME' )."
				</td>
				<td style='background:#f6f6f6;'  width='60%'>
					".JText::_( 'AUP_EMAIL' )."
				</td> </tr>";
				$odd=true;$counter=0;
				foreach ($contacts as $email=>$name)
					{
						$counter++;
						$contents.="<tr>
						<td>
							<input name='checkboxgrp' value='{$counter}' type='checkbox' class='thCheckbox' checked>
							<input type='hidden' name='emailboxgrp' value='{$email}'>
							<input type='hidden' name='name_{$counter}' value='{$name}'>
						</td>
						<td>{$name}</td>
						<td>{$email}</td></tr>";
					}
				$contents.="<tr><td colspan='3' style='padding:3px;'>";
				$contents.="<input type='button' name='send' value='".JText::_( 'AUP_OI_ADD_SELECTED_EMAILS' )."' onClick=\"pushContacts();\">";
				$contents.=	"</td></tr>";
				}
			$contents.="</table>";
			$contents.="<input type='hidden' name='step' value='send_invites'>
			<input type='hidden' name='provider_box' value='{$_POST['provider_box']}'>
			<input type='hidden' name='email_box' value='{$_POST['email_box']}'>
			<input type='hidden' name='oi_session_id' value='{$_POST['oi_session_id']}'>";
			}
		}
	}
	
$contents.="</form>";

echo $contents;

?>
<script language="javascript" type="text/javascript">
	function pushContacts() {	
		var emails = "";
		var listemails = "";
		for (i = 0; i < document.openinviter.checkboxgrp.length; i++)
		{
			if(document.openinviter.checkboxgrp[i].checked == true )
			{
				emails = emails + document.openinviter.emailboxgrp[i].value + ", ";					
			}
			
		}		
		var j = emails.length;
		if(j) {listemails = emails.substring(0,j-2);}		
		
		window.parent.document.getElementById('importedemails').value = listemails;
		window.parent.document.getElementById('sbox-window').close();	
	}
</script>