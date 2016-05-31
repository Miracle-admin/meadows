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

jimport('joomla.application.component.modeladmin');

/**
 * Vm2wishlists model.
 */
class Vm2wishlistsModelPluginize extends JModelAdmin
{
	public function getPluginize()
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$q = "SELECT virtuemart_custom_id FROM #__virtuemart_customs WHERE custom_element='vm2wishlists' ";
		$db->setQuery($q);
		$virtuemart_custom_id = $db->loadResult();
		if(!$virtuemart_custom_id)
		{
			$app->enqueueMessage('You must install and set up plg_vmcustom_vm2wishlists as a Virtuemart custom field first.' ,'warning'	);
			return false;
		}
		else
		{
			$q ="SELECT virtuemart_product_id FROM #__virtuemart_products ";
			$db->setQuery($q);
			$products = $db->loadObjectList();
			$i = 0;
			foreach($products as $product)
			{
				$q = "INSERT INTO #__virtuemart_product_customfields
				(virtuemart_product_id , virtuemart_custom_id, customfield_value)
				VALUES
				('".$product->virtuemart_product_id."', '".$virtuemart_custom_id."', 'vm2wishlists')";	
				$db->setQuery($q);
				if (!$db->query()) die($db->stderr(true));
				$i++;
			}
			$app->enqueueMessage($i.' products got the vm2wishlists custom field set' );	
		}
	}
}