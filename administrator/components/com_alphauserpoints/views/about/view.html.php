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

class alphauserpointsViewAbout extends JViewLegacy {

	function show($tpl = null) {	
	
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';

		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' . JText::_( 'AUP_ABOUT' ), 'systeminfo' );
		
		getCpanelToolbar();
		
		JToolBarHelper::back();		
		
		getPrefHelpToolbar();
		
		parent::display( $tpl) ;
		
	}
}
?>
