<?php

/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Vm2wishlists helper.
 */
class Vm2wishlistsHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_VM2WISHLISTS_TITLE_LISTS'),
			'index.php?option=com_vm2wishlists&view=lists',
			$vName == 'lists'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_vm2wishlists';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
