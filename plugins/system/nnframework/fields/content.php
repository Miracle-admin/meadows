<?php
/**
 * Element: Content
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

class JFormFieldNN_Content extends NNFormGroupField
{
	public $type = 'Content';

	function getCategories()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__categories AS c')
			->where('c.extension = ' . $this->db->quote('com_content'))
			->where('c.parent_id > 0')
			->where('c.published > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		// assemble items to the array
		$options = array();
		if ($this->get('show_ignore'))
		{
			if (in_array('-1', $this->value))
			{
				$this->value = array('-1');
			}
			$options[] = JHtml::_('select.option', '-1', '- ' . JText::_('NN_IGNORE') . ' -', 'value', 'text', 0);
			$options[] = JHtml::_('select.option', '-', '&nbsp;', 'value', 'text', 1);
		}

		$query->clear('select')
			->select('c.id, c.title as name, c.level, c.published, c.language')
			->order('c.lft');

		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		$options = array_merge($options, $this->getOptionsByList($list, array('language'), -1));

		return $options;
	}

	function getItems()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__content AS i')
			->where('i.access > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('i.id, i.title as name, i.language, c.title as cat, i.access as published')
			->join('LEFT', '#__categories AS c ON c.id = i.catid')
			->order('i.title, i.ordering, i.id');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list, array('language', 'cat', 'id'));
	}
}
