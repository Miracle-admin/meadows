<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	helpers/mail.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Helper Class for sending Emails (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class EmailHelper {
	
	function getTemplate($tempfor){
		$db = JFactory :: getDbo();
	
		$query = "SELECT * FROM #__jblance_emailtemplate WHERE templatefor = ".$db->Quote($tempfor);
		$db->setQuery($query);
		$template = $db->loadObject();
		return $template;
	}
	
	public static function getSuperAdminEmail(){
		$db = JFactory::getDbo();
	
		/* $query = "SELECT a.name, a.email, a.sendEmail FROM #__users AS a, #__user_usergroup_map AS b ".
				 "WHERE a.id = b.user_id AND b.group_id =".$db->quote(8); */
		// get all admin users
		$query = 'SELECT name, email, sendEmail FROM #__users WHERE sendEmail=1';
		$db->setQuery($query);
		if($db->getErrorNum()){
			JError::raiseError(500, $db->stderr());
		}
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	function getSenderInfo(){
		$app = JFactory::getApplication();
		
		$config = JblanceHelper::getConfig();
		$fromName = $config->mailFromName;
		$fromAddress = $config->mailFromAddress;
		
		if(!$fromName)
			$fromName =  $app->get('fromname');
		
		if(!$fromAddress)
			$fromAddress =  $app->get('mailfrom');
		
		$sitename = $app->get('sitename');
		
		$return['fromname'] = $fromName;
		$return['fromaddress'] = $fromAddress;
		$return['sitename'] = $sitename;
		
		return $return;
	}
	
	function buildCustomFieldValues(){
		$db 	= JFactory::getDbo();
		$return = array();
		$query = "SELECT fv.*, IF(fv.userid > 0, CONCAT(fv.userid,'_PROFILE'), CONCAT(fv.projectid,'_PROJECT')) AS field_type FROM #__jblance_custom_field_value fv
		LEFT JOIN #__jblance_custom_field c ON fv.fieldid=c.id";
		$db->setQuery($query);//echo $query;
		$fieldvalues = $db->loadObjectList();
	
		/* foreach ($fieldvalues as $fv){
		 $return['CUSTOM_'.$fv->fieldid.'_'.$fv->field_type] = $fv->value;
		} */
		return $fieldvalues;
	}
	
	//2.sendRegistrationMail
	function sendRegistrationMail(&$usern, $password, $facebook=false){
	
		$userid		= $usern->get('id');
		$name 		= $usern->get('name');
		$recipient	= $usern->get('email');
		$username 	= $usern->get('username');
	
		$usertype = '';
	
		$jbuser =JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
		$usergroupInfo = $jbuser->getUserGroupInfo($userid);
		$usertype = $usergroupInfo->name;
		$jbrequireApproval = $usergroupInfo->approval;	//require JoomBri Admin approval
	
		$usersConfig 	=JComponentHelper::getParams('com_users');
		$useractivation = $usersConfig->get('useractivation');
		
		$jAdminApproval = ($usersConfig->get('useractivation') == '2') ? 1 : 0;	//require Joomla Admin approval
		
		$requireApproval = $jbrequireApproval | $jAdminApproval;	//approval is required either JoomBri or Joomla require approval
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL	 = JURI::base();
		$adminURL 	 = JURI::base().'administrator';
		//$actLink 	 = $siteURL.'index.php?option=com_users&task=registration.activate&token='.$usern->get('activation');
		$actLink 	 = $siteURL.'index.php?option=com_jblance&task=developer.activate&token='.$usern->get('activation');
	
		/* Send notification to registered user */
		
		//get the email template
		if($requireApproval){
			$template = $this->getTemplate('newuser-pending-approval');
		}
		else {
			if($useractivation == 1){
				$template = $this->getTemplate('newuser-activate');
			}
			else {
				$template = $this->getTemplate('newuser-login');
			}
		}
		
		// If the user is signing up from Facebook, set the template to newuser-login
		if($facebook){
			$template = $this->getTemplate('newuser-facebook-signin');
			$usertype = JText::_('COM_JBLANCE_SIGN_IN_WITH_FACEBOOK');
		}
		
		//get the status tag
		if($requireApproval)
			$status = JText::_('COM_JBLANCE_PENDING');
		else
			$status = JText::_('COM_JBLANCE_APPROVED');
	
		$tags = array("[NAME]", "[SITENAME]", "[ACTLINK]", "[SITEURL]", "[ADMINURL]", "[USERNAME]", "[PASSWORD]", "[USEREMAIL]", "[USERTYPE]", "[STATUS]");
		$tagsValues = array("$name", "$sitename", "$actLink", "$siteURL", "$adminURL", "$username", "$password", "$recipient", "$usertype", "$status");
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1);
	
		/* Send notification to all administrators */
		
		//get all super administrator
		$rows = self::getSuperAdminEmail();
		
		//get the email template
		$template = $this->getTemplate('newuser-details');
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		// get super administrators id
		foreach($rows as $row){
			JFactory::getMailer()->sendMail($fromaddress, $fromname, $row->email, $subject, $message, 1);
		}
	}
	
	//
	function sendUserAccountApproved($userid){
		$user 		= JFactory::getUser($userid);
		$name 		= $user->name;
		$recipient  = $user->email;
		$username 	= $user->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL = JURI::root();
		
		$tags = array("[NAME]", "[EMAIL]", "[USERNAME]", "[SITENAME]", "[SITEURL]");
		$tagsValues = array("$name", "$recipient", "$username", "$sitename", "$siteURL");
		
		//get the email template
		$template = $this->getTemplate('newuser-account-approved');
		
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
		
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		// Send email to user
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1);
	}
	
	//3.Send alert to Admin on new Subscription
	function alertAdminSubscr($subscrid, $userid){
		$user 	= JFactory::getUser($userid);
		$row	= JTable::getInstance('plansubscr', 'Table');
		$row->load($subscrid);
	
		$plan	= JTable::getInstance('plan', 'Table');
		$plan->load($row->plan_id);
		
		//Alert admin based on the plan settings - return if set to 'No'
		if(!$plan->alert_admin)
			return;
	
		$name = $user->name;
		$userEmail = $user->email;
		$username = $user->username;
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		
		$subscrid = $row->id;
		$planname = $plan->name;
		$gateway =JblanceHelper::getGwayName($row->gateway);
	
		if($row->approved)
			$status = JText::_('COM_JBLANCE_APPROVED');
		else
			$status = JText::_('COM_JBLANCE_APPROVAL_PENDING');
	
		$tags = array("[NAME]", "[USERNAME]", "[PLANNAME]", "[SITENAME]", "[SUBSCRID]", "[USEREMAIL]", "[GATEWAY]", "[PLANSTATUS]");
		$tagsValues = array("$name", "$username", "$planname", "$sitename", "$subscrid", "$userEmail", "$gateway", "$status");
	
		//get the email template
		$template = $this->getTemplate('subscr-details');
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		//get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach ($rows as $row){
			JFactory::getMailer()->sendMail($fromaddress, $fromname, $row->email, $subject, $message, 1);
		}
	}

	//4.Send alert to Subscribers on new Subscription
	function alertUserSubscr($subscrid, $userid){
		$user 	= JFactory::getUser($userid);
		$row	= JTable::getInstance('plansubscr', 'Table');
		$row->load($subscrid);
	
		$plan	= JTable::getInstance('plan', 'Table');
		$plan->load($row->plan_id);
		
		$name = $user->name;
		$recipient = $user->email;
		$username = $user->username;
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL = JURI::base();
	
		$subscrid = $row->id;
		$planname = $plan->name;
	
		$tags = array("[NAME]", "[PLANNAME]", "[SITENAME]", "[SITEURL]", "[ADMINEMAIL]");
		$tagsValues = array("$name", "$planname", "$sitename", "$siteURL", "$fromaddress");
	
		//get the email template
		if($row->approved){
			$template = $this->getTemplate('subscr-approved-auto');
		}
		else {
			$template = $this->getTemplate('subscr-pending');
		}
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		// Send email to user
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1);
	}	

	//Send alert to user when the subscription is approved by admin
	function sendSubscrApprovedEmail($subscrid, $userid){
		$row	= JTable::getInstance('plansubscr', 'Table');
		$row->load($subscrid);
	
		$plan	= JTable::getInstance('plan', 'Table');
		$plan->load($row->plan_id);
	
		$data 		= JFactory::getUser($userid);
		$name 		= $data->name;
		$recipient  = $data->email;
		$username 	= $data->username;
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL = JURI::root();
	
		$subscrid = $row->id;
		$planname = $plan->name;
	
		$tags = array("[NAME]", "[PLANNAME]", "[SITENAME]", "[SITEURL]");
		$tagsValues = array("$name", "$planname", "$sitename", "$siteURL");
	
		//get the email template
		$template = $this->getTemplate('subscr-approved-admin');
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		// Send email to user
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1);
	}
	
	//send new project notification to users who can bid for projects
	function sendNewProjectNotification($project_id, $isNewProject){
		$db 	= JFactory::getDbo();
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
		
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		$currencycode = $config->currencyCode;
		$dformat	  = $config->dateFormat;
		
		//project details
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname	= $project->project_title;
		$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
		$projectType	= $project->project_type;
		$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
		$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
		$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
		$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
		$expires		= $project->expires;
		$projecturl 	= JURI::root().'index.php?option=com_jblance&view=project&layout=detailproject&id='.$project->id;
		$isPvtInvite	= $project->is_private_invite;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $senderInfo['fromaddress'];
		
		$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[CATEGORYNAME]", "[CURRENCYSYM]", "[CURRENCYCODE]", "[BUDGETMIN]", "[BUDGETMAX]", "[STARTDATE]", "[EXPIRE]", "[PROJECTURL]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$categoryname", "$currencysym", "$currencycode", "$budgetmin", "$budgetmax", "$startdate", "$expires", $projecturl);
		
		/*
		 * if the project is not private invite, get the list of users who can bid and matching the project skills
		 * else get the list of users from the invitees column
		 */
		if(!$isPvtInvite){
			//get the list of usergroups who can bid
			$query = "SELECT * FROM #__jblance_usergroup WHERE published=1";
			$db->setQuery($query);
			$ugs = $db->loadObjectList();
			$canBidUserGroup = array();
			
			foreach($ugs as $ug){
				$info = $jbuser->getUserGroupInfo(null, $ug->id);
				if($info->allowBidProjects)
					$canBidUserGroup[] = $ug->id;
			}
			
			$receivableIds = implode($canBidUserGroup, ',');
			//if there is no user group who can bid, just skip the rest of the code
			if(empty($receivableIds)){
				return false;
			}
			
			//get the email template
			$template = $this->getTemplate('proj-new-notify');
			
			//get subject
			$subject = $template->subject;
			if(!$isNewProject)
				$subject = $subject.' ('.JText::_('COM_JBLANCE_PROJECT_DETAILS_CHANGED').')';
			$subject = str_replace($tags, $tagsValues, $subject);
			$subject = html_entity_decode($subject, ENT_QUOTES);
			
			//get message body
			$message = $template->body;
			$message = str_replace($tags, $tagsValues, $message);
			$message = html_entity_decode($message, ENT_QUOTES);
			
			$where = '';
			$queryStrings[] = "ju.ug_id IN ($receivableIds)";
			$queryStrings[] = "n.notifyNewProject=1";
			$queryStrings[] = "u.block=0";
			
			//get the relevent project for the user id based on the category
			$id_categ = explode(',', $project->id_category);
			if(is_array($id_categ)){
				$miniquery = array();
				foreach($id_categ as $cat){
					$miniquery[] = "FIND_IN_SET($cat, ju.id_category)";
				}
				$querytemp = '('.implode(' OR ', $miniquery).')';
				$queryStrings[] = $querytemp;
			}
			
			//filter projects by matching user locaton with project
			$queryStrings[] = "ju.id_location=".$db->quote($project->id_location);
			
			$where = (count($queryStrings) ? ' WHERE ('.implode(') AND (', $queryStrings).') ' : '');
			
			//get array of user emails
			$query = "SELECT u.email FROM #__jblance_user ju ".
					 "INNER JOIN #__users u ON u.id=ju.user_id ".
					 "LEFT JOIN #__jblance_notify n ON ju.user_id=n.user_id ".
 					  $where;
			$db->setQuery($query);//echo $query;exit;
			$bcc = $db->loadColumn();
		}
		else {
			//get the email template
			$template = $this->getTemplate('proj-private-invite');
			
			//get subject
			$subject = $template->subject;
			$subject = str_replace($tags, $tagsValues, $subject);
			$subject = html_entity_decode($subject, ENT_QUOTES);
			
			//get message body
			$message = $template->body;
			$message = str_replace($tags, $tagsValues, $message);
			$message = html_entity_decode($message, ENT_QUOTES);
			
			//get array of user emails from invitees column
			$invite_user_id = $project->invite_user_id;
			$query = "SELECT DISTINCT u.email FROM #__jblance_user ju ".
					 "INNER JOIN #__users u ON u.id=ju.user_id ".
					 "LEFT JOIN #__jblance_notify n ON ju.user_id=n.user_id ".
					 "WHERE n.notifyNewProject=1 AND u.block=0 AND u.id IN ($invite_user_id)";
			$db->setQuery($query);//echo $query;exit;
			$bcc = $db->loadColumn();
		}
		
		// replace custom field tags
		$message = self::buildCustomFieldTags($message, 0, $project->publisher_userid, $project_id);
		
		// Send email to user
		//if($isNewProject)		//UNCOMMENT '//' this line if you want to send message for new projects only.
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1, null, $bcc);
	}
	
	
	
	
	//1.send new project notification to newly registered user(proj-new-notify-reg)[TESTED OK]
	
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[CATEGORYNAME]", "[CURRENCYSYM]", "[CURRENCYCODE]", "[BUDGETMIN]", "[BUDGETMAX]", "[STARTDATE]", "[EXPIRE]", "[PROJECTURL]","[NEW_USERNAME]","[NEW_PASSWORD]","[ACTIVATION_LINK]" );
	$tagsValues = array("$sitename", "$siteURL", "$projectname", "$categoryname", "$currencysym", "$currencycode", "$budgetmin", "$budgetmax", "$startdate", "$expires", $projecturl,"$Username","$password","$activationLink");
	*/
	function sendNewProjectRegistrationNotificationUser($project_id,$user)
	{
	
	$config 	      = JblanceHelper::getConfig();
	$dformat	      = $config->dateFormat;
	$newsLetter       = "newclientpostedproject";
	$project          = JTable::getInstance('project', 'Table');
	$project->load($project_id);
	$senderInfo       = self::getSenderInfo();
	$sitename 	      = $senderInfo['sitename'];
	$siteURL 	      = JURI::root();
	$projectname	  = $project->project_title;
	$categoryname	  = JblanceHelper::getCategoryNames($project->id_category);
	$currencysym      = $config->currencySymbol;
	$currencycode     = $config->currencyCode;
	$projectType	  = $project->project_type;
	$perHr			  = ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
	$budgetmin 		  = JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
	$budgetmax		  = JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
	$startdate		  = JHtml::_('date', $project->start_date, $dformat, false);
	$expires		  = $project->expires;
	$projecturlb 	  = JRoute::_(JUri::root().'administrator/index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='.$project->id);
	$projecturlf 	  = JRoute::_(JUri::root().'index.php?option=com_jblance&view=project&layout=projectdashboard&id='.$project->id.'&Itemid=340');
	$Username         =$user->newUsername;
	$password         =$user->newPassword;
	$activationLink   =JRoute::_(JUri::root().'index.php?option=com_jblance&task=project.validateemail&email='.$user->email.'&id='.$user->activationLink.'&pid='.$project->id);
	//Sender info
	
	$senderInfo       = self::getSenderInfo();
	$sitename 	      = $senderInfo['sitename'];
	$fromname 	      = $senderInfo['fromname'];
	$fromaddress      = $senderInfo['fromaddress'];
	$siteURL 	      = JURI::root();
	$recipient 	      = $user->email;
	
	
	$params  = array("cname"          => $Username,
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "username"       => $Username, 
				     "password"       => $password,
                     "useremail"      => $user->email,
				     "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlb"    => $projecturlb,
                     "projecturlf"    => $projecturlf,
                     "activationlink" => $activationLink
					 );
					 
					 
	
	
	
	
	
    return $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
	
	
	}
	
	//2.send new project notification to administrator(newuser-posted-project)[TESTED OK]
	/*
    $tags=array("[NAME]","[SITENAME]","[SITEURL]","[USERNAME]","[PASSWORD]","[USEREMAIL]","[USERTYPE]","[PROJECTNAME]","[CATEGORYNAME]","[CURRENCYSYM]","[CURRENCYCODE]","[BUDGETMIN]","[BUDGETMAX]","[STARTDATE]","[EXPIRE]","[PROJECTURL]");
	$tagsValues = array("$Username", "$sitename", "$siteURL", "$Username", "$password", "$useremail", "$usertype", "$projectname", "$categoryname", "$currencysym", "$currencycode","$budgetmin","$budgetmax","$startdate","$expires", $projecturl);
	*/
	
	function sendNewProjectRegistrationNotificationAdmin($project_id,$user)
	{
	
	$config 	  = JblanceHelper::getConfig();
	$dformat	  = $config->dateFormat;
	$newsLetter       = "newclientpostedprojectadminnoti";
	
	$project = JTable::getInstance('project', 'Table');
	$project->load($project_id);
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$siteURL 	 = JURI::root();
	$projectname	= $project->project_title;
	$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
	$currencysym  = $config->currencySymbol;
	$currencycode = $config->currencyCode;
	$projectType	= $project->project_type;
	$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
	$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
	$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
	$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
	$expires		= $project->expires;
	$projecturlb 	= JURI::base().'administrator/index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='.$project->id;
	$projecturlf 	  = JRoute::_(JUri::root().'index.php?option=com_jblance&view=project&layout=projectdashboard&id='.$project->id.'&Itemid=340');
	$projecturlb 	= JURI::base().'administrator/index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='.$project->id;
	$Username       =$user->newUsername;
	$password       =$user->newPassword;
	$activationLink =$user->activationLink;
	$useremail      =$user->email;
	
	
	//Sender info
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$fromname 	 = $senderInfo['fromname'];
	$fromaddress = $senderInfo['fromaddress'];
	$siteURL 	 = JURI::root();
	$recipient 	 = $senderInfo['fromaddress'];
	
	
	
	$params  = array(
		             "cname"          => $Username,
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "username"       => $Username, 
				     "password"       => $password,
                     "useremail"      => $useremail,
					 "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlb"    => $projecturlb,
                     "projecturlf"    => $projecturlf,
                     "activationlink" => $activationLink
					 );
	$rows = self::getSuperAdminEmail();		


foreach($rows as $row){
		return	$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
		}

	

	
	
	}
	
	//3.send email validated notification to administrator(newuser-validated-email)(TESTED)
	/*
	$tags=array("[NAME]","[SITENAME]","[SITEURL]","[USERNAME]","[USEREMAIL]","[USERTYPE]","[PROJECTNAME]","[CATEGORYNAME]","[CURRENCYSYM]","[CURRENCYCODE]","[BUDGETMIN]","[BUDGETMAX]","[STARTDATE]","[EXPIRE]","[PROJECTURL]","[PROJECTUPGRADES]" );
	$tagsValues = array("$Username", "$sitename", "$siteURL", "$Username", "$useremail", "$usertype", "$projectname", "$categoryname", "$currencysym", "$currencycode","$budgetmin","$budgetmax","$startdate","$expires", "$projecturl","$upgds");
	*/
	function sendEmailValidationNotificationAdmin($project_id,$userId)
	{
	
	$config 	  = JblanceHelper::getConfig();
	$dformat	  = $config->dateFormat;
	
	$user=JFactory::getUser($userId);
	
	$project = JTable::getInstance('project', 'Table');
	$project->load($project_id);
	$newsLetter="sendEmailValidationNotificationAdmin";
	$upgrades=array("Featured"=>"is_featured","Urgent"=>"is_urgent","Private"=>"is_private","Sealed"=>"is_sealed","Assisted"=>"is_assisted");
	$upgds=array();
	foreach($upgrades as $upgk=>$upgv):
	if($project->$upgv==1)
    $upgds[]=$upgk;
	endforeach;
	if(count($upgds)>0)
	$upgds=implode(' , ',$upgds);
	else
	$upgds="None";
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$siteURL 	 = JURI::root();
	$projectname	= $project->project_title;
	$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
	$currencysym  = $config->currencySymbol;
	$currencycode = $config->currencyCode;
	$projectType	= $project->project_type;
	$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
	$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
	$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
	$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
	$expires		= $project->expires;
	$projecturlb 	= JURI::base().'administrator/index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='.$project->id;
	$Username       =$user->name;
	$useremail      =$user->email;
	$usertype       ="";
	
	
	//Sender info
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$fromname 	 = $senderInfo['fromname'];
	$fromaddress = $senderInfo['fromaddress'];
	$siteURL 	 = JURI::root();
	
	$rows = self::getSuperAdminEmail();	
	
	$params  = array(
		             "cname"          => $Username,
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "username"       => $Username, 
				     "useremail"      => $useremail,
					 "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlb"    => $projecturlb,
                     "projectupgrades"=> $upgds
					 );
					 
					
		foreach($rows as $row)
               {	
			   
	           return $this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
	           }
	}
	
	//4.user upgraded project(user-upgraded-project)(TESTED OK)
	/*
	$tags=array("[NAME]","[SITENAME]","[SITEURL]","[USERNAME]","[USEREMAIL]","[USERTYPE]","[PROJECTNAME]","[CATEGORYNAME]","[CURRENCYSYM]","[CURRENCYCODE]","[BUDGETMIN]","[BUDGETMAX]","[STARTDATE]","[EXPIRE]","[PROJECTURL]","[PROJECTUPGRADES]" );
	
	$tagsValues = array("$Username", "$sitename", "$siteURL", "$Username", "$useremail", "$usertype", "$projectname", "$categoryname", "$currencysym", "$currencycode","$budgetmin","$budgetmax","$startdate","$expires", "$projecturl","$upgds");
	*/
		function sendProjectupgradeNotificationAdmin($project_id,$userId)
	{
	
	$config 	  = JblanceHelper::getConfig();
	$dformat	  = $config->dateFormat;
	$newsLetter   = "sendProjectupgradeNotificationAdmin";
	$user=JFactory::getUser($userId);
	
	$project = JTable::getInstance('project', 'Table');
	$project->load($project_id);
	
	$upgrades=array("Featured"=>"is_featured","Urgent"=>"is_urgent","Private"=>"is_private","Sealed"=>"is_sealed","Assisted"=>"is_assisted");
	$upgds=array();
	foreach($upgrades as $upgk=>$upgv):
	if($project->$upgv==1)
    $upgds[]=$upgk;
	endforeach;
	if(count($upgds)>0)
	$upgds=implode(' , ',$upgds);
	else
	$upgds="None";
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$siteURL 	 = JURI::root();
	$projectname	= $project->project_title;
	$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
	$currencysym  = $config->currencySymbol;
	$currencycode = $config->currencyCode;
	$projectType	= $project->project_type;
	$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
	$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
	$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
	$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
	$expires		= $project->expires;
	$projecturlb 	= JURI::base().'administrator/index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='.$project->id;
	$projecturlf 	  = JRoute::_(JUri::root().'index.php?option=com_jblance&view=project&layout=projectdashboard&id='.$project->id.'&Itemid=340');
	$Username       =$user->name;
	$useremail      =$user->email;
	$usertype       ="Company";
	
	
	//Sender info
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$fromname 	 = $senderInfo['fromname'];
	$fromaddress = $senderInfo['fromaddress'];
	$siteURL 	 = JURI::root();
	$recipient 	 = $senderInfo['fromaddress'];
	
	
	
	
	
	
	$params  = array("cname"          => $Username,
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "username"       => $Username, 
				     "password"       => $password,
                     "useremail"      => $useremail,
					 "projectupgrades"=> $upgds,
                     "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlb"    => $projecturlb,
                     "projecturlf"    => $projecturlf,
                     "activationlink" => $activationLink
					 );
					 
					 
					 $rows = self::getSuperAdminEmail();	
	           foreach($rows as $row)
               {	
			   return $this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
	           }
	
	
	
	}
	//5.(TESTED OK)
	function sendProjectupgradeNotificationUser($project_id,$userId)
	{
	
	$config 	  = JblanceHelper::getConfig();
	$dformat	  = $config->dateFormat;
	$newsLetter   = "sendProjectupgradeNotificationUser";
	$user=JFactory::getUser($userId);
	
	$project = JTable::getInstance('project', 'Table');
	$project->load($project_id);
	
	$upgrades=array("Featured"=>"is_featured","Urgent"=>"is_urgent","Private"=>"is_private","Sealed"=>"is_sealed","Assisted"=>"is_assisted");
	$upgds=array();
	foreach($upgrades as $upgk=>$upgv):
	if($project->$upgv==1)
    $upgds[]=$upgk;
	endforeach;
	if(count($upgds)>0)
	$upgds=implode(' , ',$upgds);
	else
	$upgds="None";
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$siteURL 	 = JURI::root();
	$projectname	= $project->project_title;
	$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
	$currencysym  = $config->currencySymbol;
	$currencycode = $config->currencyCode;
	$projectType	= $project->project_type;
	$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
	$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
	$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
	$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
	$expires		= $project->expires;
	$projecturlb 	= JURI::base().'administrator/index.php?option=com_jblance&view=admproject&layout=editproject&cid[]='.$project->id;
	$projecturlf 	  = JRoute::_(JUri::root().'index.php?option=com_jblance&view=project&layout=projectdashboard&id='.$project->id.'&Itemid=340');
	$Username       =$user->name;
	$useremail      =$user->email;
	$usertype       ="Company";
	
	
	//Sender info
	
	$senderInfo  = self::getSenderInfo();
	$sitename 	 = $senderInfo['sitename'];
	$fromname 	 = $senderInfo['fromname'];
	$fromaddress = $senderInfo['fromaddress'];
	$siteURL 	 = JURI::root();
	$recipient 	 = $useremail;
	
	
	
	
	
	
	$params  = array("cname"      => $Username,
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "username"       => $Username, 
				     "password"       => $password,
                     "useremail"      => $useremail,
					 "projectupgrades"=> $upgds,
                     "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlb"    => $projecturlb,
                     "projecturlf"    => $projecturlf,
                     "activationlink" => $activationLink
					 );
	
	
	return $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
	
	}
	
	
	//6.integrated acymailing(proj-private-invite)
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[CATEGORYNAME]", "[CURRENCYSYM]", "[CURRENCYCODE]", "[BUDGETMIN]", "[BUDGETMAX]", "[STARTDATE]", "[EXPIRE]", "[PROJECTURL]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$categoryname", "$currencysym", "$currencycode", "$budgetmin", "$budgetmax", "$startdate", "$expires", $projecturl);
	*/
	function sendInviteToProjectNotification($project_id, $user_id){
	
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		$currencycode = $config->currencyCode;
		$dformat	  = $config->dateFormat;
	    $newsLetter   = "sendInviteToProjectNotification";
		//project details
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname	= $project->project_title;
		$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
		$projectType	= $project->project_type;
		$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
		$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
		$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
		$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
		$expires		= $project->expires;
		$projecturl 	= JURI::root().'index.php?option=com_jblance&view=project&layout=detailproject&id='.$project->id;
		$isPvtInvite	= $project->is_private_invite;
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
	
		//get recipient info
		$inviteeInfo = JFactory::getUser($user_id);
		$recipient = $inviteeInfo->email;
	
	
		
		$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlf"    => $projecturl,
                    
					 );
	
		
		
		
		return $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
	
		
	}

	//7.send new bid notification integrated acy mailing(proj-newbid-notify)(TESTED OK)
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PUBLISHERNAME]", "[PROJECTNAME]", "[CATEGORYNAME]", "[CURRENCYSYM]", "[CURRENCYCODE]", "[BUDGETMIN]", "[BUDGETMAX]", "[STARTDATE]", "[EXPIRE]", "[BIDDERNAME]", "[BIDDERUSERNAME]", "[BIDAMOUNT]", "[DELIVERY]");
		$tagsValues = array("$sitename", "$siteURL", "$publishername", "$projectname", "$categoryname", "$currencysym", "$currencycode", "$budgetmin", "$budgetmax", "$startdate", "$expires", "$biddername", "$bidderusername", "$bidamount", "$delivery");
	*/
	function sendNewBidNotification($bid_id, $project_id, $isNewBid){
		
		$db 	= JFactory::getDbo();
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class 
		$newsLetter = "sendNewBidNotification";
		
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		$currencycode = $config->currencyCode;
		$dformat	  = $config->dateFormat;
		
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname	= $project->project_title;
		$categoryname	= JblanceHelper::getCategoryNames($project->id_category);
		$projectType	= $project->project_type;
		$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
		$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
		$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
		$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
		$expires		= $project->expires;
		
		$hrPerInterval = JText::_('COM_JBLANCE_DAYS');
		if($projectType == 'COM_JBLANCE_HOURLY'){
			$commitment = new JRegistry;
			$commitment->loadString($project->commitment);
			$hrPerInterval = JText::_('COM_JBLANCE_HOURS_PER').' '.JText::_($commitment->get('interval'));
		}
		
		//check if the user/bidder has enabled 'new bid' notification. If disabled, return
		$query = "SELECT notifyBidNewAcceptDeny FROM #__jblance_notify WHERE user_id=".$project->publisher_userid;
		$db->setQuery($query);
		$notify = $db->loadResult();
		
		if(!$notify) return;
		
		$buyerinfo = JFactory::getUser($project->publisher_userid);
		$publishername = $buyerinfo->name;
		
		$bid = JTable::getInstance('bid', 'Table');
		$bid->load($bid_id);
		//get  bidder info
		$bidderinfo 	= $jbuser->getUser($bid->user_id);
		$biddername  	= $bidderinfo->name;
		$bidderusername = $bidderinfo->username;
		$bidamount 		= JblanceHelper::formatCurrency($bid->amount, false).$perHr;
		$delivery 		= $bid->delivery.' '.$hrPerInterval;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $buyerinfo->email;
		
		
		
		
		$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                     "categories"     => $categoryname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "projecturlf"    => $projecturl,
                     "publishername"  => $publishername,
                     "biddername"     => $biddername,
                     "bidderusername" => $bidderusername,
                     "bidamount"      => $bidamount,
					 "delivery"       => $delivery
					 );
	
		
		
		
		return $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
		
		
	}

	//8.send out bid notification(Somebody bid lower than your amount)integrated acymailing(proj-lowbid-notify)[TESTED OK]
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[CURRENCYSYM]", "[CURRENCYCODE]", "[BUDGETMIN]", "[BUDGETMAX]", "[STARTDATE]", "[EXPIRE]", "[BIDDERUSERNAME]", "[BIDAMOUNT]", "[DELIVERY]");
			$tagsValues = array("$sitename", "$siteURL", "$projectname", "$currencysym", "$currencycode", "$budgetmin", "$budgetmax", "$startdate", "$expires", "$bidderusername", "$bidamount", "$delivery");
	
	*/
	function sendOutBidNotification($bid_id, $project_id){
		$db 	= JFactory::getDbo();
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
		$newsLetter = "sendOutBidNotification";
		$config 		= JblanceHelper::getConfig();
		$currencysym 	= $config->currencySymbol;
		$currencycode 	= $config->currencyCode;
		$dformat		= $config->dateFormat;
		
		//get sender info
		$senderInfo = self::getSenderInfo();
		$sitename = $senderInfo['sitename'];
		$fromname = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL = JURI::root();
		$recipient = $senderInfo['fromaddress'];
		
		
		$project 		= JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname	= $project->project_title;
		$projectType	= $project->project_type;
		$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
		$budgetmin 		= JblanceHelper::formatCurrency($project->budgetmin, false).$perHr;
		$budgetmax		= JblanceHelper::formatCurrency($project->budgetmax, false).$perHr;
		$startdate		= JHtml::_('date', $project->start_date, $dformat, false);
		$expires		= $project->expires;
		
		$hrPerInterval = JText::_('COM_JBLANCE_DAYS');
		if($projectType == 'COM_JBLANCE_HOURLY'){
			$commitment = new JRegistry;
			$commitment->loadString($project->commitment);
			$hrPerInterval = JText::_('COM_JBLANCE_HOURS_PER').' '.JText::_($commitment->get('interval'));
		}
		
		$bid = JTable::getInstance('bid','Table');
		$bid->load($bid_id);
		
		//get  bidder info
		$bidderinfo 	= $jbuser->getUser($bid->user_id);
		$bidderusername = $bidderinfo->username;
		$bidamount 		= JblanceHelper::formatCurrency($bid->amount, false).$perHr;
		$delivery 		= $bid->delivery.' '.$hrPerInterval;
		
		//search for recipient
		$query = "SELECT email FROM #__jblance_bid b ".
				 "INNER JOIN #__users u ON b.user_id = u.id ".
				 "WHERE b.amount > ".$bid->amount." ".
				 "AND  outbid = 1 AND project_id =".$project_id;
		$db->setQuery($query);
		$bcc = $db->loadColumn();
		
		if(count($bcc) > 0){
			
			
			
				$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "budgetmin"      => $budgetmin, 
                     "budgetmax"      => $budgetmax, 
                     "startdate"      => $startdate,
                     "expire"         => $expires,
                     "bidderusername" => $bidderusername,
                     "bidamount"      => $bidamount,
					 "delivery"       => $delivery
					 );
	
		
		foreach($bcc as $bc)
		{
	   return $this->sendAcyNewsletter($newsLetter,$params,$bc,"apmptag:");
			
        }	
	
			
		}
	}

	//9.send bid won(Bid won by developer)(proj-bidwon-notify)[TESTED OK]
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[BIDDERNAME]", "[BIDDERUSERNAME]", "[CURRENCYSYM]", "[CURRENCYCODE]", "[BIDAMOUNT]", "[DELIVERY]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$biddername", "$bidderusername", "$currencysym", "$currencycode", "$bidamount", "$delivery");
	
	*/
	function sendBidWonNotification($project_id)
	{
		$db 	= JFactory::getDbo();
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
		$newsLetter = "sendBidWonNotification";
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		$currencycode = $config->currencyCode;
		
		$project 		= JTable::getInstance('project', 'Table');
		
		
		$project->load($project_id);
		
		
		$projectname 	= $project->project_title;
		$projectType	= $project->project_type;
		$perHr			= ($projectType == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : '';
		
		$bidderinfo 	= $jbuser->getUser($project->assigned_userid);
		$biddername 	= $bidderinfo->name;
		$bidderusername = $bidderinfo->username;
		
		//check if the user/bidder has enabled 'bid won' notification. If disabled, return
		$query = "SELECT notifyBidWon FROM #__jblance_notify WHERE user_id=".$project->assigned_userid;
		$db->setQuery($query);
		$notify = $db->loadResult();
		
		
		
		if(!$notify) return;
		
		//get bid details
		$query = "SELECT amount,delivery FROM #__jblance_bid WHERE user_id=$project->assigned_userid AND project_id = ".$project_id;
		$db->setQuery($query);
		$bid = $db->loadObject();
		$bidamount = JblanceHelper::formatCurrency($bid->amount, false).$perHr;
		$delivery = $bid->delivery;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $bidderinfo->email;
		
		$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                     "currencysym"    => $currencysym,
                     "currencycode"   => $currencycode, 
                     "biddername"     => $biddername,
                     "bidderusername" => $bidderusername,
                     "bidamount"      => $bidamount,
					 "delivery"       => $delivery
					 );
	
		
		
		
		
		return $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
	}
	
	//10.send when the bidder denied the offer(Acymailing integrated)(proj-denied-notify)[TESTED OK]
	/*$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[PUBLISHERNAME]", "[BIDDERNAME]", "[BIDDERUSERNAME]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$publishername", "$biddername", "$bidderusername");*/
	function sendProjectDeniedNotification($project_id, $bidder_id){
		
		$db = JFactory::getDbo();
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
		$newsLetter="sendProjectDeniedNotification";
		$project 	 = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname = $project->project_title;
		
		$buyerinfo		= $jbuser->getUser($project->publisher_userid);
		$publishername 	= $buyerinfo->name;
		
		
		
		//check if the publisher has enabled 'bid denied' notification. If disabled, return
		$query = "SELECT notifyBidNewAcceptDeny FROM #__jblance_notify WHERE user_id=".$project->publisher_userid;
		
		
		$db->setQuery($query);
		$notify = $db->loadResult();
		
		
		
		if(!$notify) return;
		
		$bidderinfo 	= $jbuser->getUser($bidder_id);
		$biddername 	= $bidderinfo->name;
		$bidderusername = $bidderinfo->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $buyerinfo->email;
		
		$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                     "publishername"  => $publishername,
                     "biddername"     => $biddername,
                     "bidderusername" => $bidderusername
					 );
	
		return $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
		
	}
	
	//11.send project/bid accepted by freelancer,to freelancer to loosers(Integrated acy)(proj-accept-notify,proj-accept-notify-bidder,proj-bid-loosers-notify)[TESTED OK]
	/*$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[PUBLISHERNAME]", "[BIDDERNAME]", "[BIDDERUSERNAME]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$publishername", "$biddername", "$bidderusername");*/
	function sendProjectAcceptedNotification($project_id, $bidder_id){
		
		$db 	= JFactory::getDbo();
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
		$newsLetterC="sendProjectAcceptedNotification";
		$newsLetterD="sendProjectAcceptedNotificationDveloper";
		$newsLetterL="sendProjectAcceptedNotificationloosers";
		
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname = $project->project_title;
		
		$buyerinfo = $jbuser->getUser($project->publisher_userid);
		$publishername = $buyerinfo->name;
		
		//check if the publisher has enabled 'bid accept' notification. If disabled, return
		$query = "SELECT notifyBidNewAcceptDeny FROM #__jblance_notify WHERE user_id=".$project->publisher_userid;
		$db->setQuery($query);
		$notify = $db->loadResult();
		
		if(!$notify) return;
		
		$bidderinfo = $jbuser->getUser($bidder_id);
		$biddername = $bidderinfo->name;
		$bidderusername = $bidderinfo->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $buyerinfo->email;
		
	
		
		$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                     "publishername"  => $publishername,
                     "biddername"     => $biddername,
                     "bidderusername" => $bidderusername
					 );
		
		// Send email to buyer
		$this->sendAcyNewsletter($newsLetterC,$params,$recipient,"apmptag:");
		
		// Send email to freelancer
		$this->sendAcyNewsletter($newsLetterD,$params,$bidderinfo->email,"apmptag:");
		
		// send email to other bidders who has lost it
		
		$params  = array(
				     "sitename"       => $sitename,
				     "siteurl"        => $siteURL,
				     "projectname"    => $projectname,
                      );
		
		$query = "SELECT email FROM #__jblance_bid b
				  LEFT JOIN #__users u ON u.id=b.user_id
				  WHERE project_id=".$db->quote($project_id)." AND user_id NOT IN ($project->assigned_userid)";//echo $query;exit;
		$db->setQuery($query);
		$bccLosers = $db->loadColumn();
		
		// Send email to loosers
		if(count($bccLosers) > 0){
			foreach($bccLosers as $looser)
			{
			$this->sendAcyNewsletter($newsLetterL,$params,$looser,"apmptag:");
			}
		}
	}
	
	function buildCustomFieldTags($message, $bidder_id = 0, $publisher_id = 0, $project_id = 0){
		
		//apend custom field
		$fields = self::buildCustomFieldValues();
		$tagKeys = array();
		$tagValues = array();
		foreach ($fields as $field){
			if($field->userid > 0 && $field->userid == $bidder_id){
				$tagKeys[] = "[CUSTOM_".$field->fieldid."_BIDDER]";
				$tagValues[] = empty($field->value) ? '-' : nl2br($field->value);
			}
			if($field->userid > 0 && $field->userid == $publisher_id){
				$tagKeys[] = "[CUSTOM_".$field->fieldid."_PUBLISHER]";
				$tagValues[] = empty($field->value) ? '-' : nl2br($field->value);
			}
			if($field->projectid > 0 && $field->projectid == $project_id){
				$tagKeys[] = "[CUSTOM_".$field->fieldid."_PROJECT]";
				$tagValues[] = empty($field->value) ? '-' : nl2br($field->value);
			}
		}
		$message = str_replace($tagKeys, $tagValues, $message);
		return $message;
	}

	//12.send project pending approval to admin(Acy integrated)(proj-pending-approval)[TESTED OK]
	/*
	$tags = array("[SITENAME]", "[ADMINURL]", "[PROJECTNAME]", "[PUBLISHERUSERNAME]");
		$tagsValues = array("$sitename", "$adminURL", "$projectname", "$publisherusername");
	*/
	function sendAdminProjectPendingApproval($project_id){
	
	    $project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		
		
		$projectname = $project->project_title;
		$newsLetter ="sendAdminProjectPendingApproval";
		$buyerinfo = JFactory::getUser($project->publisher_userid);
		
		
		$publisherusername = $buyerinfo->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$adminURL 	 = JURI::base().'administrator';
		
		
		
		$params  = array(
				     "sitename"       => $sitename,
				     "projectname"    => $projectname,
                     "adminurl"       => $adminURL,
					 "publisherusername"=>$publisherusername
					 );
					 
					
		//get all email address eligible to receive system emails
		$rows = self::getSuperAdminEmail();
		
    
		// Send notification to all administrators
		foreach($rows as $row){
		
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
		}
		
	}
	
	//13.send project approved to publisher(Integrated acy)(proj-approved)[TESTED OK]
	/*
	$tags = array("[SITENAME]", "[PROJECTURL]", "[PROJECTNAME]", "[PUBLISHERNAME]");
		$tagsValues = array("$sitename", "$projecturl", "$projectname", "$publishername");
	*/
	function sendPublisherProjectApproved($project_id){
		
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname = $project->project_title;
		$projecturlf = JURI::root().'index.php?option=com_jblance&view=project&layout=detailproject&id='.$project->id;
		
		//get publisher info
		$publisher = JFactory::getUser($project->publisher_userid);
		$publishername = $publisher->name;
		$newsLetter    ="sendPublisherProjectApproved";
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$recipient 	 = $publisher->email;
		
		
		
		$params  = array(
				     "sitename"       => $sitename,
				     "projectname"    => $projectname,
                     "projecturlf"    => $projecturlf,
                     "publishername"  => $publishername
					 );
			 
					 
					 $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
	}
	
	//14.send send project payment complete notification(proj-payment-complete)[TESTED OK]
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[RECIPIENT_USERNAME]", "[MARKEDBY_USERNAME]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$recipientUsername", "$markerUsername");
	*/
	function sendProjectPaymentCompleteNotification($project_id, $marker_id){
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectname = $project->project_title;
		$newsLetter  = "sendProjectPaymentCompleteNotification";
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$siteURL 	 = JURI::root();
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		
		//get marker information
		$marker = JFactory::getUser($marker_id);
		$markerUsername = $marker->username;
		
		//logic to find the recipient
		$publisher_userid = $project->publisher_userid;
		$assigned_userid = $project->assigned_userid;
		if($marker_id == $publisher_userid){
			$recipient = JFactory::getUser($assigned_userid);
			$recipientUsername = $recipient->username;
			$recipientEmail = $recipient->email;
		}
		elseif($marker_id == $assigned_userid){
			$recipient = JFactory::getUser($publisher_userid);
			$recipientUsername = $recipient->username;
			$recipientEmail = $recipient->email;
		}
			
		
		
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "projectname"       => $projectname,
                     "recipientUsername" =>$recipientUsername,
					 "markerUsername"    =>$markerUsername
					 );
		
		// Send email to user
	
		$this->sendAcyNewsletter($newsLetter,$params,$recipientEmail,"apmptag:");
	}
	
	//15.send project progress notification to buyer(Acy integrated)(proj-progress-notify)[TESTED OK]
	/*
	$tags 		= array("[SITENAME]", "[SITEURL]", "[BUYER_USERNAME]", "[PROJECTNAME]", "[PROJECTID]", "[STATUS]", "[PERCENT]");
		$tagsValues = array("$sitename", "$siteURL", "$buyerUsername", "$projectName", "$project_id", "$status", "$percent");
	*/
	function sendProjectProgressNotification($bid_id, $project_id){
		$project = JTable::getInstance('project', 'Table');
		$project->load($project_id);
		$projectName 	 = $project->project_title;
	    $newsLetter ="sendProjectProgressNotification";
		$bid = JTable::getInstance('bid', 'Table');
		$bid->load($bid_id);
		$status = JText::_($bid->p_status);
		$percent = $bid->p_percent.' %';
	
		//get recipient
		$buyer = JFactory::getUser($project->publisher_userid);	//recipient is the buyer
		$buyerUsername = $buyer->username;
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
	
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "projectname"       => $projectName,
                     "buyerUsername"     =>$buyerUsername,
					 "project_id"        =>$project_id,
					 "percent"           =>$percent,
					 "status"            =>$status
					 );
					 
					
		$this->sendAcyNewsletter($newsLetter,$params,$buyer->email,"apmptag:");
	
	}
	
	//send new forum message notification
	function sendForumMessageNotification($post){
		$db 	= JFactory::getDbo();
	
		//get message info from the post variable
		$poster_id 	   	= $post['user_id'];
		$project_id	   	= $post['project_id'];
		$project_title 	= $post['project_title'];
		$message 		= $post['message'];
		$projecturl 	= JURI::root().'index.php?option=com_jblance&view=project&layout=detailproject&id='.$project_id;
		$publisher_userid = $post['publisher_userid'];
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $senderInfo['fromaddress'];
	
		$posterInfo = JFactory::getUser($poster_id);
		$publisherInfo = JFactory::getUser($publisher_userid);
	
		//get the recipient list. Do not send to poster and users not receive notification
		$query = "SELECT DISTINCT u.email FROM #__jblance_forum f ".
				 "INNER JOIN #__users u ON u.id=f.user_id ".
				 "LEFT JOIN #__jblance_notify n ON f.user_id=n.user_id ".
				 "WHERE f.project_id=".$db->quote($project_id)." AND f.user_id !=".$db->quote($poster_id);
		$db->setQuery($query);
		$bcc = $db->loadColumn();
		
		if($publisher_userid != $poster_id)
			$bcc[] = $publisherInfo->email;		// always add project author email but if he is adding message, leave him
		
		$bcc = array_unique($bcc);			// make the array unique
		
		if(count($bcc) > 0){
			$tags = array("[SITENAME]", "[SITEURL]", "[POSTERUSERNAME]", "[PROJECTNAME]", "[PROJECTURL]", "[FORUMMESSAGE]");
			$tagsValues = array("$sitename", "$siteURL", "$posterInfo->username", "$project_title", "$projecturl", "$message");
		
			//get the email template
			$template = $this->getTemplate('proj-newforum-notify');
		
			//get subject
			$subject = $template->subject;
			$subject = str_replace($tags, $tagsValues, $subject);
			$subject = html_entity_decode($subject, ENT_QUOTES);
		
			//get message body
			$message = $template->body;
			$message = str_replace($tags, $tagsValues, $message);
			$message = html_entity_decode($message, ENT_QUOTES);
		
			// Send email to user
			JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1, null, $bcc);
		}
	}
	
	//send service order notification to seller
	function sendServiceOrderNotification($order_id, $service_id){
		
		$service = JTable::getInstance('service', 'Table');
		$service->load($service_id);
		$serviceName 	 = $service->service_title;
		$servicePrice 	 = JblanceHelper::formatCurrency($service->price, true, true);
		$serviceDuration = $service->duration.' '.JText::_('COM_JBLANCE_DAYS');
		$serviceUrl 	 = JURI::root().'index.php?option=com_jblance&view=service&layout=viewservice&id='.$service_id;
		
		$order = JTable::getInstance('serviceorder', 'Table');
		$order->load($order_id);
		$totalPrice    = JblanceHelper::formatCurrency($order->price, true, true);
		$totalDuration = $order->duration.' '.JText::_('COM_JBLANCE_DAYS');
		
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		$currencycode = $config->currencyCode;
		
		//get recipient
		$seller = JFactory::getUser($service->user_id);	//recipient is the freelancer or seller
		$sellerUsername = $seller->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		
		$tags 		= array("[SITENAME]", "[SITEURL]", "[SELLER_USERNAME]", "[SERVICENAME]", "[SERVICEPRICE]", "[SERVICEDURATION]", "[TOTALPRICE]", "[TOTALDURATION]", "[SERVICEURL]");
		$tagsValues = array("$sitename", "$siteURL", "$sellerUsername", "$serviceName", "$servicePrice", "$serviceDuration", "$totalPrice", "$totalDuration", "$serviceUrl");
		
		//get the email template
		$template = $this->getTemplate('svc-neworder-notify');
		
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
		
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		// replace custom field tags
		//$message = self::buildCustomFieldTags($message, $bid->user_id, $project->publisher_userid, $project_id);
		
		// Send email to user
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $seller->email, $subject, $message, 1);
	}
	
	//send service progress notification to buyer
	function sendServiceProgressNotification($order_id, $service_id){
		$service = JTable::getInstance('service', 'Table');
		$service->load($service_id);
		$serviceName 	 = $service->service_title;
		
		$order = JTable::getInstance('serviceorder', 'Table');
		$order->load($order_id);
		$status = JText::_($order->p_status);
		$percent = $order->p_percent.' %';
		
		//get recipient
		$buyer = JFactory::getUser($order->user_id);	//recipient is the freelancer or seller
		$buyerUsername = $buyer->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		
		$tags 		= array("[SITENAME]", "[SITEURL]", "[BUYER_USERNAME]", "[SERVICENAME]", "[ORDERID]", "[STATUS]", "[PERCENT]");
		$tagsValues = array("$sitename", "$siteURL", "$buyerUsername", "$serviceName", "$order_id", "$status", "$percent");
		
		//get the email template
		$template = $this->getTemplate('svc-progress-notify');
		
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
		
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		// replace custom field tags
		//$message = self::buildCustomFieldTags($message, $bid->user_id, $project->publisher_userid, $project_id);
		
		// Send email to user
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $buyer->email, $subject, $message, 1);
	}
	
	//send service pending approval to admin
	function sendAdminServicePendingApproval($service_id){
	
		$service = JTable::getInstance('service', 'Table');
		$service->load($service_id);
		$serviceName = $service->service_title;
	
		$sellerInfo = JFactory::getUser($service->user_id);
		$sellerUsername = $sellerInfo->username;
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$adminURL 	 = JURI::base().'administrator';
	
		$tags = array("[SITENAME]", "[ADMINURL]", "[SERVICENAME]", "[SELLER_USERNAME]");
		$tagsValues = array("$sitename", "$adminURL", "$serviceName", "$sellerUsername");
	
		//get the email template
		$template = $this->getTemplate('svc-pending-approval');
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		//get all email address eligible to receive system emails
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			JFactory::getMailer()->sendMail($fromaddress, $fromname, $row->email, $subject, $message, 1);
		}
	}

	//send seller service approval status
	function sendSellerServiceApprovalStatus($service_id){
	
		$service = JTable::getInstance('service', 'Table');
		$service->load($service_id);
		$serviceName = $service->service_title;
		$serviceUrl = JURI::root().'index.php?option=com_jblance&view=service&layout=viewservice&id='.$service->id;
	
		//get seller info
		$sellerInfo = JFactory::getUser($service->user_id);
		$sellerUsername = $sellerInfo->username;
		
		//get approval status and message
		$approved = $service->approved;
		if($approved){
			$approvalStatus = JText::_('COM_JBLANCE_APPROVED');
			$approvalMessage = JText::_('COM_JBLANCE_YOUR_SERVICE_IS_APPROVED');
			
		}
		else {
			$approvalStatus = JText::_('COM_JBLANCE_NEEDS_REVISION');
			$approvalMessage = $service->disapprove_reason;
		}
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$recipient 	 = $sellerInfo->email;
	
		$tags = array("[SITENAME]", "[SERVICEURL]", "[SERVICENAME]", "[SELLER_USERNAME]", "[APPROVAL_STATUS]", "[APPROVAL_MESSAGE]");
		$tagsValues = array("$sitename", "$serviceUrl", "$serviceName", "$sellerUsername", "$approvalStatus", "$approvalMessage");
	
		//get the email template
		$template = $this->getTemplate('svc-approval_status');
	
		//get subject
		$subject = $template->subject;
		$subject = str_replace($tags, $tagsValues, $subject);
		$subject = html_entity_decode($subject, ENT_QUOTES);
	
		//get message body
		$message = $template->body;
		$message = str_replace($tags, $tagsValues, $message);
		$message = html_entity_decode($message, ENT_QUOTES);
	
		// Send email to user
		JFactory::getMailer()->sendMail($fromaddress, $fromname, $recipient, $subject, $message, 1);
	}
	
	//16.send withdraw request to admin(fin-witdrw-request)[TESTED]
	/*
	$tags = array("[NAME]", "[USERNAME]", "[INVOICENO]", "[ADMINURL]", "[GATEWAY]");
		$tagsValues = array("$name", "$username", "$invoiceNo", "$adminURL", "$gateway");
	*/
	function sendWithdrawFundRequest($withdraw_id){
		
		$withdraw = JTable::getInstance('withdraw', 'Table');
		$withdraw->load($withdraw_id);
		
		$invoiceNo = $withdraw->invoiceNo;
		$user_id   = $withdraw->user_id;
		$gateway   = JblanceHelper::getGwayName($withdraw->gateway);
		$newsLetter = "sendWithdrawFundRequest";
		//get requestor info
		$requestor 	 = JFactory::getUser($user_id);
		$name		 = $fromname = $requestor->name;
		$username 	 = $requestor->username;
		$fromaddress = $requestor->email;
		$adminURL 	 = JURI::base().'administrator';
		
		
		
		$params  = array(
				     "username"          => $username, 
				     "adminurl"          => $adminURL,
			         "name"              => $name,
					 "invoiceNo"         => $invoiceNo,
					 "gateway"           => $gateway
					 );
		
		
		
		//get all super administrator
		$rows = self::getSuperAdminEmail();
		
		// Send notification to all administrators
		foreach($rows as $row){
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
		}
	}
	
	//17.send withdraw request approved to user(fin-witdrw-approved)[TESTED OK]
	/*
	$tags = array("[NAME]", "[CURRENCYSYM]", "[AMOUNT]", "[INVOICENO]", "[SITENAME]", "[SITEURL]");
		$tagsValues = array("$name", "$currencysym", "$amount", "$invoiceNo", "$sitename", "$siteURL");
	*/
	function sendWithdrawRequestApproved($withdraw_id){
		
		$withdraw = JTable::getInstance('withdraw', 'Table');
		$withdraw->load($withdraw_id);
		
		$invoiceNo 	= $withdraw->invoiceNo;
		$user_id 	= $withdraw->user_id;
		$amount		= JblanceHelper::formatCurrency($withdraw->amount, false);
		$newsLetter = "sendWithdrawRequestApproved";
		//get requestor info
		$requestor = JFactory::getUser($user_id);
		$name = $requestor->name;
		
		
	
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		$currencycode = $config->currencyCode;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $requestor->email;
		
		
		
		
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "currencysym"       => $currencysym,
                     "name"              => $name,
					 "invoiceNo"         => $invoiceNo,
					 "amount"            => $amount
					 );
					 
					 
		// Send email to user
		
		$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
	}

	//18.send escrow payment received to the bid winner(fin-escrow-released)[TESTED OK]
	/*$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[SENDERUSERNAME]", "[RECEIVEUSERNAME]", "[RELEASEDATE]", "[CURRENCYSYM]", "[AMOUNT]", "[NOTE]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$senderUsername", "$receiveUsername", "$releaseDate", "$currencysym", "$amount", "$note");*/
	function sendEscrowPaymentReleased($escrow_id){
		$escrow	= JTable::getInstance('escrow', 'Table');
		$escrow->load($escrow_id);
		$newsLetter ="sendEscrowPaymentReleased";
		$config 	 = JblanceHelper::getConfig();
		$currencysym = $config->currencySymbol;
		$dformat	 = $config->dateFormat;
		
		$receiver = JFactory::getUser($escrow->to_id);
		$sender = JFactory::getUser($escrow->from_id);
		
		$type = $escrow->type;
		if($type == 'COM_JBLANCE_PROJECT'){
			$project	= JTable::getInstance('project', 'Table');
			$project->load($escrow->project_id);
			$projectname = $project->project_title;
		}
		elseif($type == 'COM_JBLANCE_SERVICE'){
			//$svcHelper 	= JblanceHelper::get('helper.service');		// create an instance of the class ServiceHelper
			//$service = $svcHelper->getServiceDetailsFromOrder($escrow->project_id);
			$service	= JTable::getInstance('service', 'Table');
			$service->load($escrow->project_id);
			$projectname = $service->service_title;
		}
		elseif($type == 'COM_JBLANCE_OTHER'){
			$projectname = JText::_('COM_JBLANCE_NA');
		}
		
		$senderUsername  = $sender->username;
		$receiveUsername = $receiver->username;
		$releaseDate	 = JHtml::_('date', $escrow->date_release, $dformat, false);
		$amount 		 = JblanceHelper::formatCurrency($escrow->amount, false);;
		$note 			 = (!empty($escrow->note)) ? $escrow->note : '-';
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient[] = $receiver->email;
		
		/* $recipient[] = $sender->email; 		
		$mailer =& JFactory::getMailer();
		$mailer->addRecipient($recipient); */

		
		
	    $params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "projectname"       => $projectname,
                     "currencysym"       => $currencysym,
                     "amount"            => $amount,
					 "senderUsername"    =>$senderUsername,
					 "receiveUsername"   =>$receiveUsername,
					 "releaseDate"       =>$releaseDate,
					 "note"              =>$note
					 );
					 
					
		
		// Send email to user
	   
		$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
		
		
		
	}
	
	//19.send escrow pymt accepted to sender(fin-escrow-accepted)[TESTED OK]
	/*$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[SENDERUSERNAME]", "[RECEIVEUSERNAME]", "[RELEASEDATE]", "[CURRENCYSYM]", "[AMOUNT]", "[NOTE]");
		$tagsValues = array("$sitename", "$siteURL", "$projectname", "$senderUsername", "$receiveUsername", "$releaseDate", "$currencysym", "$amount", "$note");*/
	function sendEscrowPaymentAccepted($escrow_id){
		
		$db 	= JFactory::getDbo();
		
		$escrow	= JTable::getInstance('escrow', 'Table');
		$escrow->load($escrow_id);
		
		$newsLetter="sendEscrowPaymentAccepted";
		$config 	 = JblanceHelper::getConfig();
		$currencysym = $config->currencySymbol;
		$dformat	 = $config->dateFormat;
		
		$receiver = JFactory::getUser($escrow->to_id);
		$sender   = JFactory::getUser($escrow->from_id);
		
		//check if the user/bidder has enabled 'new bid' notification. If disabled, return
		$query = "SELECT notifyPaymentTransaction FROM #__jblance_notify WHERE user_id=".$escrow->from_id;
		$db->setQuery($query);
		$notify = $db->loadResult();
		
		if(!$notify) return;
		
		
		$type = $escrow->type;
		if($type == 'COM_JBLANCE_PROJECT'){
			$project	= JTable::getInstance('project', 'Table');
			$project->load($escrow->project_id);
			$projectname = $project->project_title;
		}
		elseif($type == 'COM_JBLANCE_SERVICE'){
			//$svcHelper 	= JblanceHelper::get('helper.service');		// create an instance of the class ServiceHelper
			//$service = $svcHelper->getServiceDetailsFromOrder($escrow->project_id);
			$service	= JTable::getInstance('service', 'Table');
			$service->load($escrow->project_id);
			$projectname = $service->service_title;
		}
		elseif($type == 'COM_JBLANCE_OTHER'){
			$projectname = JText::_('COM_JBLANCE_NA');
		}
		
		$senderUsername  = $sender->username;
		$receiveUsername = $receiver->username;
		$releaseDate	 = JHtml::_('date', $escrow->date_release, $dformat, false);
		$amount 		 = JblanceHelper::formatCurrency($escrow->amount, false);
		$note 			 = (!empty($escrow->note)) ? $escrow->note : '-';
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $sender->email;
		
		
		
		
		
		 $params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "projectname"       => $projectname,
                     "currencysym"       => $currencysym,
                     "amount"            => $amount,
					 "senderUsername"    =>$senderUsername,
					 "receiveUsername"   =>$receiveUsername,
					 "releaseDate"       =>$releaseDate,
					 "note"              =>$note
					 );
		
		// Send email to user
	
		$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
		
		
		
	}
	
	//20.send deposit fund alert to admin(fin-deposit-alert)[TESTED OK]
	/*
	$tags = array("[NAME]", "[USERNAME]", "[INVOICENO]", "[ADMINURL]", "[GATEWAY]", "[STATUS]", "[AMOUNT]", "[CURRENCYSYM]");
		$tagsValues = array("$name", "$username", "$invoiceNo", "$adminURL", "$gateway", "$status", $amount, $currencysym);
	*/
	function sendAdminDepositFund($deposit_id){
	
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		
		$deposit = JTable::getInstance('deposit', 'Table');
		$deposit->load($deposit_id);
	
		$invoiceNo = $deposit->invoiceNo;
		$user_id   = $deposit->user_id;
		$gateway   = JblanceHelper::getGwayName($deposit->gateway);
		$amount	   = JblanceHelper::formatCurrency($deposit->amount, false);
	    $newsLetter = "sendAdminDepositFund";
		//get depositor info
		$depositor 	 = JFactory::getUser($user_id);
		$name		 = $fromname = $depositor->name;
		$username 	 = $depositor->username;
		$fromaddress = $depositor->email;
		$adminURL 	 = JURI::base().'administrator';
		
		if($deposit->approved)
			$status = JText::_('COM_JBLANCE_APPROVED');
		else
			$status = JText::_('COM_JBLANCE_PENDING');
	
		
	
		 $params  = array(
				     "username"          => $username, 
				     "currencysym"       => $currencysym,
                     "adminurl"          => $adminurl,
				     "status"            => $status,
					 "name"              => $name,
					 "invoiceNo"         => $invoiceNo,
					 "gateway"           => $gateway,
					 "amount"            => $amount
					 );
		
		// Send email to user
		
		
	
		//get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
		}
	}

	//21.send approved deposit fund to depositor(fin-deposit-approved)[TESTED OK]
	/*
	$tags = array("[NAME]", "[CURRENCYSYM]", "[AMOUNT]", "[INVOICENO]", "[SITENAME]", "[SITEURL]");
		$tagsValues = array("$name", "$currencysym", "$amount", "$invoiceNo", "$sitename", "$siteURL");
	*/
	function sendUserDepositFundApproved($deposit_id){
		$config 	  = JblanceHelper::getConfig();
		$currencysym  = $config->currencySymbol;
		
		$deposit = JTable::getInstance('deposit', 'Table');
		$deposit->load($deposit_id);
		
		$invoiceNo = $deposit->invoiceNo;
		$user_id   = $deposit->user_id;
		$gateway   = JblanceHelper::getGwayName($deposit->gateway);
		$amount	   = JblanceHelper::formatCurrency($deposit->amount, false);
		$newsLetter = "sendUserDepositFundApproved";
		//get depositor info
		$depositor 	 = JFactory::getUser($user_id);
		$name		 = $depositor->name;
		$username 	 = $depositor->username;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$recipient 	 = $depositor->email;
		
		
		
		
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "currencysym"       => $currencysym,
                     "name"              => $name,
					 "invoiceNo"         => $invoiceNo,
				     "amount"            => $amount
					 );
		
		
		
		
	$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmptag:");
		
		
	}

	//22send PM notification to the recipient(pm-new-notify)
	/*$tags = array("[RECIPIENT_USERNAME]", "[SENDER_USERNAME]", "[MSG_SUBJECT]", "[MSG_BODY]", "[SITENAME]", "[SITEURL]");
		$tagsValues = array("$msgRecipientInfo->username", "$msgSenderInfo->username", "$msg_subject", "$msg_body", "$sitename", "$siteURL");*/
	function sendMessageNotification($msg_id){
		$db 	= JFactory::getDbo();
		
		$message = JTable::getInstance('message', 'Table');
		$message->load($msg_id);
		$newsLetter ="sendMessageNotification";
		//get message info
		$sender_id 	  = $message->idFrom;
		$recipient_id = $message->idTo;
		$msg_subject  = $message->subject;
		$msg_body 	  = $message->message;
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		
		$msgSenderInfo = JFactory::getUser($sender_id);
		$msgRecipientInfo = JFactory::getUser($recipient_id);
		
		//check if the recipient has enabled 'new message' notification. If disabled, return
		$query = "SELECT notifyNewMessage FROM #__jblance_notify WHERE user_id=".$recipient_id;
		$db->setQuery($query);
		$notify = $db->loadResult();
		
		if(!$notify) return;
		
		
		
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
			         "msgRecipientName"  => $msgRecipientInfo->username,
					 "msgSenderName"     => $msgSenderInfo->username,
					 "msg_subject"       => $msg_subject,
					 "msg_body"          => $msg_body
					 );
		
	
		$this->sendAcyNewsletter($newsLetter,$params,$msgRecipientInfo->email,"apmptag:");
		
	}
	
	//23send message pending approval to admin(pm-pending-approval)[TESTED OK]
	/*
	$tags = array("[RECIPIENT_USERNAME]", "[SENDER_USERNAME]", "[MSG_SUBJECT]", "[MSG_BODY]", "[SITENAME]", "[SITEURL]", "[ADMINURL]");
		$tagsValues = array("$msgRecipientInfo->username", "$msgSenderInfo->username", "$msg_subject", "$msg_body", "$sitename", "$siteURL", "$adminURL");
	*/
	function sendAdminMessagePendingApproval($msg_id){
	
		$message = JTable::getInstance('message', 'Table');
		$message->load($msg_id);
		
		//get message info
		$sender_id 	  = $message->idFrom;
		$recipient_id = $message->idTo;
		$msg_subject  = $message->subject;
		$msg_body 	  = $message->message;
		$newsLetter   = "sendAdminMessagePendingApproval";
		$msgSenderInfo = JFactory::getUser($sender_id);
		$msgRecipientInfo = JFactory::getUser($recipient_id);
	
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL 	 = JURI::root();
		$adminURL 	 = JURI::base().'administrator';
		
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "adminurl"          => $adminURL,
					 "msgRecipientInfo"  => $msgRecipientInfo,
					 "msgSenderInfo"     => $msgSenderInfo,
					 "msg_subject"       => $msg_subject,
					 "msg_body"          => $msg_body
					 );
	
		//get all email address eligible to receive system emails
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
		}
	}
	//24(report-default-action)[TESTED OK]
	/*
	$tags = array("[TYPE]", "[COUNT]", "[ACTION]", "[ITEMLINK]", "[SITENAME]");
		$tagsValues = array("$type", "$count", "$action", "$itemlink", "$sitename");
	*/
	function sendReportingDefaultAction($report, $result){
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$newsLetter ="sendReportingDefaultAction";
		$type = $result['type'];
		$count = $report->getReportersCount();
		$action = $result['action'];
		$itemlink = $report->link;
		
	    $params  = array(
				     "sitename"          => $sitename,
			         "type"              => $type,
					 "count"             => $count,
					 "action"            => $action,
					 "itemlink"          => $itemlink,
					 );
	
		//get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmptag:");
		}
	}
	
	//25subscription expiry email(proj-expiry-reminder)
	//25subscription expiry email(proj-expiry-reminder)
	/*
	$tags = array("[SITENAME]", "[SITEURL]", "[PROJECTNAME]", "[PUBLISHERNAME]", "[PROJECTEXPIRYDATE]");
			$tagsValues = array("$sitename", "$siteURL", "$row->project_title", "$uname", "$expireDate");
	*/
	function sendExpiryEmail($email, $uname, $type, $subscr_project_id){
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL	 = JURI::base();
		$newsLetter ="sendExpiryEmail";
		$config 	  = JblanceHelper::getConfig();
		$dformat	  = $config->dateFormat;
		
		if($type == 'project'){
			$row	= JTable::getInstance('project', 'Table');
			$row->load($subscr_project_id);
			$expiredate = JFactory::getDate($row->start_date);
			$expiredate->modify("+$row->expires days");
			$expireDate = JHtml::_('date', $expiredate, $dformat, false);
		
			
			
			$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "projectname"       => $row->project_title,
                     "publishername"     => $uname,
                     "expireDate"        => $expireDate
					 );
			
			$this->sendAcyNewsletter($newsLetter,$params,$email,"apmptag:");
			
		}
	}
