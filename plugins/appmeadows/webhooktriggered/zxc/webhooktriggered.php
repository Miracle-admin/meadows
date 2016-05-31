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
class plgAppmeadowsWebhooktriggered extends JPlugin
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
function OnWebhookTriggered($triggeredOn,$status,$planId,$subId,$type,$rowEH)
{
$this->_createHtml(array($triggeredOn,$status,$planId,$subId,$type,$rowEH););
}
						
							
private function _createHtml($data)
{
ob_start();

echo"<pre><b style='color:red;'>";

print_r($data);
echo"</b>";
$output = ob_get_clean();

$outputFile = JPATH_SITE.DS."logshtml".DS."Test.html";

$fileHandle = fopen($outputFile, 'w') or die('File creation error.');

fwrite($fileHandle, $output);

fclose($fileHandle);

return true;

}								   
					
		
}
?>