<?php
/**
 * Element: Editor
 * Displays an HTML editor text field
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

class JFormFieldNN_Editor extends NNFormField
{
	public $type = 'Editor';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$width  = $this->get('width', '100%');
		$height = $this->get('height', 400);

		$this->value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');

		// Get an editor object.
		$editor = JFactory::getEditor();
		$html   = $editor->display($this->name, $this->value, $width, $height, true, $this->id);

		return '</div><div>' . $html;
	}
}
