<?php
/*
 * @component Vm2wishlists
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
class Vm2wishlistsViewAdders extends JViewLegacy
{
	protected $adders;
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		$app 			= JFactory::getApplication();
		
	//	$model  = $this->getModel('adders', 'Vm2wishlistsModel');
		$this->adders		= $this->get('adders');
		// Display the view
		$this->users	= $this->adders[0];
		$this->total	= $this->adders[1];
 		$this->limit		= $this->adders[2];
		$this->limitstart	= $this->adders[3];
		
		
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );		
		$this->assignRef('pagination', $pagination );

		

		// Display the view
		parent::display($tpl);
	}
}