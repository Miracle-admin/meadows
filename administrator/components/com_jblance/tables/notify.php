<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 September 2012
 * @file name	:	tables/notify.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die( 'Restricted access' );

class TableNotify extends JTable {
	var $id = null;
	var $user_id = null;
	var $frequency = null;
	var $notifyNewProject = null;
	var $notifyBidWon = null;
	var $notifyNewMessage = null;
		
	var $notifyBidNewAcceptDeny = null; 	
	var $notifyNewForumMessage = null; 	
	var $notifyDeveloperRecommendation = null; 	
	var $notifyProjectComment = null; 	
	var $notifyPaymentTransaction = null; 
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct( '#__jblance_notify', 'id', $db);
	}
	
	function checkUserExist($user)
	{
		$db	= JFactory::getDbo();
	
		$query = "SELECT count(*) as usercount, id FROM #__jblance_notify WHERE user_id=".$db->quote($user);
		$db->setQuery($query);
		$result	= $db->loadObject();
		return $result;
	}
	
	function UserNotifyUpdate($post)
	{
		$db	= JFactory::getDbo();
		$user 	= JFactory::getUser();
		//echo "<pre>"; print_r($post); die;
		$notifyBidNewAcceptDeny			=	$post['notifyBidNewAcceptDeny'];
		$notifyDeveloperRecommendation	=	$post['notifyDeveloperRecommendation']; 
		$notifyNewMessage				=	$post['notifyNewMessage'];
		$notifyPaymentTransaction		=	$post['notifyPaymentTransaction'];
	
		$query = "update #__jblance_notify set notifyBidNewAcceptDeny = ".$notifyBidNewAcceptDeny.", notifyDeveloperRecommendation = ".$notifyDeveloperRecommendation.", notifyNewMessage = ".$notifyNewMessage.", notifyPaymentTransaction = ".$notifyPaymentTransaction."  WHERE user_id=".$db->quote($user->id);
		
		$db->setQuery($query);
		$result	= $db->execute();
		return $result;
	}
}
?>