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

class alphauserpointsViewCouponcodes extends JViewLegacy {

	function _displaylist($tpl = null) {

		$document	=  JFactory::getDocument();
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' .  JText::_( 'AUP_COUPON_CODES' ), 'thememanager' );
		
		getCpanelToolbar();
		if (JFactory::getUser()->authorise('core.edit', 'com_alphauserpoints')) {
			JToolBarHelper::editList( 'editcoupon' );
		}
		if (JFactory::getUser()->authorise('core.create', 'com_alphauserpoints')) {
			JToolBarHelper::addNew( 'editcoupon' );
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_alphauserpoints')) {
			JToolBarHelper::custom( 'deletecoupon', 'delete.png', 'delete.png', JText::_('AUP_DELETE') );
		}
		JToolBarHelper::divider();
		//JToolBarHelper::custom( 'qrcodestats', 'qrcode-stats.png', 'qrcode-stats.png', JText::_('AUP_QRCODE'), false );
		$bar =  JToolBar::getInstance('toolbar');
		

		JHtml::_('bootstrap.modal', 'collapseModal');
		$title = JText::_('AUP_GENERATOR');
		$dhtml = "<button data-toggle=\"modal\" data-target=\"#generatorModal\" class=\"btn btn-small\">
					<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
					$title</button>";
		$bar->appendButton('Custom', $dhtml, 'coupongenerator');
		
		//$bar->appendButton( 'Popup', 'upload', JText::_('AUP_GENERATOR'), 'index.php?option=com_alphauserpoints&task=coupongenerator&layout=modal&tmpl=component', 800, 560, 0, 0, 'window.top.location.reload(true);document.location.reload(true);' );
		
		if (JFactory::getUser()->authorise('core.create', 'com_alphauserpoints')) {
			JToolBarHelper::custom( 'exportcoupons', 'upload.png', 'upload.png', JText::_('AUP_EXPORT'), false );		
		}
		
		getPrefHelpToolbar();
		
		isIEAdm();
		
		$this->assignRef( 'couponcodes', $this->couponcodes );
		$this->assignRef( 'lists', $this->lists );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef( 'pagination', $pagination );
		
		parent::display( $tpl) ;
	}
	
	function _edit_coupon($tpl = null) {
		
		$document	= JFactory::getDocument();
		
		JFactory::getApplication()->input->set( 'hidemainmenu', 1 );
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title(  $logo . 'AlphaUserPoints :: ' . JText::_( 'AUP_COUPON_CODES' ), 'thememanager' );
		getCpanelToolbar();
		
		if ( $this->row->printable ) {
			$bar =  JToolBar::getInstance('toolbar');
			$bar->appendButton( 'Popup', 'print', JText::_('AUP_PRINT'), 'index.php?option=com_alphauserpoints&task=printcoupon&id='.$this->row->id.'&layout=modal&tmpl=component', 740, 480, 0, 0 );
			JToolBarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit.state', 'com_alphauserpoints')) {
			JToolbarHelper::apply('applycoupon');
			JToolBarHelper::save( 'savecoupon' );
		}
		
		JToolBarHelper::cancel( 'cancelcoupon' );
		getPrefHelpToolbar();				
  		//JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		//$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		$this->assignRef( 'row', $this->row );
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( "form" ) ;
	}
	
	function _displayQRcodestats ($tpl = null) {
	
		$document	=  JFactory::getDocument();
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title( $logo . 'AlphaUserPoints :: ' .  JText::_( 'AUP_QRCODE' ), 'thememanager' );
		
		getCpanelToolbar();
		JToolBarHelper::back();
		getPrefHelpToolbar();
		
		$this->assignRef( 'qrcodestats', $this->qrcodestats );
		$this->assignRef( 'id', $this->id );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );
		$this->assignRef( 'pagination', $pagination );
		
		parent::display( 'qrstats') ;
	}
	
	function _generate_coupon($tpl = null) {
		
		$document	=  JFactory::getDocument();
  		//JHTML::_('behavior.mootools');
		JHTML::_('behavior.calendar');
		
		$this->assignRef( 'lists', $this->lists );
		
		parent::display( "generator" ) ;
	}
	
	
	function _print_coupon($tpl = null) {
		
		$document	=  JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_alphauserpoints/assets/css/print_coupon.css');
		
  		//JHTML::_('behavior.mootools');
		
		$this->assignRef( 'couponcode', $this->couponcode );
		$this->assignRef( 'points', $this->points );
		$this->assignRef( 'sitename', $this->sitename );
		
		parent::display( "print" );
	}
	
	
}
?>
