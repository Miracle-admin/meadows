<?php
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2015 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : http://www.nordmograph.com/extensions
 */

defined('_JEXEC') or die;
abstract class VmvendorHelper
{
	static function getVendorPlan( $userid )
	{
		$db						= JFactory::getDBO();
		$q = "SELECT vp.commission_pct , vp.autopublish , vp.max_products , vp.max_img, vp.max_files , ug.title
		
		 FROM #__vmvendor_plans vp 
		 LEFT JOIN #__user_usergroup_map uum ON uum.group_id = vp.jusergroups AND uum.user_id= '".$userid."' 
		 LEFT JOIN #__usergroups ug ON ug.id =  uum.group_id ";
		$db->setQuery($q);
		$vendor_plan = $db->LoadObject();
		if($userid>0)
			return $vendor_plan;
	}
	static function countVendorProducts( $userid )
	{
		$db						= JFactory::getDBO();
		$q = "SELECT COUNT(*) FROM #__virtuemart_products vp 
		JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id AND vv.virtuemart_user_id = '".$userid."' ";
		$db->setQuery($q);
		$count = $db->LoadResult();
		return $count;
	}
	
}