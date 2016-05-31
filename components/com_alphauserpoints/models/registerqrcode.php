<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelRegisterqrcode extends JmodelLegacy {

	function __construct(){
		parent::__construct();
	}

	function attribPoints()
	{	
		$app = JFactory::getApplication();
		$db			    = JFactory::getDBO();
		
		$login  		= JFactory::getApplication()->input->get('login', '', 'username');
		$coupon			= JFactory::getApplication()->input->get('couponCode', '', 'string');
		$trackID		= JFactory::getApplication()->input->get('trackID', '', 'string');
		
		$query = "SELECT id FROM #__alpha_userpoints_qrcodetrack WHERE `trackid`='".$trackID."'";
		$db->setQuery( $query );
		$idTrack = $db->loadResult();
		
		$query = "SELECT id FROM #__users WHERE `username`='" . $login . "' AND `block`='0'";
		$db->setQuery($query);
		$userID = $db->loadResult();
		
		if ( $userID ) 
		{
			// insert API AlphaUserPoint 
			$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
			require_once ($api_AUP);
			
			$referrerid = AlphaUserPointsHelper::getAnyUserReferreID( $userID );
			
			$nullDate	= $db->getNullDate();
			$date =& JFactory::getDate();
			$now  = $date->toSql();
			
			$query = "SELECT * FROM #__alpha_userpoints_coupons WHERE `couponcode`='$coupon' AND (`expires`>='$now' OR `expires`='0000-00-00 00:00:00')";
			$db->setQuery( $query );
			$result  = $db->loadObjectList();
			if ( $result )
			{			
				$resultCouponExist = 0;
			
				// check if public or private coupon
				if ( !$result[0]->public )
				{
					// private -> usable once per one user
					$query = "SELECT count(*) FROM #__alpha_userpoints_details WHERE `keyreference`='$coupon' AND `enabled`='1'";
					$db->setQuery( $query );
					$resultCouponExist = $db->loadResult();
					if ( !$resultCouponExist )
					{
						// insert points
						AlphaUserPointsHelper::newpoints( 'sysplgaup_couponpointscodes', $referrerid, $result[0]->couponcode, $result[0]->description, $result[0]->points );
						//if ( AlphaUserPointsHelper::newpoints( 'sysplgaup_couponpointscodes', $referrerid, $result[0]->couponcode, $result[0]->description, $result[0]->points, true )===true ){
							// insert confirmed in track table
							$this->updateTableQRTrack($idTrack);
							return $result[0]->points;
						//}
					} 
					else 
					{
						$msg = (JText::_('AUP_THIS_COUPON_WAS_ALREADY_USED'));
			      		JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&view=registerqrcode&QRcode='.$coupon.'&trackID='.$trackID, $msg);
			      		JControllerLegacy::redirect(); 
					}
				} 
				elseif ( $result[0]->public )
				{
					// public -> usable once per all users
					$keyreference = $coupon . "##" . $userID;
					$query = "SELECT count(*) FROM #__alpha_userpoints_details WHERE `keyreference`='$keyreference' AND `enabled`='1'";
					$db->setQuery( $query );
					$resultCouponExist = $db->loadResult();
					if ( !$resultCouponExist )
					{
						// insert points
						AlphaUserPointsHelper::newpoints( 'sysplgaup_couponpointscodes', $referrerid, $keyreference, $result[0]->description, $result[0]->points );
						//if ( AlphaUserPointsHelper::newpoints( 'sysplgaup_couponpointscodes', $referrerid, $keyreference, $result[0]->description, $result[0]->points, true )===true ){
							// insert confirmed in track table
							$this->updateTableQRTrack($idTrack);
							return $result[0]->points;
						//}
					} 
					else 
					{
						$msg = (JText::_('AUP_THIS_COUPON_WAS_ALREADY_USED'));
			      		JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&view=registerqrcode&QRcode='.$coupon.'&trackID='.$trackID, $msg);
			      		JControllerLegacy::redirect();	
					}				
				} 
			}
			else
			{
				$msg = (JText::_('AUP_COUPON_NOT_AVAILABLE'));
        		JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&view=registerqrcode&QRcode='.$coupon.'&trackID='.$trackID, $msg);
			  	JControllerLegacy::redirect();	
				
			}		

		
		
		}
		else
		{
			// no username
			$msg = JText::_('ALERTNOTAUTH' );
      		JControllerLegacy::setRedirect('index.php?option=com_alphauserpoints&view=registerqrcode&QRcode='.$coupon.'&trackID='.$trackID, $msg);
			JControllerLegacy::redirect();
		}
		
	}
	
	
	function trackQRcode($trackID='', $couponCode='')
	{	
		if (!$trackID || !$couponCode) return;
		
		$db			= JFactory::getDBO();
		
		//already inserted (e.g. error login)
		$query = "SELECT trackid FROM #__alpha_userpoints_qrcodetrack WHERE `trackid`='".$trackID."'";
		$db->setQuery( $query );
		$alreadytrackid = $db->loadResult();
		if (!$alreadytrackid) 
		{		
			$couponID = 0;		
			
			$jnow		= JFactory::getDate();		
			$now		= $jnow->toSql();
			
			$query = "SELECT id FROM #__alpha_userpoints_coupons WHERE `couponcode`='".$couponCode."' AND `printable`='1'";
			$db->setQuery( $query );
			$couponID = $db->loadResult();
			
			$ip		  	= $_SERVER["REMOTE_ADDR"];
			$device		= $_SERVER['HTTP_USER_AGENT'];
					
			$ipDetail 	= countryCityFromIP( $ip );
			$country  	= $ipDetail['country']; 
			$city     	= $ipDetail['city'];
		
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');
			$row = JTable::getInstance('qrcodetrack');
			$row->id				= NULL;
			$row->couponid			= $couponID;
			$row->trackid			= $trackID;
			$row->trackdate			= $now;
			$row->country 			= $country;		
			$row->city				= $city;
			$row->device			= $device;
			$row->ip				= $ip;
			$row->confirmed			= 0;
	
			if ( !$row->store() )
			{
				JError::raiseError(500, $row->getError());
			}
		}	
	
	}
	
	function updateTableQRTrack($idTrack)
	{
		$db = JFactory::getDBO();
	
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_alphauserpoints'.DS.'tables');
		$row = JTable::getInstance('qrcodetrack');
		$row->load( intval($idTrack) );
		$row->confirmed = 1;
		$db->updateObject( '#__alpha_userpoints_qrcodetrack', $row, 'id' );	
	
	}

}
?>