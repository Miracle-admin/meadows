<?php
/**
 * NoNumber Framework Helper File: Assignments
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

require_once __DIR__ . '/cache.php';
require_once __DIR__ . '/functions.php';

/**
 * Assignments
 * $assignment = no / include / exclude / none
 */
class NNFrameworkAssignmentsHelper
{
	var $db = null;
	var $params = null;
	var $init = false;
	var $types = array();
	var $maintype = '';
	var $subtype = '';

	public function __construct()
	{
		$this->db = JFactory::getDbo();

		$this->has                  = array();
		$this->has['easyblog']      = NNFrameworkFunctions::extensionInstalled('easyblog');
		$this->has['flexicontent']  = NNFrameworkFunctions::extensionInstalled('flexicontent');
		$this->has['form2content']  = NNFrameworkFunctions::extensionInstalled('form2content');
		$this->has['k2']            = NNFrameworkFunctions::extensionInstalled('k2');
		$this->has['zoo']           = NNFrameworkFunctions::extensionInstalled('zoo');
		$this->has['akeebasubs']    = NNFrameworkFunctions::extensionInstalled('akeebasubs');
		$this->has['hikashop']      = NNFrameworkFunctions::extensionInstalled('hikashop');
		$this->has['mijoshop']      = NNFrameworkFunctions::extensionInstalled('mijoshop');
		$this->has['redshop']       = NNFrameworkFunctions::extensionInstalled('redshop');
		$this->has['virtuemart']    = NNFrameworkFunctions::extensionInstalled('virtuemart');
		$this->has['cookieconfirm'] = NNFrameworkFunctions::extensionInstalled('cookieconfirm');

		$this->types      = array(
			'menuitems'             => 'Menu',
			'homepage'              => 'HomePage',
			'date'                  => 'DateTime.Date',
			'seasons'               => 'DateTime.Seasons',
			'months'                => 'DateTime.Months',
			'days'                  => 'DateTime.Days',
			'time'                  => 'DateTime.Time',
			'usergrouplevels'       => 'Users.UserGroupLevels',
			'users'                 => 'Users.Users',
			'languages'             => 'Languages',
			'ips'                   => 'IPs',
			'geocontinents'         => 'Geo.Continents',
			'geocountries'          => 'Geo.Countries',
			'georegions'            => 'Geo.Regions',
			'geopostalcodes'        => 'Geo.Postalcodes',
			'templates'             => 'Templates',
			'urls'                  => 'URLs',
			'os'                    => 'Agents.OS',
			'browsers'              => 'Agents.Browsers',
			'components'            => 'Components',
			'tags'                  => 'Tags',
			'contentpagetypes'      => 'Content.PageTypes',
			'cats'                  => 'Content.Categories',
			'articles'              => 'Content.Articles',
			'easyblogpagetypes'     => 'EasyBlog.PageTypes',
			'easyblogcats'          => 'EasyBlog.Categories',
			'easyblogtags'          => 'EasyBlog.Tags',
			'easyblogitems'         => 'EasyBlog.Items',
			'flexicontentpagetypes' => 'FlexiContent.PageTypes',
			'flexicontenttags'      => 'FlexiContent.Tags',
			'flexicontenttypes'     => 'FlexiContent.Types',
			'form2contentprojects'  => 'Form2Content.Projects',
			'k2pagetypes'           => 'K2.PageTypes',
			'k2cats'                => 'K2.Categories',
			'k2tags'                => 'K2.Tags',
			'k2items'               => 'K2.Items',
			'zoopagetypes'          => 'Zoo.PageTypes',
			'zoocats'               => 'Zoo.Categories',
			'zooitems'              => 'Zoo.Items',
			'akeebasubspagetypes'   => 'AkeebaSubs.PageTypes',
			'akeebasubslevels'      => 'AkeebaSubs.Levels',
			'hikashoppagetypes'     => 'HikaShop.PageTypes',
			'hikashopcats'          => 'HikaShop.Categories',
			'hikashopproducts'      => 'HikaShop.Products',
			'mijoshoppagetypes'     => 'MijoShop.PageTypes',
			'mijoshopcats'          => 'MijoShop.Categories',
			'mijoshopproducts'      => 'MijoShop.Products',
			'redshoppagetypes'      => 'RedShop.PageTypes',
			'redshopcats'           => 'RedShop.Categories',
			'redshopproducts'       => 'RedShop.Products',
			'virtuemartpagetypes'   => 'VirtueMart.PageTypes',
			'virtuemartcats'        => 'VirtueMart.Categories',
			'virtuemartproducts'    => 'VirtueMart.Products',
			'cookieconfirm'         => 'CookieConfirm',
			'php'                   => 'PHP',
		);
		$this->thirdparty = array(
			'EasyBlog',
			'FlexiContent',
			'Form2Content',
			'K2',
			'Zoo',
			'AkeebaSubs',
			'HikaShop',
			'MijoShop',
			'RedShop',
			'VirtueMart',
			'CookieConfirm',
		);
		$this->nonarray   = array(
			'PHP',
		);

		$this->setIdNames();

		$this->classes = array();
	}

