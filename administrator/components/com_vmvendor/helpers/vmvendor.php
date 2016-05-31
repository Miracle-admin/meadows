<?php

/**
 * @version     1.0.0
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2015. All rights rserved
 * @license     GNU General Public License version 3 ; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Vmvendor helper.
 */
class VmvendorHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_VMVENDOR_TITLE_PLANS'),
			'index.php?option=com_vmvendor&view=plans',
			$vName == 'plans'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_VMVENDOR_TITLE_POINTSACTIVITIES'),
			'index.php?option=com_vmvendor&view=pointsactivities',
			$vName == 'pointsactivities'
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

        $assetName = 'com_vmvendor';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

	/**
	* Get group name using group ID
	* @param integer $group_id Usergroup ID
	* @return mixed group name if the group was found, null otherwise
	*/
	public static function getGroupNameByGroupId($group_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__usergroups')
			->where('id = ' . intval($group_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
}
