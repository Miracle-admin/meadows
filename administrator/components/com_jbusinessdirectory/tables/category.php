<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.database.tablenested');
/**
 * Category table
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JBusinessTableCategory extends JTableNested{
	
	/**`
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){
	
		parent::__construct('#__jbusinessdirectory_categories', 'id', $db);
	}
	
	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 */
	public function delete($pk = null, $children = false)
	{
		return parent::delete($pk, $children);
	}
	
	public function getCategoryById($categoryId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_categories c
				 where c.id=$categoryId";
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	public function getAllCategories(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_categories where published=1 order by parent_id, name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getCategoriesForExport(){
		$db =JFactory::getDBO();
		$query = "select  c.name, GROUP_CONCAT(cc.name ORDER BY cc.name) as subcategories
				from #__jbusinessdirectory_categories c
				left join #__jbusinessdirectory_categories cc on c.id = cc.parent_id
				group by c.id
				order by c.lft, cc.name";
	
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getMainCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_categories where published=1 and parent_id=1 order by name';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getSubCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_categories where published=1 and parent_id!=1 order by name';
		$db->setQuery($query);
		$result = $db->loadObjectList();
	
		return $result;
	}
	
	function getCategoriesList($keyword){
		$db =JFactory::getDBO();
		$query = "select distinct name as label, name as value from #__jbusinessdirectory_categories where name like '%$keyword%' and published=1";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getTotalCategories(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_categories";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->nr;
	}
	
	function checkAlias($id, $alias){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_categories  WHERE alias='$alias' and id<>$id";
		$db->setQuery($query);
		$result = $db->loadObject();
		dump($result);
		return $result->nr;
	}
}


