<?php
/*
 * @title		VM - Custom, Downloadable Products
 * @version		3.5
 * @package		Joomla
 * @author		ekerner@ekerner.com.au
 * @website		http://www.ekerner.com.au
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * @copyright		Copyright (C) 2012 - 2014 eKerner.com.au All rights reserved.
 */

defined('_JEXEC') or die('Restricted access');
if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);

class plgVmcustomDownloadableInstallerScript
{
	protected $plugin_name = 'downloadable';
	protected $dbprefix = '#__';
	protected $db;

	public function __construct($adapter)
	{
		// get the db prefix
		if (!class_exists('JConfig'))
			require(JPATH_CONFIGURATION . DS . 'configuration.php');
		$cfg = new JConfig();
		$this->dbprefix = $cfg->dbprefix;
		$this->db = JFactory::getDbo();
	}

	public function install($adapter)
	{
		// enabling plugin
		$this->db->setQuery("UPDATE {$this->dbprefix}extensions SET enabled = 1 WHERE type = 'plugin' AND element = '{$this->plugin_name}' AND folder = 'vmcustom'");
		$this->db->query();
		return $this->reportIfDbError();
	}

	public function update($adapter)
	{
		return $this->install($adapter);
	}
 
	public function uninstall($adapter)
	{
		// Remove from database
		$virtuemart_custom_id = $this->db->setQuery("SELECT virtuemart_custom_id FROM {$this->dbprefix}virtuemart_customs WHERE custom_value = '{$this->plugin_name}' LIMIT 1")->loadResult();
		if ($virtuemart_custom_id)
			$this->db->setQuery("DELETE a.*, b.* FROM {$this->dbprefix}virtuemart_product_customfields AS a, {$this->dbprefix}virtuemart_customs AS b WHERE a.virtuemart_custom_id = {$virtuemart_custom_id} AND b.virtuemart_custom_id = a.virtuemart_custom_id")->query();
		return $this->reportIfDbError();
	}

	protected function reportIfDbError()
	{
		if ($this->db->getErrorNum()) {
			JError::raiseError(500, $this->db->getErrorMsg());
			return false;
		}
		return true;
	}
}
