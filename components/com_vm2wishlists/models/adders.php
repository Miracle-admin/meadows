<?php

/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Vm2wishlists model.
 */
class Vm2wishlistsModelAdders extends JModelItem {

 

    public function getAdders()
	{
        $app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$doc = JFactory::getDocument();
		$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
		$profileman 			= $cparams->get('profileman', '0');
		$limit 			= $cparams->get('limit', '50');
		$limitstart = $app->input->getInt('limitstart', 0);
		$listid = $app->input->getInt('listid');
		$productid = $app->input->getInt('productid');
		
        $q = "SELECT vi.userid , vi.date_added ,
		u.name, u.username ";
		if($profileman=='js')
			$q .= " ,cu.thumb ";
		if($profileman=='cb')
			$q .= " ,c.avatar AS thumb , c.avatarapproved  ";
		// we'll use EasySocial API on view for ES names and avatars
		$q .= " FROM #__vm2wishlists_items vi 
		JOIN #__users u ON u.id = vi.userid ";
		if($profileman=='js')
			$q .= " JOIN #__community_users AS cu ON cu.userid = vi.userid ";
		if($profileman=='cb')
			$q .= " JOIN #__comprofiler AS c ON c.user_id = vi.userid ";

		$q .= " WHERE vi.listid='".$listid."' 
		AND vi.virtuemart_product_id='".$productid."' 
		ORDER BY vi.date_added DESC ";
        $db->setQuery($q);
		$total = @$this->_getListCount($q);
		$adders = $this->_getList($q, $limitstart, $limit);
		return array($adders, $total, $limit, $limitstart);
    }
}
