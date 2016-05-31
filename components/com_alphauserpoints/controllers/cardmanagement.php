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
class alphauserpointsControllerCardmanagement extends JControllerLegacy
{
	
	
public function newcard()
{

$app=JFactory::getApplication();

$user = JFactory::getUser();
$response = array();

if(!JRequest::checkToken('get'))
{
//invalid token
$response["message"]="Invalid token.";
$response["type"]   ="error";
$response['err_code'] = 0;
echo json_encode($response);
}
if($user->guest)
{
//session expired
$response["message"]="Your session has been expired,please login to proceed further.";
$response["type"]   ="error";
$response['err_code'] = 1;
echo json_encode($response);
}

//process payment
 $post 	= $app->input->post->getArray();
$nonce = $post["nonce"];
$json = $post["j"];
if(!empty($nonce))
{
$model=$this->getModel("cardmanagement");

$model->addcard($nonce,$json);
}
else
{
//payment method not received
$response["message"]="Payment method not received.";
$response["type"]   ="error";
$response['err_code'] = 2;
echo json_encode($response);

}



$app->close();
}

public function gettoken()
{
$app=JFactory::getApplication();
echo JSession::getFormToken();
$app->close();
}


}
?>