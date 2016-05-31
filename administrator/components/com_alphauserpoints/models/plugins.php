<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelPlugins extends JmodelLegacy {
	
	function __construct(){
		parent::__construct();
	}
	

	function _loadPluginElements( $xmlFile )
	{			
	
		if( substr( strtolower($xmlFile), -4) != ".xml" ) return;		
		
		$app = JFactory::getApplication();
		
		$error = "";
	
		// XML library
		//jimport('joomla.utilities.simplexml');
		
		// Import file dependencies
		require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'includes'.DS.'simplexml.php');
		 
		//$xmlDoc =  JFactory::getXMLParser('Simple');		
		$xmlDoc = new JSimpleXML();
		
		$_xmlFile = JPATH_COMPONENT_ADMINISTRATOR . DS . 'assets' . DS . 'plugins' . DS . $xmlFile;
		
		$pos = strrpos ($_xmlFile, DS);	
		if ( $pos )	$_installPath = substr($_xmlFile, 0, $pos);
		
		if ( $xmlDoc->loadFile($_xmlFile) ) {

			$root = & $xmlDoc->document;
			
			if ( $root->name() == 'alphauserpoints' ) 
			{			
				$element = $root->rule;
				$nameRule = $element ? $element[0]->data() : '';
	
				$element = $root->description;
				$descriptionRule = $element ? $element[0]->data() : '';
				
				$element = $root->component;
				$componentRule = $element ? $element[0]->data() : '';
								
				$element = $root->plugin_function;
				$pluginRule = $element ? $element[0]->data() : '';
				
				$element = $root->fixed_points;				
				$fixedpointsRule = $element ? $element[0]->data() : '';
				$fixedpointsRule = trim(strtolower($fixedpointsRule));
				$fixedpointsRule = ( $fixedpointsRule=='true' ) ? 1 : 0;
				
				$element = @$root->category;
				$categoryRule = @$element ? @$element[0]->data() : '';
				
				$element = @$root->display_message;
				$displayMessage = @$element ? @$element[0]->data() : '';
				$displayMessage = trim(strtolower($displayMessage));
				$displayMessage = ( $displayMessage=='true' ) ? 1 : 0;
				
				$element = @$root->email_notification;
				$emailNotification = @$element ? @$element[0]->data() : '';
				$emailNotification = trim(strtolower($emailNotification));
				$emailNotification = ( $emailNotification=='true' ) ? 1 : 0;
				
				// insert in table
				if ( $nameRule!='' && $descriptionRule!='' && $componentRule!='' && $pluginRule!='') {
				
					$db	= JFactory::getDBO();
					// check if already exist...					
					$query = "SELECT COUNT(*) FROM #__alpha_userpoints_rules WHERE `plugin_function`='$pluginRule'";
					$db->setQuery( $query );
					$resultCount = $db->loadResult();
					if ( !$resultCount ) {					
						$query = "INSERT INTO #__alpha_userpoints_rules VALUES ('', '".$nameRule."', '".$descriptionRule."', '".$componentRule."', '".$pluginRule."', '1', '".$componentRule."', '', '', '0', '0', 0, '0000-00-00 00:00:00', '', '', '', '', '0', '0', '0', '0', '1', '".$fixedpointsRule."', '".$categoryRule."', '".$displayMessage."', '', '0', '".$emailNotification."', '', '', '0', '0', '0', '0', '0', '1')";
						$db->setQuery( $query );
						if ( $db->query() ) {							
							$msg = JText::_('AUP_NEW_RULE_INSTALLED_SUCCESSFULLY') . ' : <b>' . $nameRule . '</b>';
							$app->enqueueMessage( $msg );
						} else {
							$error = JText::_('This rule is not installed properly') ;
							JError::raiseNotice(0, $error );
						}
					} else {
						$error = JText::_('AUP_THISRULEALREADYEXIST');
						JError::raiseNotice(0, $error );
					}
				}  else {
					$error = JText::_('AUP_XML_FILE_INVALID');
					JError::raiseWarning(0, $error );
				}
				
			} 	elseif ( $root->name() == 'extension' ) {			
			
				// Install standard plugins for Joomla!
				
				jimport('joomla.installer.installer');
				jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');
								
				$plugin_installer = new JInstaller;
				$installStandard = $plugin_installer->install($_installPath);
				
				JFolder::delete($_installPath);

				$redirecturl = "index.php?option=com_plugins";		
		    JControllerLegacy::setRedirect($redirecturl);
		    JControllerLegacy::redirect();
			
			} else {		
			
				unset ($xmlDoc);
				$error = JText::_('AUP_XML_FILE_INVALID');
				JError::raiseWarning(0, $error );
			}				

		} else {
			unset ($xmlDoc);
			$error = JText::_('AUP_XML_FILE_INVALID');
			JError::raiseWarning(0, $error );
		}		
		
	}
	
}
?>