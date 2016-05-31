<?php
defined('_JEXEC') or die;

class ModMeadowsLoginHelper{	
	public static function getuserInfo()
	{
		$user	= JFactory::getUser();		
		$db			 = JFactory::getDbo();
		$query = 'SELECT * FROM #__jblance_user WHERE user_id='.$db->quote($user->id);		
		$db->setQuery($query);
		$result = $db->loadObject();
		
		return $result;
	
	
	}
} //End of class
