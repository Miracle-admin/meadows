<?php
define( '_JEXEC', 1 );

if (stristr( $_SERVER['SERVER_SOFTWARE'], 'win32' )) {
	define( 'JPATH_BASE', realpath(dirname(__FILE__).'\..\..\..\..' ));
} else define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../../..' ));

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
$app = JFactory::getApplication('site');
$app->initialise();

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.environment.uri' );

$lang = JFactory::getLanguage();
$lang->load( 'com_alphauserpoints', JPATH_SITE);	

$username = JFactory::getApplication()->input->get( 'n', '', 'username' );
$username = str_replace("'", "", $username);

$baseimg = str_replace('ajax', 'images', JURI::base());

$url_tick_img = $baseimg . 'tick.png';
$url_load_img = $baseimg . 'loader.gif';

if ( strlen($username)>3  ) 
	{
	$db	   = JFactory::getDBO();	
	$query = "SELECT id FROM #__users WHERE `username`='".trim($username)."' LIMIT 1";
	$db->setQuery( $query );
	$userexist = $db->loadResult();
	
	if( $userexist )
		{
		echo '<img src="'.$url_tick_img.'" alt="" align="absmiddle" />';
		}
		else
		{
		echo '<font color="red">'.JText::_( 'AUP_THIS_USERNAME_NOT_EXIST' ).'</font>';
		}
	}
	else 
	{	
	echo '<img src="'.$url_load_img.'" alt="" align="absmiddle" /> ';
	}
?>
