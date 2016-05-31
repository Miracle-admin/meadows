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

class alphauserpointsViewTemplateinvite extends JViewLegacy {

	function _displaylist($tpl = null) {

		$document	=  JFactory::getDocument();
		
		$logo = '<img src="'. JURI::root() . 'administrator/components/com_alphauserpoints/assets/images/icon-48-alphauserpoints.png" />&nbsp;&nbsp;';
		
		JToolBarHelper::title(  $logo . 'AlphaUserPoints :: ' . JText::_( 'AUP_TEMPLATES' ), 'thememanager' );
		
		getCpanelToolbar();
		if (JFactory::getUser()->authorise('core.edit', 'com_alphauserpoints')) {
			JToolBarHelper::editList( 'edittemplateinvite' );
		}
		if (JFactory::getUser()->authorise('core.create', 'com_alphauserpoints')) {
			JToolBarHelper::addNew( 'edittemplateinvite' );
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_alphauserpoints')) {
			JToolBarHelper::custom( 'deletetemplateinvite', 'delete.png', 'delete.png', JText::_('AUP_DELETE') );
		}
		getPrefHelpToolbar();
			
		$this->assignRef( 'templateinvite', $this->templateinvite );
	
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef( 'pagination', $pagination );
		
		parent::display( $tpl) ;
	}
	
	function _edit_templateinvite($tpl = null) {
		
		$document	=  JFactory::getDocument();
		
		JFactory::getApplication()->input->set( 'hidemainmenu', 1 );
		
		JToolBarHelper::title( 'AlphaUserPoints :: ' .  JText::_( 'AUP_TEMPLATE' ), 'thememanager' );
		getCpanelToolbar();		
		if (JFactory::getUser()->authorise('core.edit.state', 'com_alphauserpoints')) {
			JToolbarHelper::apply('applytemplateinvite');
			JToolBarHelper::save( 'savetemplateinvite' );
		}
		JToolBarHelper::cancel( 'canceltemplateinvite' );
		getPrefHelpToolbar();				
		$document->addScriptDeclaration("window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });");
		
		$lists = array();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', $this->row->published);
		$options = "";		
		$options[] = JHTML::_('select.option', '0', JText::_( 'AUP_PLAIN-TEXT' ) );
		$options[] = JHTML::_('select.option', '1', JText::_( 'AUP_HTML' ) );
		$lists['emailformat'] = JHTML::_('select.genericlist', $options, 'emailformat', 'class="inputbox" size="1"' ,'value', 'text', $this->row->emailformat );
		$lists['bcc2admin'] = JHTML::_('select.booleanlist', 'bcc2admin', '', $this->row->bcc2admin);
		
		$this->assignRef( 'row', $this->row );
		$this->assignRef( 'lists', $lists );
		
		parent::display( "form" ) ;
	}	
	
}
?>