	function setIdNames()
	{
		$this->names = array();

		foreach ($this->types as $type)
		{
			$type                                = explode('.', $type, 2);
			$this->names[strtolower($type['0'])] = $type['0'];
			if (isset($type['1']))
			{
				$this->names[strtolower($type['1'])] = $type['1'];
			}
		}

		$this->names['menuitems'] = 'Menu';
		$this->names['cats']      = 'Categories';
	}

	function init()
	{
		if ($this->init)
		{
			return;
		}

		$tz         = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$this->date = JFactory::getDate()->setTimeZone($tz);

		$this->request = new stdClass;

		$this->request->idname = 'id';
		$this->request->option = JFactory::getApplication()->input->get('option');
		$this->request->view   = JFactory::getApplication()->input->get('view');
		$this->request->task   = JFactory::getApplication()->input->get('task');
		$this->request->layout = JFactory::getApplication()->input->get('layout', '', 'string');
		$this->request->Itemid = JFactory::getApplication()->input->getInt('Itemid', 0);

		$id = JFactory::getApplication()->input->get('id', array(0), 'array');
		$this->request->id = (int) $id['0'];

		switch ($this->request->option)
		{
			case 'com_categories':
				$extension             = JFactory::getApplication()->input->getCmd('extension');
				$this->request->option = $extension ? $extension : 'com_content';
				$this->request->view   = 'category';
				break;

			case 'com_breezingforms':
				if ($this->request->view == 'article')
				{
					$this->request->option = 'com_content';
				}
				break;
		}

		$option = strtolower(str_replace('com_', '', $this->request->option));
		if (JFile::exists(__DIR__ . '/assignments/' . $option . '.php'))
		{
			require_once __DIR__ . '/assignments/' . $option . '.php';
			$class = 'NNFrameworkAssignments' . $option;
			if (class_exists($class))
			{
				$this->classes[$this->maintype] = new $class($this->request, $this->date);
				$this->classes[$this->maintype]->init();
			}
		}

		if (!$this->request->id)
		{
			$cid = JFactory::getApplication()->input->get('cid', array(0), 'array');
			$this->request->id = (int) $cid['0'];
		}

		// if no id is found, check if menuitem exists to get view and id
		if (JFactory::getApplication()->isSite()
			&& (!$this->request->option || !$this->request->id)
		)
		{
			$menuItem = empty($this->request->Itemid)
				? JFactory::getApplication()->getMenu('site')->getActive()
				: JFactory::getApplication()->getMenu('site')->getItem($this->request->Itemid);

			if ($menuItem)
			{
				if (!$this->request->option)
				{
					$this->request->option = (empty($menuItem->query['option'])) ? null : $menuItem->query['option'];
				}

				$this->request->view = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
				$this->request->task = (empty($menuItem->query['task'])) ? null : $menuItem->query['task'];

				if (!$this->request->id)
				{
					$this->request->id = (empty($menuItem->query[$this->request->idname])) ? $menuItem->params->get($this->request->idname) : $menuItem->query[$this->request->idname];
				}
			}

			unset($menuItem);
		}

		$this->init = true;
	}

	function initParamsByType(&$params, $type = '')
	{
		$this->getAssignmentState($params->assignment);
		$params->id = $type;

		if (strpos($type, '.') === false)
		{
			$params->maintype = $type;
			$params->subtype  = $type;

			return;
		}

		$type             = explode('.', $type, 2);
		$params->maintype = $type['0'];
		$params->subtype  = $type['1'];
	}

	function passAll(&$assignments, $match_method = 'and', $article = 0)
	{
		if (empty($assignments))
		{
			return 1;
		}

		$aid  = ($article && isset($article->id)) ? '[' . $article->id . ']' : '';
		$hash = md5('passAll_' . $aid . '_' . $match_method . '_' . json_encode($assignments));

		if (NNCache::has($hash))
		{
			return NNCache::get($hash);
		}

		$this->init();

		jimport('joomla.filesystem.file');

		$pass = (bool) ($match_method == 'and');

		foreach ($this->types as $type)
		{
			// Break if not passed and matching method is ALL
			// Or if  passed and matching method is ANY
			if (
				(!$pass && $match_method == 'and')
				|| ($pass && $match_method == 'or')
			)
			{
				break;
			}

			if (!isset($assignments[$type]))
			{
				continue;
			}

			$pass = $this->passAllByType($assignments[$type], $type, $article);
		}

		return NNCache::set(
			$hash,
			$pass
		);
	}

