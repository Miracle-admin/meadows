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
class alphauserpointsControllerLatestactivity extends alphauserpointsController
{
	/**
	 * Custom Constructor
	 */
 	public function __construct()	{
		parent::__construct( );
	}
	
	public function display($cachable = false, $urlparams = false) 
	{	
	
		$com_params = JComponentHelper::getParams( 'com_alphauserpoints' );
		$_useAvatarFrom = $com_params->get('useAvatarFrom');	
		$_profilelink	= $com_params->get('linkToProfile');		
		$_allowGuestUserViewProfil = $com_params->get('allowGuestUserViewProfil', 1);	

		$model      = $this->getModel ( 'latestactivity' );
		$view       = $this->getView  ( 'latestactivity','html' );
	
		$app = JFactory::getApplication();
		$menus = $app->getMenu();
		$menu       = $menus->getActive();
		$menuid     = $menu->id;

		$params     = $menus->getParams($menuid);		
		
		$_latestactivity = $model->_getLatestActivity($params);

		$view->assign('params', $params );
		$view->assign('allowGuestUserViewProfil', $_allowGuestUserViewProfil );
		$view->assign('latestactivity', $_latestactivity[0] );
		$view->assign('total', $_latestactivity[1] );
		$view->assign('limit', $_latestactivity[2] );
		$view->assign('limitstart', $_latestactivity[3] );
		$view->assign('useAvatarFrom', $_useAvatarFrom );
		$view->assign('linkToProfile', $_profilelink );
		
		$view->_display();
	}
	

}
?>