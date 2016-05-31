<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerRssactivity extends alphauserpointsController
{
	/**
	 * Custom Constructor
	 */
 	public function __construct()	{
		parent::__construct( );
	}
	
	public function display($cachable = false, $urlparams = false) 
	{
		$model      = $this->getModel ( 'rssactivity' );
		$view       = $this->getView  ( 'rssactivity','html' );	

		$model->_showRSSAUPActivity();

	}

}
?>