<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
class VmvendorRouter extends JComponentRouterBase
{
	public function build(&$query)
	{
		$segments = array();
		// Get a menu item based on Itemid or currently active
		$app 			= JFactory::getApplication();
		$menu			= $app->getMenu();
		$params 		= JComponentHelper::getParams('com_vmvendor');
		$advanced 		= $params->get('sef_advanced_link', 0);
		$vendor_filter	= $params->get('vendor_filter', 0);
	
		
		if (!class_exists( 'VmConfig' ))
				require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
	
		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
		}

		if(isset($query['view']))
		{
			if(empty($query['Itemid']))
			{
				$segments[] = $query['view'];
			}
			
			if($query['view'] == 'vendorprofile' 
				|| $query['view'] == 'dashboard' 
				|| $query['view'] == 'editprofile'  
				|| $query['view'] == 'editproduct' 
				|| $query['view'] == 'addproduct'  
				|| $query['view'] == 'askvendor'  
				|| $query['view'] == 'mailcustomer'   
				|| $query['view'] == 'edittax' 
				|| $query['view'] == 'editshipment'  
				|| $query['view'] == 'catsuggest'
				|| $query['view'] == 'profilevisits'
			)
			{
				$segments[] = $query['view'];
			}
	
			unset($query['view']);
		}

		if(isset($query['userid']))
		{			
			 $db		  =  JFactory::getDBO();
			 $sqlQuery = "SELECT vvl.`vendor_store_name` 
			FROM `#__virtuemart_vendors_".VMLANG."` AS vvl
			 LEFT JOIN `#__virtuemart_vmusers` vvu ON vvu.`virtuemart_vendor_id` = vvl.`virtuemart_vendor_id` 
			 WHERE vvu.`virtuemart_user_id` ='".$query['userid']."' ";
			$db->setQuery($sqlQuery);				
			$segments[] = urlencode($query['userid'].'-'.$db->loadResult());		
			unset($query['userid']);
		}
		
		return $segments;
	}

	public function parse(&$segments)
	{
		$vars = array();
		// Count route segments
		$count = count($segments);	
		if ( $count )
		{
			if($segments[0] == 'vendorprofile')
			{
				$vars['view'] = 'vendorprofile';
				$vendor_store_name = urldecode($segments[$count-1]);
				$strpos = strpos( $vendor_store_name , '-');
				$userid = substr($vendor_store_name, 0,$strpos );
				$vars['userid'] =$userid;
				return $vars;
			}
			elseif($segments[0] == 'editprofile')
			{
				$vars['view'] = 'editprofile';
				return $vars;
			}
			elseif($segments[0] == 'addproduct')
			{
				$vars['view'] = 'addproduct';
				return $vars;
			}
			elseif($segments[0] == 'editproduct')
			{
				$vars['view'] = 'editproduct';
				return $vars;
			}
			elseif($segments[0] == 'dashboard')
			{
				$vars['view'] = 'dashboard';
				return $vars;
			}
			elseif($segments[0] == 'askvendor')
			{
				$vars['view'] = 'askvendor';
				return $vars;
			}
			elseif($segments[0] == 'mailcustomer')
			{
				$vars['view'] = 'mailcustomer';
				return $vars;
			}
			elseif($segments[0] == 'edittax')
			{
				$vars['view'] = 'edittax';
				return $vars;
			}
			elseif($segments[0] == 'editshipment')
			{
				$vars['view'] = 'editshipment';
				return $vars;
			}
			elseif($segments[0] == 'catsuggest')
			{
				$vars['view'] = 'catsuggest';
				return $vars;
			}
			elseif($segments[0] == 'profilevisits')
			{
				$vars['view'] = 'profilevisits';
				return $vars;
			}
		}
	}
}
?>