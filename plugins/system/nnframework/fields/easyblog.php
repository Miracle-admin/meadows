<?php
/**
 * Element: EasyBlog
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

require_once JPATH_PLUGINS . '/system/nnframework/helpers/groupfield.php';

class JFormFieldNN_EasyBlog extends NNFormGroupField
{
	public $type = 'EasyBlog';

	protected function getInput()
	{
		if ($error = $this->missingFilesOrTables(array('categories' => 'category', 'items' => 'post', 'tags' => 'tag')))
		{
			return $error;
		}

		return $this->getSelectList();
	}

	function getCategories()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__easyblog_category AS c')
			->where('c.published > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('c.id, c.parent_id, c.title, c.published')
			->order('c.ordering, c.title');
		$this->db->setQuery($query);
		$items = $this->db->loadObjectList();

		return $this->getOptionsTreeByList($items);
	}

	function getItems()
	{
		$query = $this->db->getQuery(true)
			->select('i.id, i.title as name, c.title as cat, i.published')
			->from('#__easyblog_post AS i')
			->join('LEFT', '#__easyblog_category AS c ON c.id = i.category_id')
			->where('i.published > -1')
			->order('i.title, c.title, i.id');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list, array('cat', 'id'));
	}

	function getTags()
	{
		$query = $this->db->getQuery(true)
			->select('t.alias as id, t.title as name')
			->from('#__easyblog_tag AS t')
			->where('t.published > -1')
			->order('t.title');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list);
	}
}