	private function passAllByType(&$assignment, $type, $article = 0)
	{
		$aid  = ($article && isset($article->id)) ? '[' . $article->id . ']' : '';
		$hash = md5('passAllByType_' . $type . '_' . $aid . '_' . json_encode($assignment) . '_' . json_encode($article));

		if (NNCache::has($hash))
		{
			return NNCache::get($hash);
		}

		$this->initParamsByType($assignment, $type);

		$hash = md5('passAllByType_' . $type . '_' . $aid . '_' . json_encode($assignment) . '_' . json_encode($article));

		if (NNCache::has($hash))
		{
			return NNCache::get($hash);
		}

		switch ($assignment->assignment)
		{
			case 'all':
				$pass = true;
				break;

			case 'none':
				$pass = false;
				break;

			default:
				$main_type = $assignment->maintype;
				$sub_type  = $assignment->subtype;
				$pass      = false;

				if (!isset($this->classes[$main_type]) && JFile::exists(__DIR__ . '/assignments/' . strtolower($main_type) . '.php'))
				{
					require_once __DIR__ . '/assignments/' . strtolower($main_type) . '.php';
					$class                     = 'NNFrameworkAssignments' . $main_type;
					$this->classes[$main_type] = new $class($this->request, $this->date);
				}

				if (isset($this->classes[$main_type]))
				{
					$method = 'pass' . $sub_type;
					if (method_exists('NNFrameworkAssignments' . $main_type, $method))
					{
						$this->classes[$main_type]->initAssignment($assignment, $article);
						$pass = $this->classes[$main_type]->$method();
					}
				}

				break;
		}

		return NNCache::set(
			$hash,
			$pass
		);
	}

	public function hasAssignments(&$assignments)
	{
		if (empty($assignments))
		{
			return false;
		}

		foreach ($this->types as $type)
		{
			if (isset($assignments[$type]) && isset($assignments[$type]->assignment) && $assignments[$type]->assignment)
			{
				return true;
			}
		}

		return false;
	}

	private function getAssignmentState(&$assignment)
	{
		switch ($assignment)
		{
			case 1:
			case 'include':
				$assignment = 'include';
				break;

			case 2:
			case 'exclude':
				$assignment = 'exclude';
				break;

			case 3:
			case -1:
			case 'none':
				$assignment = 'none';
				break;

			default:
				$assignment = 'all';
				break;
		}
	}

	function makeArray($array = '', $onlycommas = 0, $trim = 1)
	{
		if (empty($array))
		{
			return array();
		}

		$hash = md5('makeArray_' . json_encode($array) . '_' . $onlycommas . '_' . $trim);

		if (NNCache::has($hash))
		{
			return NNCache::get($hash);
		}

		$array = $this->mixedDataToArray($array, $onlycommas);

		if (empty($array))
		{
			return $array;
		}

		if (!$trim)
		{
			return $array;
		}

		foreach ($array as $k => $v)
		{
			if (!is_string($v))
			{
				continue;
			}

			$array[$k] = trim($v);
		}

		return NNCache::set(
			$hash,
			$array
		);
	}

	private function mixedDataToArray($array = '', $onlycommas = 0)
	{
		if (!is_array($array))
		{
			$delimiter = ($onlycommas || strpos($array, '|') === false) ? ',' : '|';

			return explode($delimiter, $array);
		}

		if (empty($array))
		{
			return $array;
		}

		if (isset($array['0']) && is_array($array['0']))
		{
			return $array['0'];
		}

		if (count($array) === 1 && strpos($array['0'], ',') !== false)
		{
			return explode(',', $array['0']);
		}

		return $array;
	}

	public function getAssignmentsFromParams(&$params)
	{
		$hash = md5('getAssignmentsFromParams_' . json_encode($params));

		if (NNCache::has($hash))
		{
			return NNCache::get($hash);
		}

		$types = array();

		foreach ($this->types as $id => $type)
		{
			if (!isset($params->{'assignto_' . $id}) || !$params->{'assignto_' . $id})
			{
				continue;
			}

			$types[$type] = (object) array(
				'assignment' => $params->{'assignto_' . $id},
				'selection'  => array(),
				'params'     => new stdClass(),
			);

			if (isset($params->{'assignto_' . $id . '_selection'}))
			{
				$selection               = $params->{'assignto_' . $id . '_selection'};
				$types[$type]->selection = in_array($type, $this->nonarray) ? $selection : $this->makeArray($selection);
			}

			$this->addParams($types[$type], $type, $id, $params);
		}

		return NNCache::set(
			$hash,
			$types
		);

		return $types;
	}

