<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * Script file of HelloWorld plugin
 * Group: Universe
 */
class com_jBusinessDirectoryInstallerScript
{
        /**
         * Method to install the extension
         * $parent is the class calling this method
         *
         * @return void
         */
        function install($parent) 
        {
               
        }
 
        /**
         * Method to uninstall the extension
         * $parent is the class calling this method
         *
         * @return void
         */
        function uninstall($parent) 
        {
               
        }
 
        /**
         * Method to update the extension
         * $parent is the class calling this method
         *
         * @return void
         */
        function update($parent) 
        {
                
        }
 
        /**
         * Method to run before an install/update/uninstall method
         * $parent is the class calling this method
         * $type is the type of change (install, update or discover_install)
         *
         * @return void
         */
        function preflight($type, $parent) 
        {
             
        }
 
        /**
         * Method to run after an install/update/uninstall method
         * $parent is the class calling this method
         * $type is the type of change (install, update or discover_install)
         *
         * @return void
         */
        function postflight($type, $parent) 
        {
        	
        	jimport('joomla.installer.helper');
        	$basedir = dirname(__FILE__);
        	$packageDir = $basedir .'/admin/'.'packages';
        	$extensionsDirs = JFolder::folders($packageDir);
        	foreach( $extensionsDirs as $extensionDir)
        	{
        		$tmpInstaller = new JInstaller();
        		$tmpInstaller->setOverwrite(true);
        		if(!$tmpInstaller->install($packageDir.'/'.$extensionDir))
        		{
        			JError::raiseWarning(100,"Extension :". $extensionDir);
        		}
        	}
        	$path = JPATH_ADMINISTRATOR . '/components/com_jbusinessdirectory/help/readme.html';
        	include( $path);
        	
        	$db = JFactory::getDBO();
        	$db->setQuery( " UPDATE #__extensions SET enabled=1 WHERE element='urltranslator' " );
        	$db->query();
        	
        	require_once( JPATH_ADMINISTRATOR.'/components/com_jbusinessdirectory/library/category_lib.php');
        	$service=new JBusinessDirectorCategoryLib();
        	$service->updateCategoryStructure();
               
        }
}