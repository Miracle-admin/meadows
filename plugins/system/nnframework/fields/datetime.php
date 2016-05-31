<?php
/**
 * Element: DateTime
 * Element to display the date and time
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

require_once JPATH_PLUGINS . '/system/nnframework/helpers/field.php';

class JFormFieldNN_DateTime extends NNFormField
{
	public $type = 'DateTime';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$label  = $this->get('label');
		$format = $this->get('format');

		$date = JFactory::getDate();

		$tz = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$date->setTimeZone($tz);

		if ($format)
		{
			if (strpos($format, '%') !== false)
			{
				require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';
				$format = NNText::dateToDateFormat($format);
			}
			$html = $date->format($format, true);
		}
		else
		{
			$html = $date->format('', true);
		}

		if ($label)
		{
			$html = JText::sprintf($label, $html);
		}

		return '</div><div>' . $html;
	}
}