	private function addParams(&$object, $type, $id, &$params)
	{
		$bool_params  = array();
		$array_params = array();
		$includes     = array();

		switch ($type)
		{
			case 'Menu':
				$bool_params = array('inc_children', 'inc_noitemid');
				break;

			case 'DateTime.Date':
				$bool_params = array('publish_up', 'publish_down', 'recurring');
				break;

			case 'DateTime.Seasons':
				$bool_params = array('hemisphere');
				break;

			case 'DateTime.Time':
				$bool_params = array('publish_up', 'publish_down');
				break;

			case 'URLs':
				if (is_array($object->selection))
				{
					$object->selection = implode("\n", $object->selection);
				}
				if (isset($params->assignto_urls_selection_sef))
				{
					$object->selection .= "\n" . $params->assignto_urls_selection_sef;
				}
				$object->selection     = trim(str_replace("\r", '', $object->selection));
				$object->selection     = explode("\n", $object->selection);
				$object->params->regex = isset($params->assignto_urls_regex) ? $params->assignto_urls_regex : 1;
				break;

			case 'Agents.Browsers':
				if (!empty($params->assignto_mobile_selection))
				{
					$object->selection = array_merge($this->makeArray($object->selection), $this->makeArray($params->assignto_mobile_selection));
				}
				if (!empty($params->assignto_searchbots_selection))
				{
					$object->selection = array_merge($object->selection, $this->makeArray($params->assignto_searchbots_selection));
				}
				break;

			case 'Tags':
				$bool_params = array('inc_children');
				break;

			case 'Content.Categories':
				$bool_params = array('inc_children');
				$includes    = array('cats' => 'categories', 'arts' => 'articles', 'others');
				break;

			case 'EasyBlog.Categories':
			case 'K2.Categories':
			case 'HikaShop.Categories':
			case 'MijoShop.Categories':
			case 'RedShop.Categories':
			case 'VirtueMart.Categories':
				$bool_params = array('inc_children');
				$includes    = array('cats' => 'categories', 'items');
				break;

			case 'Zoo.Categories':
				$bool_params = array('inc_children');
				$includes    = array('apps', 'cats' => 'categories', 'items');
				break;

			case 'EasyBlog.Tags':
			case 'FlexiContent.Tags':
			case 'K2.Tags':
				$includes = array('tags', 'items');
				break;

			case 'Content.Articles':
				$bool_params = array('content_keywords', 'keywords' => 'meta_keywords', 'authors');
				break;

			case 'K2.Items':
				$bool_params = array('content_keywords', 'meta_keywords', 'authors');
				break;

			case 'EasyBlog.Items':
				$bool_params = array('content_keywords', 'authors');
				break;

			case 'Zoo.Items':
				$bool_params = array('authors');
				break;
		}

		if (empty($bool_params) && empty($array_params) && empty($includes))
		{
			return;
		}

		$this->addParamsByType($object, $id, $params, $bool_params, $array_params, $includes);
	}

	private function addParamsByType(&$object, $id, $params, $bool_params = array(), $array_params = array(), $includes = array())
	{
		foreach ($bool_params as $key => $param)
		{
			$key                      = is_numeric($key) ? $param : $key;
			$object->params->{$param} = $this->getTypeParamValue($id, $params, $key);
		}

		foreach ($array_params as $key => $param)
		{
			$key                      = is_numeric($key) ? $param : $key;
			$object->params->{$param} = $this->getTypeParamValue($id, $params, $key, true);
		}

		if (empty($includes))
		{
			return;
		}

		$incs = $this->getTypeParamValue($id, $params, 'inc', true);

		foreach ($includes as $key => $param)
		{
			$key                               = is_numeric($key) ? $param : $key;
			$object->params->{'inc_' . $param} = in_array('inc_' . $key, $incs) ? 1 : 0;
		}

		unset($object->params->inc);
	}

	private function getTypeParamValue($id, $params, $key, $is_array = false)
	{
		if (isset($params->{'assignto_' . $id . '_' . $key}))
		{
			return $params->{'assignto_' . $id . '_' . $key};
		}

		if ($is_array)
		{
			return array();
		}

		return 0;
	}
}
