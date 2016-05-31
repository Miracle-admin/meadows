<?php 
/** @copyright Copyright (c) 2007-2015 Joobi Limited. All rights reserved.
* @link joobi.co
* @license GNU GPLv3 */
defined('_JEXEC') or die;
if ((defined('_JEXEC')) && !defined('JOOBI_SECURE')) define('JOOBI_SECURE',true);
defined('JOOBI_SECURE') or die('J....');


class com_jcenterInstallerScript {

	


	function install($adapter) {

		include( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install.php' );
				$joobiInstaller = new install_joobi ;
		$joobiInstaller->setCMS("joomla","jcenter");
		$joobiInstaller->setDistribServer("http://joobiserver.com/w/jml_prod/r2");
		$joobiInstaller->setLicense("http://register.joobi.co");
		$joobiInstaller->setSound();
		if($joobiInstaller->installJoobi()) return true;
		else return false;

	}
	


	function update($adapter) {

		include( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install.php' );
				$joobiInstaller = new install_joobi ;
		$joobiInstaller->setCMS("joomla","jcenter");
		$joobiInstaller->setDistribServer("http://joobiserver.com/w/jml_prod/r2");
		$joobiInstaller->setLicense("http://register.joobi.co");
		$joobiInstaller->setSound();
		if($joobiInstaller->installJoobi()) return true;
		else return false;

	}

	


	function uninstall($adapter) {

		include( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install.php' );
				$joobiInstaller = new install_joobi ;
		$joobiInstaller->setCMS("joomla","jcenter");
		if($joobiInstaller->deleteJoobi()) return true;
		else return false;

	}
	


	function postflight($route,$adapter)
	{

						$addon = WAddon::get( 'install.'.JOOBI_FRAMEWORK );
		$lcAppName = strtolower("jcenter");
		$addon->setExtensionInfo($lcAppName.'.application');
		$addon->refreshMenus($lcAppName);
	}
}

if (strpos(__FILE__, 'com_jcenter') !== FALSE)
{
	$joobiEntryPoint = __FILE__ ;
	$status = @include(JPATH_ROOT.DIRECTORY_SEPARATOR.'joobi'.DIRECTORY_SEPARATOR.'entry.php');
}


