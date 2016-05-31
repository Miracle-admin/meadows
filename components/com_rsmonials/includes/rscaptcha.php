<?php
/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
 
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define( 'DS', DIRECTORY_SEPARATOR );

define('JPATH_BASE', '..'.DS.'..'.DS.'..'.DS.'' );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$app = JFactory::getApplication('site');

$app = JFactory::getApplication(defined('_VM_IS_BACKEND') ? 'administrator' : 'site');

$db = JFactory::getDBO();

require_once ( JPATH_BASE.DS.'components'.DS.'com_rsmonials'.DS.'includes'.DS.'rsfunctions.php' );

//@session_start();
$session = JFactory::getSession();

//Let's generate a totally random string using md5 
$md5_hash = md5(rand(0,999)); 
//We don't need a 32 character long string so we trim it down to 5 
$security_code = strtoupper(substr($md5_hash, 15, 5));

//Set the session to store the security code
//$_SESSION["RSM_code"] = $security_code;
$session->set('RSM_code', $security_code);

//Set the image width and height 
$width = 100; 
$height = 40;  

//Create the image resource 
$image = imagecreate($width, $height);  

//We are making three colors, white, black and gray 
$fontcol = imagecolorallocate($image, 0, 0, 0); 
$bgcol = imagecolorallocate($image, 200, 200, 200); 
$linecol = imagecolorallocate($image, 200, 200, 200); 

//Make the background black 
imagefill($image, 0, 0, $bgcol); 

//Add randomly generated string in white to the image
imagestring($image, 6, 27, 12, $security_code, $fontcol); 

//Throw in some lines to make it a little bit harder for any bots to break 
//imagerectangle($image,0,0,$width-1,$height-1,$linecol); 
//imageline($image, 0, $height/2, $width, $height/2, $linecol); 
//imageline($image, $width/2, 0, $width/2, $height, $linecol); 

//Tell the browser what kind of file is come in 
header("Content-Type: image/jpeg"); 

//Output the newly created image in jpeg format 
imagejpeg($image); 

//Free up resources
imagedestroy($image); 
?>