<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * extension menu created by Mike Gusev (migus)
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerRegisterqrcode extends alphauserpointsController
{
	/**
	 * Custom Constructor
	 */
 	public function __construct()	{
		parent::__construct( );
	}
	
	//function registerQRcode()
	public function display($cachable = false, $urlparams = false)
	{

		$model      = $this->getModel ( 'registerqrcode' );
		$view       = $this->getView  ( 'registerqrcode','html' );
		
		$couponCode = JFactory::getApplication()->input->get('QRcode', '', 'string');
		$trackIDNew = uniqid('', true);		
		$trackID = JFactory::getApplication()->input->get('trackID', $trackIDNew, 'string');
		$model->trackQRcode($trackID, $couponCode);
		
		$view->assign( 'couponCode', $couponCode );
		$view->assign( 'trackID', $trackID );
		
		$view->display();
	}
	
	public function attribqrcode() 
	{

		$model      = $this->getModel ( 'registerqrcode' );
		$view       = $this->getView  ( 'registerqrcode','html' );
	
		$points = $model->attribPoints();
		
		$view->assign( 'points', $points );
		
		$view->displayResult();
	}


}
?>