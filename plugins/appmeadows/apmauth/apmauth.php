<?php
/**
 * @version    $Id: myauth.php 7180 2007-04-23 16:51:53Z jinx $
 * @package    Joomla.Tutorials
 * @subpackage Plugins
 * @license    GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
/**
 * Example Authentication Plugin.  Based on the example.php plugin in the Joomla! Core installation
 *
 * @package    Joomla.Tutorials
 * @subpackage Plugins
 * @license    GNU/GPL
 */
class plgAuthenticationApmauth extends JPlugin
{
    /**
     * This method should handle any authentication and report back to the subject
     * This example uses simple authentication - it checks if the password is the reverse
     * of the username (and the user exists in the database).
     *
     * @access    public
     * @param     array     $credentials    Array holding the user credentials ('username' and 'password')
     * @param     array     $options        Array of extra options
     * @param     object    $response       Authentication response object
     * @return    boolean
     * @since 1.5
     */
    function onUserAuthenticate( $credentials, $options, &$response )
    {
	
	echo"<pre>";
	print_r($response); die;
        /*
         * Here you would do whatever you need for an authentication routine with the credentials
         *
         * In this example the mixed variable $return would be set to false
         * if the authentication routine fails or an integer userid of the authenticated
         * user if the routine passes
         */
       /*  $db = JFactory::getDbo();
	$query	= $db->getQuery(true)
		->select('id')
		->from('#__users')
		->where('username=' . $db->quote($credentials['username']));
 
	$db->setQuery($query);
	$result = $db->loadResult();
 
	if (!$result) {
	    $response->status = STATUS_FAILURE;
	    $response->error_message = 'User does not exist';
	} */
 
	/**
	 * To authenticate, the username must exist in the database, and the password should be equal
	 * to the reverse of the username (so user joeblow would have password wolbeoj)
	 */
/* 	if($result && ($credentials['username'] == strrev( $credentials['password'] )))
	{
	    $email = JUser::getInstance($result); // Bring this in line with the rest of the system
	    $response->email = $email->email;
	    $response->status = JAuthentication::STATUS_SUCCESS;
	}
	else
	{
	    $response->status = JAuthentication::STATUS_FAILURE;
	    $response->error_message = 'Invalid username and password';
	} */
    }
}
?>