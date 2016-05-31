<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
$juri 			= JURI::base();
$doc = JFactory::getDocument();
$doc->addStyleSheet($juri.'modules/mod_vmvendors/css/style.css');
$user 			= JFactory::getUser();

$db 			= JFactory::getDBO();
$limit 			= trim( $params->get('limit', '10') );
$profileman 	= $params->get('profileman','0');
$cparams 		= JComponentHelper::getParams('com_vmvendor');
$naming 		= $cparams->get('naming');
$rating_stars	= $cparams->get('rating_stars','5');
$profile_itemid	= $cparams->get('profileitemid');

$showpic 	= trim( $params->get('showpic', 1) );
$show_rating = trim( $params->get('show_rating', 1) );

if( $show_rating)
	echo '<link rel="stylesheet" href="'.JURI::base().'components/com_vmvendor/assets/css/fontello.css">';

if (count($vendors)>0){
	echo '<div style="text-align:right;">'.JText::_('MODVMVEN_SORTEDBY').' <b>';
			
	if ($sortby ==1)
		echo JText::_('MODVMVEN_LATESTVEN');
	elseif($sortby ==2)
		echo JText::_('MODVMVEN_RANDOMVEN');
	elseif($sortby ==3)
		echo JText::_('MODVMVEN_LARGESTCAT');
	elseif($sortby ==4)
		echo JText::_('MODVMVEN_TOPRATED');
	echo '</b></div>';
	echo '<table class="table table-striped table-condensed table-hover category">';
	$i=0;
	$z = 0;
	foreach ($vendors as $vendor){
		$z++;
		if($profileman=='0')
		{
			$profile_itemid = 	ModVMVendorsHelper::getVendorprofileItemid();
			$vendor_url = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$vendor->virtuemart_user_id.'&Itemid='.$profile_itemid);

		}
		elseif($profileman =='cb')
			$vendor_url = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user='.$vendor->virtuemart_user_id.'&Itemid='.$profile_itemid);
		elseif($profileman =='js')
			$vendor_url = JRoute::_('index.php?option=com_community&view=profile&userid='.$vendor->virtuemart_user_id.'&Itemid='.$profile_itemid);
		elseif($profileman =='es')
			$vendor_url = JRoute::_('index.php?option=com_easysocial&view=profile&id='.$vendor->virtuemart_user_id.'&Itemid='.$profile_itemid);
		
		$i++;
		if ($i>2)
			$i=1;
			
		echo '<tr >';
		if($showpic)
		{
			echo '<td width="50px">';			
			if($profileman =='0'){
				if($vendor->avatar)
					$src= $juri.$vendor->avatar;
				else
					$src= $juri.'components/com_vmvendor/assets/img/noimage.gif';
			}
			elseif($profileman =='cb'){
				if($vendor->avatar)
					$src= $juri.'/images/comprofiler/'.$vendor->avatar;
				else
					$src= $juri.'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';
			}
			elseif($profileman =='js'){
				if($vendor->avatar)
					$src= $juri.$vendor->avatar;
				else
					$src= $juri.'components/com_community/assets/user_thumb.png';
			}
			elseif($profileman =='es'){
				if($vendor->avatar)
					$src= $juri.'media/com_easysocial/avatars/users/'.$vendor->virtuemart_user_id.'/'.$vendor->avatar;
				else
					$src= $juri.'media/com_easysocial/defaults/avatars/user/medium.png';
					//http://localhost/vmvendor/media/com_easysocial/avatars/users/967/b2032da7279b59dea3d43cbad4fdc121_medium.jpg
			}
				
				
				
				echo '<a href="'.$vendor_url.'" >';
				if($profileman =='0' OR $profileman =='cb')
					echo '<img src="'.$src.'" alt="'.$vendor->vendor_name.'" height="50" class="vmvborder"/>';
				else
					echo '<img src="'.$src.'" alt="'.$vendor->vendor_name.'" height="50"  width="50" class="img-circle vmvborder" />';
		
			echo '</a></td>';
		}
		echo '<td><div class="vendor_title">';
		
			echo '<a href="'.$vendor_url.'" ><span>'.ucfirst($vendor->vendor_name).'</span>';
			
			if($show_rating){
			$vendor_rating = ModVMVendorsHelper::getVendorRating($vendor->virtuemart_user_id);
			//$doc->addStyleSheet($juri.'components/com_vmvendor/assets/css/jquery.rating.css');
			$votes_count = $vendor_rating['count']  ;
			$average_percent= $vendor_rating['percent']  ;
			$stars = ($average_percent * $rating_stars)/100 ;
			echo '<div class="vendor_rating hasTooltip" title="'.$stars.'/'.$rating_stars.'" ><a href="'.$vendor_url.'" >';
			for ($i=1;$i<= $stars;$i++){
					echo '<i class="vmv-icon-star"></i>';	
			}
			if(!is_int($stars)){
					echo '<i class="vmv-icon-star-half"></i>';
			}
			for ($j=1;$j<= $rating_stars - $stars ; $j++){
					echo '<i class="vmv-icon-star-empty"></i>';	
			}
			echo '<a></div>';	
			
		}
		
			
		echo '</a><i><b>'.$vendor->count.'</b> ';
		if ($vendor->count<2)
			echo JText::_('MODVMVEN_ITEM');
		else
			echo JText::_('MODVMVEN_ITEMS');
		
		
		echo'</i></div>';
		
			
		echo '</td>';
		
		echo '<td><div class="badge " >'.$z.'</badge>';
		echo '</td>';

		echo '</tr>';
	}
	echo '</table>';
}
else
	echo JText::_('MODVMVEN_NOVENDOR');
?>