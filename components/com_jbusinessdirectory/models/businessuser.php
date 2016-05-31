<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');

class JBusinessDirectoryModelBusinessUser extends JModelLegacy
{ 
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Populate state
	 * @param unknown_type $ordering
	 * @param unknown_type $direction
	 */
	protected function populateState($ordering = null, $direction = null){
		$app = JFactory::getApplication('administrator');	
	}
	
	function addJoomlaUser($details){
	
		// "generate" a new JUser Object
		$user = JFactory::getUser(0); // it's important to set the "0" otherwise your admin user information will be loaded
			
		jimport('joomla.application.component.helper');
		$usersParams = JComponentHelper::getParams( 'com_users' ); // load the Params
			
		$userdata = array(); // place user data in an array for storing.
		$userdata['name'] = $details["name"];
		$userdata['email'] = $details["email"];
		$userdata['username'] = $details["username"];
	
		//set password
		$userdata['password'] =$details["password"];
		$userdata['password2'] = $details["passwordc"];
			
		//set default group.
		$usertype = $usersParams->get( 'new_usertype',2 );
		if (!$usertype)
		{
			$usertype = 'Registered';
		}
			
		//default to defaultUserGroup i.e.,Registered
		$userdata['groups']=array($usertype);
		$useractivation = $usersParams->get( 'useractivation' ); 					// in this example, we load the config-setting
		
		$userdata['block'] = 0; // don't block the user
		//now to add the new user to the dtabase.
		if (!$user->bind($userdata)) {
			JError::raiseWarning('', JText::_( $user->getError())); // something went wrong!!
		}
		if (!$user->save()) {
			// now check if the new user is saved
			JError::raiseWarning('', JText::_( $user->getError())); // something went wrong!!
		}
		
		return $user->id;
	}
	
	function loginUser(){
		$app    = JFactory::getApplication();
		
		$input  = $app->input;
		$method = $input->getMethod();
		
		$data['username']  = $input->$method->get('username', '', 'USERNAME');
		$data['password']  = $input->$method->get('password', '', 'RAW');
		$data['secretkey'] = $input->$method->get('secretkey', '', 'RAW');
		
		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = $data['secretkey'];
		
		$options=array();
		
		$result = $app->login($credentials, $options);
		
		return $result;
	}
}

?>