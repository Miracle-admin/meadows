<?php
/**
 * NoNumber Framework Helper File: Assignments: Menu
 *
 * @package         NoNumber Framework
 * @version         15.11.2132
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2015 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . '/system/nnframework/helpers/assignment.php';

class NNFrameworkAssignmentsMenu extends NNFrameworkAssignment
{
	function passMenu()
	{
		// return if no Itemid or selection is set
		if (!$this->request->Itemid || empty($this->selection))
		{
			return $this->pass($this->params->inc_noitemid);
		}

		$menutype = 'type.' . self::getMenuType();

		// return true if menu type is in selection
		if (in_array($menutype, $this->selection))
		{
			return $this->pass(true);
		}

		// return true if menu is in selection
		if (in_array($this->request->Itemid, $this->selection))
		{
			return $this->pass(($this->params->inc_children != 2));
		}

		if (!$this->params->inc_children)
		{
			return $this->pass(false);
		}

		$parent_ids = $this->getMenuParentIds($this->request->Itemid);
		$parent_ids = array_diff($parent_ids, array('1'));
		foreach ($parent_ids as $id)
		{
			if (!in_array($id, $this->selection))
			{
				continue;
			}

			return $this->pass(true);
		}

		return $this->pass(false);
	}

	function getMenuParentIds($id = 0)
	{
		return $this->getParentIds($id, 'menu');
	}

	function getMenuType()
	{
		if (isset($this->request->menutype))
		{
			return $this->request->menutype;
		}

		$query = $this->db->getQuery(true)
			->select('m.menutype')
			->from('#__menu AS m')
			->where('m.id = ' . (int) $this->request->Itemid);
		$this->db->setQuery($query);
		$this->request->menutype = $this->db->loadResult();

		return $this->request->menutype;
	}
}
