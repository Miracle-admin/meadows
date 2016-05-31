<?php
/**
 * Social Login
 *
 * @version 	1.0
 * @author		Arkadiy, Joomline
 * @copyright	© 2012. All rights reserved.
 * @license 	GNU/GPL v.3 or later.
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_slogin/helpers/password.php';

class PlgAuthenticationSocialLogin extends JPlugin
{
	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param	array	Array holding the user credentials
	 * @param	array	Array of extra options
	 * @param	object	Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onUserAuthenticate($credentials, $options, &$response)
	{ 
	//die('PlgAuthenticationSociallogin plugin');
	//echo "<pre>social login authenticate bipin thakur --";
	//echo "<pre>";print_r($credentials); echo "options "; print_r($options);  echo "response "; print_r($response);die;
	
	
 	//echo "<br/>";
	// Get a database object
		$response->type = 'sociallogin';	
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
//die('PlgAuthenticationSociallogin plugin');
		$query->select('id')
			->from('#__users')
			->where('username=' . $db->quote($credentials['username']));
		$uid = $db->setQuery($query)->loadResult();
		
		if ($uid)
		{
				$user = JUser::getInstance($uid); // Bring this in line with the rest of the system
				$response->email = $user->email;
				$response->fullname = $user->name;
			
				if (JFactory::getApplication()->isAdmin())
				{
					$response->language = $user->getParam('admin_language');
				}
				else
				{
					$response->language = $user->getParam('language');
				} 			
			//	echo "<pre>"; print_r($user); echo "options "; print_r($options);  echo "response "; print_r($response); 
							
				$response->status = JAuthentication::STATUS_SUCCESS;
				//echo "<pre>"; print_r($response);				die('plugin');		
				$response->error_message = '';
		
				//print_r($response->status);			
				//die('PlgAuthenticationSociallogin');	
		}
		else
		{
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
			
		}
	}
}
