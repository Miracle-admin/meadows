<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	models/developer.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 
 class JblanceModelDeveloper extends JModelLegacy {
	
 	function getShowFront(){

 		$app 	= JFactory::getApplication();
 		$user	= JFactory::getUser();
 		$db 	= JFactory::getDbo();
 		//if the user has JoomBri profile, redirect him to the dashboard
 		$hasJBProfile = JblanceHelper::hasJBProfile($user->id);	
 		if($hasJBProfile){
 			$link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
 			$app->redirect($link);
 		}
 		
 		$query	= 'SELECT * FROM #__jblance_usergroup '.
		 		  'WHERE published=1 '.
		 		  'ORDER BY ordering';
 		$db->setQuery($query);
 		$userGroups = $db->loadObjectList();
 	
 		$return[0] = $userGroups;
 		return $return;
 	}
	
	function countries()
	{	
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__jblance_location'));
		$query->where($db->quoteName('parent_id') . ' = '. $db->quote(1));
		$query->order('title ASC');		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$countries = $db->loadObjectList();
		return $countries;		
	}
	
	function getLocations($json=true)
	{	
	$db				= JFactory::getDbo();
	$location_id    =JRequest::getVar('location_id');
	$query = 'SELECT id,title FROM #__jblance_location WHERE parent_id='.$db->quote($location_id);
	//echo $query;
	//die;
	$db->setQuery($query);
	$return=	$db->loadAssocList();
	if(!empty($return))
	{
	if($json)
	echo json_encode($return);
	else return $return;
	}
	else
	{
	if($json)
	echo 0;
	else return 0;
	
	}
	
	}
	
	function getemptyplan(){
 		$db 	= JFactory::getDbo();
 		$query	= 'SELECT * FROM #__jblance_plan WHERE id=5';
   		$db->setQuery($query);
 		$row = $db->loadAssoc();
 		return $row;
 	}
 	
 	function getUserGroupField($userid = null){
 	
 		$app = JFactory::getApplication();
 		$db	= JFactory::getDbo();
 	
 		$session = JFactory::getSession();
 		$ugid = $session->get('ugid', 0, 'register');	//user group id during the registration
 	
 		if(empty($ugid)){
 			if(empty($userid)){		// get the current userid if not passed
 				$user = JFactory::getUser();
 				$userid = $user->id;
 			}
 			$jbuser = JblanceHelper::get('helper.user');
 			$ugroup = $jbuser->getUserGroupInfo($userid, null);
 			$ugid = $ugroup->id;
 		}
 	
 		$jbfields = JblanceHelper::get('helper.fields');		// create an instance of the class FieldsHelper
 		$fields   = $jbfields->getUserGroupTypeFields($ugid);
 	
 		return $fields;
 	}
 	
 	/* Misc Functions */
	function checkUser($username)
	{
		$db			 = JFactory::getDbo();
		$user    =empty($username)?JRequest::getVar('username'):$username;
		$query = 'SELECT username FROM #__users WHERE username='.$db->quote($user);
		
		$db->setQuery($query);
		$return=	$db->loadAssocList();
		if(!empty($return) )
		{
		if(empty($username))
		{
		echo "false";
		}
		else
		{
		return true;
		}
		}
		
		else{
		if(empty($username))
		{
		echo "true";
		}
		else{
		return false;
		}
		}
	}	
	
	
	function checkEmail($email)
	{
		$db			 = JFactory::getDbo();
		$email_id    =empty($email)?JRequest::getVar('email'):$email;
		$query = 'SELECT email FROM #__users WHERE email='.$db->quote($email_id);
		
		$db->setQuery($query);
		$return=	$db->loadAssocList();
		if(!empty($return) )
		{
		if(empty($email))
		{
		echo "false";
		}
		else
		{
		return true;
		}
		}
		
		else{
		if(empty($email))
		{
		echo "true";
		}
		else{
		return false;
		}
		}
	}	
	
	
	function checkProfileUrl($profileUrl)
	{
		$db			 = JFactory::getDbo();
		$profileUrlId    =empty($profileUrl)?JRequest::getVar('url'):$profileUrl;
		$query = 'SELECT profileUrl FROM #__users WHERE profileUrl='.$db->quote($profileUrlId);
		
		$db->setQuery($query);
		$return=	$db->loadAssocList();
		if(!empty($return) )
		{
		if(empty($profileUrl))
		{
		echo "false";
		}
		else
		{
		return true;
		}
		}
		
		else{
		if(empty($profileUrl))
		{
		echo "true";
		}
		else{
		return false;
		}
		}
	}
	
 	
 }