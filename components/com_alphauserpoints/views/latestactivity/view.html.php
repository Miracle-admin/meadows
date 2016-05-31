<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class alphauserpointsViewLatestactivity extends JViewLegacy
{
	function _display($tpl = null) {		
		
		$document	=  JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_alphauserpoints/assets/css/alphauserpoints.css');
	
		$this->assignRef( 'params', $this->params );
		$this->assignRef( 'allowGuestUserViewProfil', $this->allowGuestUserViewProfil );		
		$this->assignRef( 'latestactivity', $this->latestactivity );
		$this->assignRef( 'total', $this->total );
		$this->assignRef( 'limit', $this->limit );
		$this->assignRef( 'limitstart', $this->limitstart );
		$this->assignRef( 'useAvatarFrom', $this->useAvatarFrom );
		$this->assignRef( 'linkToProfile', $this->linkToProfile );
		
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef ('pagination', $pagination );
		
		// insert the page counter in the title of the window page
		$titlesuite =  ( $this->limitstart ) ? ' - ' . $pagination->getPagesCounter() : '';
		$document->setTitle( $document->getTitle() . $titlesuite );		
		
		parent::display($tpl);
	}
	
}
?>