<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL
 * @Website : http://www.nordmograph.com
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class VmvendorModelVendorprofile_Rating extends JModelItem
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	 
	public function getVendorRating($vendor_user_id) 
	{
		$db = JFactory::getDBO();
		$vendor_rating = array();
		$q = "SELECT percent FROM #__vmvendor_vendorratings WHERE vendor_user_id = '".$vendor_user_id."' AND percent >0 ";
		$db->setQuery($q);
		$votes = $db->loadObjectList();
		$votes_count = count($votes);
		$total_pct = 0;
		if(count($votes))
		{
			foreach($votes as $vote)
			{
				$total_pct = $total_pct + $vote->percent;
			}
			if($votes_count)
				$average_percent = $total_pct / $votes_count;
			$vendor_rating['count'] = $votes_count;
			$vendor_rating['percent'] = $average_percent;
		}
		else
			$vendor_rating['count'] = 0;
			$vendor_rating['percent'] = 0;
		
		return $vendor_rating;
	}
	
	
	function getratevendor()
	{
		//rategame$user 			= JFactory::getUser();
		$db 			= JFactory::getDBO();
		$doc 			= JFactory::getDocument();
		$juri 			= JURI::base();
		$app 			= JFactory::getApplication();
		$user 			= JFactory::getUser();
		$vendor_user_id		= $app->input->get('vendor_user_id');
		$rating				= $app->input->get('rating');
		
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$rating_stars				= $cparams->get('rating_stars','5');
		$purchasetorate				= $cparams->get('purchasetorate',1);
		$process=1;
		if($vendor_user_id == $user->id)
		{
			$message = '<div style="color:red;" ><span class="vmv-icon-warning"></span> '.JText::_('COM_VMVENDOR_PROFILERATING_NOSELF').'</div>';
			$process=0;;
		}
		elseif($user->id==0)
		{
			$message = '<div style="color:red;" ><span class="vmv-icon-warning"></span> '.JText::_('COM_VMVENDOR_ONLYCUSTOMERS').'</div>';
			$process=0;;
		}
		elseif($purchasetorate)
		{
		// check if voter is a customer
			$q = " SELECT voi.virtuemart_product_id 
					FROM #__virtuemart_order_items voi 
					LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_product_id = voi.virtuemart_product_id 
					LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
					WHERE voi.created_by = '".$user->id."' 
					AND ( voi.order_status ='C' OR  voi.order_status ='S') 
					AND vv.virtuemart_user_id='".$vendor_user_id."' ";
			$db->setQuery($q);
			$purchases_from_thisvendor = $db->loadObjectList();
			if(count($purchases_from_thisvendor)<1 ){
				$process = 0;
				$message = '<div style="color:red;" ><span class="vmv-icon-warning"></span> '.JText::_('COM_VMVENDOR_PROFILERATING_NOTYET').'</div>';
			}
		}
		//&& $ratind >0
		if($process )
		{
			$percent = ($rating * 100 ) / $rating_stars;			
			$q ="SELECT COUNT(*) FROM #__vmvendor_vendorratings WHERE vendor_user_id='".$vendor_user_id."' AND voter_user_id='".$user->id."' ";
			$db->setQuery($q);
			$yet_rated = $db->loadResult();
			if($yet_rated>0)
			{
				//UPDATE ?
				$q = "UPDATE #__vmvendor_vendorratings SET percent='".$percent."' WHERE vendor_user_id='".$vendor_user_id."' AND voter_user_id='".$user->id."' ";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$message = '<div style="color:green;" ><span class="vmv-icon-ok"></span> '.JText::_('COM_VMVENDOR_PROFILERATING_UPDATING').'</div>';
			}
			else
			{ 
				 // INSERT rating
				$q ="INSERT INTO #__vmvendor_vendorratings (vendor_user_id , percent , voter_user_id )
				VALUES ('".$vendor_user_id."' ,'".$percent."' ,'".$user->id."' )";
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$message = '<div style="color:green;" ><span class="vmv-icon-ok"></span> '.JText::_('COM_VMVENDOR_PROFILERATING_THANKYOU').'</div>';	
			}
			
		}
	
		
		$vendor_rating = $this->getVendorRating($vendor_user_id);
		
		$icount= 0;
		if($vendor_rating['count']>0)
			$icount = $vendor_rating['count'];
		echo '<div>'.JText::_('COM_VMVENDOR_PROFILE_RATING').': '.number_format( ( $vendor_rating['percent'] * $rating_stars)/100 ,2,'.','') .' - '.JText::_('COM_VMVENDOR_PROFILE_VOTES').': '.$icount.'</div>';
		echo $message;
	}
}
?>