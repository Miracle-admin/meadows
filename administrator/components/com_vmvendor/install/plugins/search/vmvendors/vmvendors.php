<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;


/**
 * Contacts Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.contacts
 * @since		1.6
 */
class plgSearchVmvendors extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	* @return array An array of search areas
	*/
	function onContentSearchAreas(){
		static $areas = array(
			'vmvendors' => 'PLG_SEARCH_VMVENDORS_VENDORS'
		);
		return $areas;
	}
	
	
	function getVendorrofileItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=vendorprofile' AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  AND access='1' ";
		$db->setQuery($q);
		return $profile_itemid = $db->loadResult();
	
	}

	/**
	* Contacts Search method
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string matching option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		if (!class_exists( 'VmConfig' ))
			require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$limit			= $this->params->def('search_limit',		50);
		
		

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$section = JText::_('PLG_SEARCH_VMVENDORS_VENDORS');

		switch ($ordering) {
			case 'alpha':
				$order = 'vv.`vendor_name` ASC';
				break;

			case 'category':
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'vv.`vendor_name` DESC';
		}

		$text	= $db->Quote('%'.$db->escape($text, true).'%', false);
		$rows = array();
			
		$vmvendor_itemid = $this->getVendorrofileItemid();
			

		$q ="SELECT vv.`vendor_name` AS title , vv.`created_on` AS created ,  vvl.`vendor_store_desc` AS text, `u`.id  , '".JText::_('PLG_SEARCH_VMVENDORS_VENDORS')."' AS section 
			FROM `#__virtuemart_vendors` vv 
			LEFT JOIN `#__virtuemart_vendors_".VMLANG."` vvl ON vvl.`virtuemart_vendor_id` = vv.`virtuemart_vendor_id` 
			LEFT JOIN `#__users` u ON `u`.id = vv.`created_by`
			WHERE vv.`vendor_name` LIKE ".$text."  
			AND u.`block`='0' 
			ORDER BY ".$order;


		$db->setQuery($q, 0, $limit);
		$rows = $db->loadObjectList();

		if ($rows) {
			foreach($rows as $key => $row)
			{
				$rows[$key]->href = JRoute::_('index.php?option=com_vmvendor&view=vendorprofile&userid='.$row->id.'&Itemid='.$vmvendor_itemid);
			}
		}
	
		return $rows;
	}
}
