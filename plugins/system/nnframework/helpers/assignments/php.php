<?php
/**
 * NoNumber Framework Helper File: Assignments: PHP
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

class NNFrameworkAssignmentsPHP extends NNFrameworkAssignment
{
	function passPHP()
	{
		$article = $this->article;

		if (!is_array($this->selection))
		{
			$this->selection = array($this->selection);
		}

		$pass = false;
		foreach ($this->selection as $php)
		{
			// replace \n with newline and other fix stuff
			$php = str_replace('\|', '|', $php);
			$php = preg_replace('#(?<!\\\)\\\n#', "\n", $php);
			$php = trim(str_replace('[:REGEX_ENTER:]', '\n', $php));

			if ($php == '')
			{
				$pass = true;
				break;
			}

			if (!$article && strpos($php, '$article') !== false)
			{
				$article = '';
				if ($this->request->option == 'com_content' && $this->request->view == 'article')
				{
					require_once JPATH_SITE . '/components/com_content/models/article.php';
					$model   = JModelLegacy::getInstance('article', 'contentModel');
					$article = $model->getItem($this->request->id);
				}
			}
			if (!isset($Itemid))
			{
				$Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);
			}
			if (!isset($mainframe))
			{
				$mainframe = JFactory::getApplication();
			}
			if (!isset($app))
			{
				$app = JFactory::getApplication();
			}
			if (!isset($document))
			{
				$document = JFactory::getDocument();
			}
			if (!isset($doc))
			{
				$doc = JFactory::getDocument();
			}
			if (!isset($database))
			{
				$database = JFactory::getDbo();
			}
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			if (!isset($user))
			{
				$user = JFactory::getUser();
			}
			$php .= ';return true;';

			$temp_PHP_func = create_function('&$article, &$Itemid, &$mainframe, &$app, &$document, &$doc, &$database, &$db, &$user', $php);

			// evaluate the script
			ob_start();
			$pass = (bool) $temp_PHP_func($article, $Itemid, $mainframe, $app, $document, $doc, $database, $db, $user);
			unset($temp_PHP_func);
			ob_end_clean();

			if ($pass)
			{
				break;
			}
		}

		return $this->pass($pass);
	}
}
