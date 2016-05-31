<?php
/**
 * Element: Slide
 * Element to create a new slide pane
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

class JFormFieldNN_Slide extends NNFormField
{
	public $type = 'Slide';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		JHtml::stylesheet('nnframework/style.min.css', false, true);

		$label       = NNText::html_entity_decoder(JText::_($this->get('label')));
		$description = $this->prepareText($this->get('description'));
		$lang_file   = $this->get('language_file');

		$html = '</td></tr></table></div></div>';
		$html .= '<div class="panel"><h3 class="jpane-toggler title" id="advanced-page"><span>';
		$html .= $label;
		$html .= '</span></h3>';
		$html .= '<div class="jpane-slider content"><table width="100%" class="paramlist admintable" cellspacing="1"><tr><td colspan="2" class="paramlist_value">';

		if ($lang_file)
		{
			jimport('joomla.filesystem.file');

			// Include extra language file
			$lang = str_replace('_', '-', JFactory::getLanguage()->getTag());

			$inc       = '';
			$lang_path = 'language/' . $lang . '/' . $lang . '.' . $lang_file . '.inc.php';
			if (JFile::exists(JPATH_ADMINISTRATOR . '/' . $lang_path))
			{
				$inc = JPATH_ADMINISTRATOR . '/' . $lang_path;
			}
			else if (JFile::exists(JPATH_SITE . '/' . $lang_path))
			{
				$inc = JPATH_SITE . '/' . $lang_path;
			}
			if (!$inc && $lang != 'en-GB')
			{
				$lang      = 'en-GB';
				$lang_path = 'language/' . $lang . '/' . $lang . '.' . $lang_file . '.inc.php';
				if (JFile::exists(JPATH_ADMINISTRATOR . '/' . $lang_path))
				{
					$inc = JPATH_ADMINISTRATOR . '/' . $lang_path;
				}
				else if (JFile::exists(JPATH_SITE . '/' . $lang_path))
				{
					$inc = JPATH_SITE . '/' . $lang_path;
				}
			}
			if ($inc)
			{
				include $inc;
			}
		}

		if ($description)
		{
			if ($description['0'] != '<')
			{
				$description = '<p>' . $description . '</p>';
			}
			$class = 'nn_panel nn_panel_description';
			$html .= '<div class="' . $class . '"><div class="nn_block nn_title">';
			$html .= $description;
			$html .= '<div style="clear: both;"></div></div></div>';
		}

		return $html;
	}
}