//26(newuser-account-approved-email-validate)
/*
$tags = array("[NAME]", "[EMAIL]", "[USERNAME]","[PASSWORD]", "[SITENAME]", "[SITEURL]");
		$tagsValues = array("$name", "$recipient", "$username","$password", "$sitename", "$siteURL");
*/
	function sendUserAccountApprovedByEmailValidate($userid){
		$user 		= JFactory::getUser($userid);
		$name 		= $user->name;
		$recipient  = $user->email;
		$username 	= $user->username;
		$password 	= JUserHelper::genRandomPassword();
		
		//get sender info
		$senderInfo  = self::getSenderInfo();
		$sitename 	 = $senderInfo['sitename'];
		$fromname 	 = $senderInfo['fromname'];
		$fromaddress = $senderInfo['fromaddress'];
		$siteURL = JURI::root();
		
		
		
		
		$params  = array(
				     "sitename"          => $sitename,
				     "siteurl"           => $siteURL,
				     "projectname"       => $row->project_title,
                     "publishername"     => $uname,
                     "name"              => $name,
					 "expireDate"        => $expireDate
					 
					 );
			
			$this->sendAcyNewsletter($newsLetter,$params,$email,"apmptag:");
		
		
		   
	}
	//APPMEADOWS SUBSCRIPTIONS
	
	//27. user subscribed a plan
	
	function userSubscribedNewPlan($data)
	{
	$newsLetter="userSubscribedNewPlan";
	$user = JFactory::getUser($data->user_id);
	$recipient= $user->email;
	$planJb = $this->getPlanName($data->planId);
	$params = array(
	               "username"          => $user->name,
	               "subscriptionId"    => $data->subscriptionId,
	               "subscriptionName"  => $planJb->name,
	               "gateway"           => "Braintree",
	               "gatewayImage"      =>JUri::root()."images/braintree.png",
	               "amount"            => $data->price,
                   "transid"           => $data->trans_id,
	               "date_buy"          =>  $data->date_buy,
	               "date_approval"     => $data->date_approval,
	               "date_expire"       => $data->date_expire,
	               "billing_day"       => $data->billing_day,
	               "createdAt"         => $data->createdAt,
	               "updatedAt"         => $data->updatedAt,
	               "currentBillingCycle"  => $data->currentBillingCycle,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	                //card details
	               "token"                => $data->token,
	               "bin"                  => $data->bin,
	               "last4"                => $data->last4,
	               "cardType"             => $data->cardType,
                   "expirationDate"       => $data->expirationDate,
                   "customerLocation"     => $data->customerLocation,
                   "cardholderName"       => $data->cardholderName,
	               "imageUrl"             => $data->imageUrl, 
	               "prepaid"              => $data->prepaid, 
	               "healthcare"           => $data->healthcare,
	               "debit"                => $data->debit,
	               "durbinRegulated"      => $data->durbinRegulated, 
	               "commercial"           => $data->commercial,
	               "payroll"              => $data->payroll, 
	               "issuingBank"          => $data->issuingBank,
	               "countryOfIssuance"    => $data->countryOfIssuance, 
	               "productId"               => $data->productId, 
	               "uniqueNumberIdentifier"  => $data->uniqueNumberIdentifier, 
	               "maskedNumber"            => $data->maskedNumber
                    );				   
	
	
	
	
	
	
	$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmstag:");
	}
	
	//28. userSubscribedNewPlanAdmin
    function userSubscribedNewPlanAdmin($data)
	{
		$newsLetter="userSubscribedNewPlanAdmin";
	$user = JFactory::getUser($data->user_id);
	$recipient= $user->email;
	$planJb = $this->getPlanName($data->planId);
	$params = array(
	               "username"          => $user->name,
	               "subscriptionId"    => $data->subscriptionId,
	               "subscriptionName"  => $planJb->name,
	               "gateway"           => "Braintree",
	               "gatewayImage"      => JUri::root()."images/braintree.png",
	               "amount"            => $data->price,
                   "transid"           => $data->trans_id,
	               "date_buy"          => $data->date_buy,
	               "date_approval"     => $data->date_approval,
	               "date_expire"       => $data->date_expire,
	               "billing_day"       => $data->billing_day,
	               "createdAt"         => $data->createdAt,
	               "updatedAt"         => $data->updatedAt,
	               "currentBillingCycle"  => $data->currentBillingCycle,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	                //card details
	               "token"                => $data->token,
	               "bin"                  => $data->bin,
	               "last4"                => $data->last4,
	               "cardType"             => $data->cardType,
                   "expirationDate"       => $data->expirationDate,
                   "customerLocation"     => $data->customerLocation,
                   "cardholderName"       => $data->cardholderName,
	               "imageUrl"             => $data->imageUrl, 
	               "prepaid"              => $data->prepaid, 
	               "healthcare"           => $data->healthcare,
	               "debit"                => $data->debit,
	               "durbinRegulated"      => $data->durbinRegulated, 
	               "commercial"           => $data->commercial,
	               "payroll"              => $data->payroll, 
	               "issuingBank"          => $data->issuingBank,
	               "countryOfIssuance"    => $data->countryOfIssuance, 
	               "productId"               => $data->productId, 
	               "uniqueNumberIdentifier"  => $data->uniqueNumberIdentifier, 
	               "maskedNumber"            => $data->maskedNumber
                    );	


	
	
	//get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmstag:");
		}
	

	}
	
	//29. userUpdatedPlan
	function userUpdatedPlan($data)
	{
		$newsLetter="userUpdatedPlan";
	$user = JFactory::getUser($data->user_id);
	$recipient= $user->email;
	$planJb = $this->getPlanName($data->planId);
	$params = array(
	               "username"          => $user->name,
	               "subscriptionId"    => $data->subscriptionId,
	               "subscriptionName"  => $planJb->name,
	               "gateway"           => "Braintree",
	               "gatewayImage"      => JUri::root()."images/braintree.png",
	               "amount"            => $data->price,
                   "transid"           => $data->trans_id,
	               "date_buy"          => $data->date_buy,
	               "date_approval"     => $data->date_approval,
	               "date_expire"       => $data->date_expire,
	               "billing_day"       => $data->billing_day,
	               "createdAt"         => $data->createdAt,
	               "updatedAt"         => $data->updatedAt,
	               "currentBillingCycle"  => $data->currentBillingCycle,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	                //card details
	               "token"                => $data->token,
	               "bin"                  => $data->bin,
	               "last4"                => $data->last4,
	               "cardType"             => $data->cardType,
                   "expirationDate"       => $data->expirationDate,
                   "customerLocation"     => $data->customerLocation,
                   "cardholderName"       => $data->cardholderName,
	               "imageUrl"             => $data->imageUrl, 
	               "prepaid"              => $data->prepaid, 
	               "healthcare"           => $data->healthcare,
	               "debit"                => $data->debit,
	               "durbinRegulated"      => $data->durbinRegulated, 
	               "commercial"           => $data->commercial,
	               "payroll"              => $data->payroll, 
	               "issuingBank"          => $data->issuingBank,
	               "countryOfIssuance"    => $data->countryOfIssuance, 
	               "productId"               => $data->productId, 
	               "uniqueNumberIdentifier"  => $data->uniqueNumberIdentifier, 
	               "maskedNumber"            => $data->maskedNumber
                    );				   
	
	
	
	
	
	
	$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmstag:");
	}
	
	//30.userUpdatedPlanAdmin
	function userUpdatedPlanAdmin($data)
	{
	$newsLetter="userUpdatedPlanAdmin";
	
	$user = JFactory::getUser($data->user_id);
	
	$recipient= $user->email;
	
	$planJb = $this->getPlanName($data->planId);
	
	$params = array(
	               "username"          => $user->name,
	               "subscriptionId"    => $data->subscriptionId,
	               "subscriptionName"  => $planJb->name,
	               "gateway"           => "Braintree",
	               "gatewayImage"      => "<img src='".JUri::root()."images/braintree.png'/>",
	               "amount"            => $data->price,
                   "transid"           => $data->trans_id,
	               "date_buy"          => $data->date_buy,
	               "date_approval"     => $data->date_approval,
	               "date_expire"       => $data->date_expire,
	               "billing_day"       => $data->billing_day,
	               "createdAt"         => $data->createdAt,
	               "updatedAt"         => $data->updatedAt,
	               "currentBillingCycle"  => $data->currentBillingCycle,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	                //card details
	               "token"                => $data->token,
	               "bin"                  => $data->bin,
	               "last4"                => $data->last4,
	               "cardType"             => $data->cardType,
                   "expirationDate"       => $data->expirationDate,
                   "customerLocation"     => $data->customerLocation,
                   "cardholderName"       => $data->cardholderName,
	               "cardImage"             => "<img src='".$data->imageUrl."'/>", 
	               "prepaid"              => $data->prepaid, 
	               "healthcare"           => $data->healthcare,
	               "debit"                => $data->debit,
	               "durbinRegulated"      => $data->durbinRegulated, 
	               "commercial"           => $data->commercial,
	               "payroll"              => $data->payroll, 
	               "issuingBank"          => $data->issuingBank,
	               "countryOfIssuance"    => $data->countryOfIssuance, 
	               "productId"               => $data->productId, 
	               "uniqueNumberIdentifier"  => $data->uniqueNumberIdentifier, 
	               "maskedNumber"            => $data->maskedNumber
                    );				   
	
	
	//get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmstag:");
			
		}
		
		
	}
	
	
	//31.Canceled plan user
	function userCanceledPlan($data)
	{
	$newsLetter = "userCanceledPlan";
	
	$user = JFactory::getUser($data->user_id);
	
	$recipient= $user->email;
	
	$planJb = $this->getPlanName($data->planId);
	
	$params = array(
	               "username"             => $user->name,
	               "subscriptionId"       => $data->subscriptionId,
	               "subscriptionName"     => $planJb->name,
	               "gateway"              => "Braintree",
	               "gatewayImage"         => "<img src='".JUri::root()."images/braintree.png'/>",
	               "amount"               => $data->price,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	               "canceledOn"           => date("F j, Y, g:i a")
                    );				   
	
	
	
			
			$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmstag:");
			
	
		
	
	}
	//32. Canceled plan user admin
	function userCanceledPlanA($data)
	{
	$newsLetter="userCanceledPlanA";
	
	$planJb = $this->getPlanName($data->planId);
	
	$params = array(
	               "username"             => $user->name,
	               "subscriptionId"       => $data->subscriptionId,
	               "subscriptionName"     => $planJb->name,
	               "gateway"              => "Braintree",
	               "gatewayImage"         => "<img src='".JUri::root()."images/braintree.png'/>",
	               "amount"               => $data->price,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	               "canceledOn"           => date("F j, Y, g:i a")
                    );		

        //get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmstag:");
			
		}
					
	
	
	}
	
	
	//33.plan overdue
	function userPlanOverdue($data)
	{
	$newsLetter="userPlanOverdue";
	
    $user = JFactory::getUser($data->user_id);
	
	$recipient= $user->email;
	
	$planJb = $this->getPlanName($data->planId);
	
	$params = array(
	               "username"             => $user->name,
	               "subscriptionId"       => $data->subscriptionId,
	               "subscriptionName"     => $planJb->name,
	               "gateway"              => "Braintree",
	               "gatewayImage"         => "<img src='".JUri::root()."images/braintree.png'/>",
	               "amount"               => $data->price,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	               "overdueOn"           => date("F j, Y, g:i a")
                    );				   
	
	
	
			
			$this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmstag:");
	
	}
	//34. Plan overdue admin
	function userPlanOverdueA($data)
	{
	$newsLetter="userPlanOverdueA";
	
	$planJb = $this->getPlanName($data->planId);
	
	$params = array(
	               "username"             => $user->name,
	               "subscriptionId"       => $data->subscriptionId,
	               "subscriptionName"     => $planJb->name,
	               "gateway"              => "Braintree",
	               "gatewayImage"         => "<img src='".JUri::root()."images/braintree.png'/>",
	               "amount"               => $data->price,
                   "status"               => $data->status,
	               "planId"               => $data->planId,
	               "overdueOn"           => date("F j, Y, g:i a")
                    );		

        //get all super administrator
		$rows = self::getSuperAdminEmail();
	
		// Send notification to all administrators
		foreach($rows as $row){
			
			$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmstag:");
			
		}
	
	}
	
	//35 add new card
	function addedNewCard($data,$user)
	{
	$newsLetter = "addedNewCard";
	$recipient  = $user->email;
	 //card details
	   $params   = array(
            	   "username"                => $user->name,
	               "token"                   => $data->token,
	               "bin"                     => $data->bin,
	               "last4"                   => $data->last4,
	               "cardType"                => $data->cardType,
                   "expirationDate"          => $data->expirationDate,
                   "customerLocation"        => $data->customerLocation,
                   "cardholderName"          => $data->cardholderName,
	               "cardImage"               => "<img src='".$data->imageUrl."'/>", 
	               "prepaid"                 => $data->prepaid, 
	               "healthcare"              => $data->healthcare,
	               "debit"                   => $data->debit,
	               "durbinRegulated"         => $data->durbinRegulated, 
	               "commercial"              => $data->commercial,
	               "payroll"                 => $data->payroll, 
	               "issuingBank"             => $data->issuingBank,
	               "countryOfIssuance"       => $data->countryOfIssuance, 
	               "productId"               => $data->productId, 
	               "uniqueNumberIdentifier"  => $data->uniqueNumberIdentifier, 
	               "maskedNumber"            => $data->maskedNumber
				   );
		 
				   $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmstag:");
	
	}
	
	//36.user added funds 
	
	function userAddedFunds($data)
	{
	$newsLetter  =  "userAddedFunds";
	$user        =  JFactory::getUser($data->customerId);
	$recipient   =  $user->eamil;
	$params = array(
	"customerName"                    => $data->customerName,
	"customerId"                      => $data->customerId,
	"gateway"                         => "Braintree",
	"gatewayImage"                    => "<img src='".JUri::root()."images/braintree.png'/>", 
	"transaction_id"                  => $data->transaction_id,
    "transaction_status"              => $data->transaction_status,
    "transaction_type"                => $data->transaction_type,
    "transaction_currencyIsoCode"     => $data->transaction_currencyIsoCode,
    "transaction_amount"              => $data->transaction_amount,
    "transaction_created_at"          => $data->transaction_created_at,
	"transaction_updatedAt"           => $data->transaction_updatedAt,
	"card_token"                      => $data->card_token,
    "cardbin"                         => $data->cardbin,
    "cardlast4"                       => $data->cardlast4,
    "cardType"                        => $data->cardType,
    "expirationMonth"                 => $data->expirationMonth,
    "expirationYear"                  => $data->expirationYear,
    "customerLocation"                => $data->customerLocation,
    "cardholderName"                  => $data->cardholderName,
    "cardImage"                       => "<img src='".$data->cardImage."'/>",
    "prepaid"                         => $data->prepaid,
    "healthcare"                      => $data->healthcare,
    "debit"                           => $data->debit,
   "durbinRegulated"                  => $data->durbinRegulated,
    "commercial"                      => $data->commercial,
    "payroll"                         => $data->payroll,
    "issuingBank"                     => $data->issuingBank,
    "countryOfIssuance"               => $data->countryOfIssuance,
    "productId"                       => $data->productId,
    "uniqueNumberIdentifier"          => $data->uniqueNumberIdentifier,
    "venmoSdk"                        => $data->venmoSdk,
    "expirationDate"                  => $data->expirationDate,
    "maskedNumber"                    => $data->maskedNumber
	);
	
	
	
	 $this->sendAcyNewsletter($newsLetter,$params,$recipient,"apmcmtag:");
	}
	
	//37.user added funds administrator
	
	function userAddedFundsA($data)
	{
	$newsLetter  =  "userAddedFundsA";
	$user        =  JFactory::getUser($data->customerId);
	$recipient   =  $user->eamil;
	
	$params = array(
	"customerName"                    => $data->customerName,
	"customerId"                      => $data->customerId,
	"gateway"                         => "Braintree",
	"gatewayImage"                    => "<img src='".JUri::root()."/images/braintree.png'/>", 
	"transaction_id"                  => $data->transaction_id,
    "transaction_status"              => $data->transaction_status,
    "transaction_type"                => $data->transaction_type,
    "transaction_currencyIsoCode"     => $data->transaction_currencyIsoCode,
    "transaction_amount"              => $data->transaction_amount,
    "transaction_created_at"          => $data->transaction_created_at,
	"transaction_updatedAt"           => $data->transaction_updatedAt,
	"card_token"                      => $data->card_token,
    "cardbin"                         => $data->cardbin,
    "cardlast4"                       => $data->cardlast4,
    "cardType"                        => $data->cardType,
    "expirationMonth"                 => $data->expirationMonth,
    "expirationYear"                  => $data->expirationYear,
    "customerLocation"                => $data->customerLocation,
    "cardholderName"                  => $data->cardholderName,
    "cardImage"                       => "<img src='".$data->cardImage."'/>",
    "prepaid"                         => $data->prepaid,
    "healthcare"                      => $data->healthcare,
    "debit"                           => $data->debit,
    "durbinRegulated"                 => $data->durbinRegulated,
    "commercial"                      => $data->commercial,
    "payroll"                         => $data->payroll,
    "issuingBank"                     => $data->issuingBank,
    "countryOfIssuance"               => $data->countryOfIssuance,
    "productId"                       => $data->productId,
    "uniqueNumberIdentifier"          => $data->uniqueNumberIdentifier,
    "venmoSdk"                        => $data->venmoSdk,
    "expirationDate"                  => $data->expirationDate,
    "maskedNumber"                    => $data->maskedNumber
	);
	
	//get all super administrator
	$rows = self::getSuperAdminEmail();
	
	//Send notification to all administrators
	foreach($rows as $row)
	{
	$this->sendAcyNewsletter($newsLetter,$params,$row->email,"apmcmtag:");
	}
	
	}
	
	function getAcyConfig()
	{
	if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php'))
		 {
         echo 'This code can not work without the AcyMailing Component';
         return false;
         }
	
	}
	
	private function sendAcyNewsletter($newslettername,$AcyTags,$email,$prefix)
	{
	jimport('joomla.application.component.helper');
	$params=JComponentHelper::getParams('com_jblance');
	
	$newsletterid = $params->get($newslettername);
	$status = $params->get($newslettername."dis");
	
	
	if($status==1)
	{
	$this->getAcyConfig();
    $mailer = acymailing_get('helper.mailer');
    $mailer->report = true; 
    $mailer->trackEmail = true; 
    $mailer->autoAddUser = false; 
	foreach($AcyTags as $pk=>$pv)
	{
	
	$mailer->addParam($prefix.$pk,$pv); 
	}
 
	$status=$mailer->sendOne($newsletterid,$email); 
	
	return $status;
    } 
	}
	
	private function getPlanName($pid)
	{
	$db = JFactory::getDbo();
	$query = "SELECT * from sdrs_jblance_plan WHERE pidbt='".$pid."'";
	$db->setQuery($query);
	$res = $db->loadObject();
	return $res;
	}

	
}
?>