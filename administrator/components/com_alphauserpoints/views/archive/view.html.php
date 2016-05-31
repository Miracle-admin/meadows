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

class alphauserpointsViewArchive extends JViewLegacy {

	function show($tpl = null) {
		
		$document	=  JFactory::getDocument();
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' . JText::_('AUP_COMBINE_ACTIVITIES'), 'install' );
		
		getCpanelToolbar();

		JToolBarHelper::back();
		
		getPrefHelpToolbar();
	
		parent::display( $tpl);
		
	}
}
?>
