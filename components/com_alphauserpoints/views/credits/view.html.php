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

class alphauserpointsViewCredits extends JViewLegacy
{


	function display($tpl = null) {	

      

	
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		//caching is not required on payment page
		$app->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
		$app->setHeader('Pragma', 'no-cache');
		$app->setHeader('Expires', '0');
		$this->_validateRequest($user,$app);
	    $parameter = JRequest::getVar("type");
	    if($parameter=="cart")
	    {
		$uKey = $user->id.".".$user->name;
	   
	    $state = $app->getUserState( $uKey,0);
	
		
		
		$document = JFactory::getDocument();
		$redir = JRoute::_(Juri::root()."index.php?option=com_virtuemart&view=cart&Itemid=199");
		
		
		$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
        if ( file_exists($api_AUP))
        {
        require_once ($api_AUP);
        }
		
		$totalPoints = round(AlphaUserPointsHelper::getCurrentTotalPoints( "",$user->id  ));
		$ammount   = $totalPoints==0?$state['amount']:$state['amount']-$totalPoints;
		
		
		//if some how ammount appears to be negative or zero
	    if($ammount <= 0 )
		{
		$app->redirect($redir);
		}
		
	    $products = $state['products'];
		$order_id = $state['virtuemart_order_id'];
		
		$virtuemart_paymentmethod_id = $state['virtuemart_paymentmethod_id'];
	    if($state!=0)
		{
		
		$this->assignRef("ammount",$ammount);
		$this->assignRef("products",$products);
		
		}
		}
		$token=$this->get('ClientToken');
		
		$this->assignRef("token",$token);
	    $this->assignRef("type",$parameter);
			
		 parent::display($tpl);
	}
	
	private function _validateRequest($user,$app)
	{
		$parameter = JRequest::getVar("type","");
		
		if(empty($parameter) && ( $parameter!="cart" || $parameter!="deposit"))
		{
		$app->redirect(JUri::root(),"Unknown error occured","error");
		}
		if($user->id==0)
		{
		if($parameter=="cart")
		{
		$return = JUri::root()."index.php?option=com_alphauserpoints&view=credits&type=cart";
		}
		if($parameter=="deposit")
		{
		$return = JUri::root()."index.php?option=com_alphauserpoints&view=credits&type=deposit";
		}
		
		$redirectUrl=JRoute::_(JUri::base()."index.php?option=com_users&view=login&return=".urlencode(base64_encode($return)));
		
		$app->redirect($redirectUrl,"You need to login first","error");
		}
		
		}
	
	
	
}
?>