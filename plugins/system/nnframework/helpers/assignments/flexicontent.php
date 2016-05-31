<?php
/**
 * NoNumber Framework Helper File: Assignments: FlexiContent
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

class NNFrameworkAssignmentsFlexiContent extends NNFrameworkAssignment
{
	function passPageTypes()
	{
		return $this->passByPageTypes('com_flexicontent', $this->selection, $this->assignment);
	}

	function passTags()
	{
		if ($this->request->option != 'com_flexicontent')
		{
			return $this->pass(false);
		}

		$pass = (
			($this->params->inc_tags && $this->request->view == 'tags')
			|| ($this->params->inc_items && in_array($this->request->view, array('item', 'items')))
		);

		if (!$pass)
		{
			return $this->pass(false);
		}

		if ($this->params->inc_tags && $this->request->view == 'tags')
		{
			$query = $this->db->getQuery(true)
				->select('t.name')
				->from('#__flexicontent_tags AS t')
				->where('t.id = ' . (int) trim(JFactory::getApplication()->input->getInt('id', 0)))
				->where('t.published = 1');
			$this->db->setQuery($query);
			$tag  = $this->db->loadResult();
			$tags = array($tag);
		}
		else
		{
			$query = $this->db->getQuery(true)
				->select('t.name')
				->from('#__flexicontent_tags_item_relations AS x')
				->join('LEFT', '#__flexicontent_tags AS t ON t.id = x.tid')
				->where('x.itemid = ' . (int) $this->request->id)
				->where('t.published = 1');
			$this->db->setQuery($query);
			$tags = $this->db->loadColumn();
		}

		return $this->passSimple($tags, true);
	}

	function passTypes()
	{
		if ($this->request->option != 'com_flexicontent')
		{
			return $this->pass(false);
		}

		$pass = in_array($this->request->view, array('item', 'items'));

		if (!$pass)
		{
			return $this->pass(false);
		}

		$query = $this->db->getQuery(true)
			->select('x.type_id')
			->from('#__flexicontent_items_ext AS x')
			->where('x.item_id = ' . (int) $this->request->id);
		$this->db->setQuery($query);
		$type = $this->db->loadResult();

		$types = $this->makeArray($type, 1);

		return $this->passSimple($types);
	}

	function getCatParentIds($id = 0)
	{
		return $this->getParentIds($id, 'categories', 'parent_id');
	}
}
