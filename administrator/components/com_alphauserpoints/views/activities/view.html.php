<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class alphauserpointsViewactivities extends JViewLegacy {

	function _displaylist($tpl = null) {
		
		$document	=  JFactory::getDocument();
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' .  JText::_( 'AUP_ACTIVITY' ), 'searchtext' );
		getCpanelToolbar();
		
		if (JFactory::getUser()->authorise('core.create', 'com_alphauserpoints')) {
			JToolBarHelper::custom( 'exportallactivitiesallusers', 'upload.png', 'upload.png', JText::_('AUP_EXPORT_ACTIVITIES'), false );
		}
		
		getPrefHelpToolbar();	
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );
		
		$this->assignRef( 'pagination', $pagination );
		$this->assignRef( 'activities', $this->activities );
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( $tpl) ;
	}	
	
}
?>
