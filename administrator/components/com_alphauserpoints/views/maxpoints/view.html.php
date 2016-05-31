<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

Jimport( 'joomla.application.component.view');

class alphauserpointsViewMaxpoints extends JViewLegacy {

	function showform($tpl = null) {
		
		$document	=  JFactory::getDocument();
		
		JFactory::getApplication()->input->set( 'hidemainmenu', 1 );
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' .  JText::_( 'AUP_SETMAXPOINST' ), 'cpanel' );
		getCpanelToolbar();
		if (JFactory::getUser()->authorise('core.edit.state', 'com_alphauserpoints')) {
			JToolBarHelper::save( 'savemaxpoints' );
		}
		getPrefHelpToolbar();	
			
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		$this->assignRef('setpoints', $this->setpoints );		
		
		parent::display( $tpl);
		
	}
}
?>
