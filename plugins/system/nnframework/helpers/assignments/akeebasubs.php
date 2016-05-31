<?php
/**
 * NoNumber Framework Helper File: Assignments: AkeebaSubs
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

class NNFrameworkAssignmentsAkeebaSubs extends NNFrameworkAssignment
{
	function init()
	{
		if (!$this->request->id && $this->request->view == 'level')
		{
			$slug = JFactory::getApplication()->input->getString('slug', '');
			if ($slug)
			{
				$query = $this->db->getQuery(true)
					->select('l.akeebasubs_level_id')
					->from('#__akeebasubs_levels AS l')
					->where('l.slug = ' . $this->db->quote($slug));
				$this->db->setQuery($query);
				$this->request->id = $this->db->loadResult();
			}
		}
	}

	function passPageTypes()
	{
		return $this->passByPageTypes('com_akeebasubs', $this->selection, $this->assignment);
	}

	function passLevels()
	{
		if (!$this->request->id || $this->request->option != 'com_akeebasubs' || $this->request->view != 'level')
		{
			return $this->pass(false);
		}

		return $this->passSimple($this->request->id);
	}
}
