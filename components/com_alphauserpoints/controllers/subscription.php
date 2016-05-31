<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerSubscription extends JControllerLegacy
{
	
	
public function processSubscription()
    {
	JSession::checkToken() or die( 'Invalid Token' );
	
	$app    = JFactory::getApplication();
    $post 	= $app->input->post->getArray();
	
	$subId  = $post['subid'];
	
	$pmn    = $post['payment_method_nonce'];
	
	$model =$this->getModel('subscription');
    
	$model->processSubscription($subId,$pmn);
    }
	
public function cancelSubscription()
{
$user=JFactory::getUser();
if(!$user->guest )
{
$model = $this->getModel("subscription");
$model->cancelSubscription($user->id,true,true);
}
}	
}
?>