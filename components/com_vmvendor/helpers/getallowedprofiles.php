<?php
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2015 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : http://www.nordmograph.com/extensions
 */

defined('_JEXEC') or die;
abstract class VmvendorAllowedProfiles
{
	static function getJSProfileallowed($profiletypes_ids) 
	{
		$app =  JFactory::getApplication();
		$db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$profiletypes_mode 	= $cparams->get('profiletypes_mode');
		$allowed = 0;
		if ($profiletypes_mode == 1)
		{
            $q = "SELECT profile_id FROM #__community_users WHERE userid='" . $user->id . "' ";
        }
        if ($profiletypes_mode == 2)
        {
            $q = "SELECT profiletype FROM #__xipt_users WHERE userid='" . $user->id . "' ";
        }
        $db->setQuery($q);
		$user_profile_id = $db->loadResult();
		$allowedprofiles_array = array();
		if(strpos( $profiletypes_ids , ',' ) )
		{
			$exploded = explode( ',' , $profiletypes_ids);
			$count = count($exploded);
			for($i= 0 ; $i < $count ; $i++ )
			{
				$allowedprofiles_array[] = $exploded[$i];	
			}		  
		}
		else
		{
			$allowedprofiles_array[] = $profiletypes_ids;
        }
        if (in_array($user_profile_id, $allowedprofiles_array))
        {
            $allowed = 1;
        }
        if($allowed==0)
			$app->enqueueMessage( JText::_('COM_VMVENDOR_JSPROFILE_NOTALLOWED'). ' <input type="button" class="btn" name="cancel" id="cancelbutton" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">' );	

        return $allowed;
	}

	static function getESProfileallowed($profiletypes_ids) 
	{
		$app =  JFactory::getApplication();
		$db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$allowed = 0;
		$q = "SELECT profile_id FROM #__social_profiles_maps WHERE user_id='" . $user->id . "' AND state='1' ";
        $db->setQuery($q);
		$user_profile_id = $db->loadResult();
		$allowedprofiles_array = array();
		if(strpos( $profiletypes_ids , ',' ) )
		{
			$exploded = explode( ',' , $profiletypes_ids);
			$count = count($exploded);
			for($i= 0 ; $i < $count ; $i++ )
			{
				$allowedprofiles_array[] = $exploded[$i];	
			}		  
		}
		else
		{
			$allowedprofiles_array[] = $profiletypes_ids;
        }
        if (in_array($user_profile_id, $allowedprofiles_array))
        {
            $allowed = 1;
        }
        if($allowed==0)
			$app->enqueueMessage( JText::_('COM_VMVENDOR_JSPROFILE_NOTALLOWED'). ' <input type="button" class="btn" name="cancel" id="cancelbutton" value="'.JText::_('JCANCEL').'" onclick="history.go(-1)">' );	

        return $allowed;
	}
}