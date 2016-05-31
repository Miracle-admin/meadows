<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerAddressbook extends alphauserpointsController
{
	/**
	 * Custom Constructor
	 */
 	public function __construct()	{
	
		parent::__construct( );
		
	}	

	/**
	* Show import contacts
	*/
	public function display() {
		
		$view       = $this->getView  ( 'invite','html' );
		
		$view->_display_addressbook();
	}
	
}
?>