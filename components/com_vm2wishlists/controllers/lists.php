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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Lists list controller class.
 */
class Vm2wishlistsControllerLists extends Vm2wishlistsController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Lists', $prefix = 'Vm2wishlistsModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}