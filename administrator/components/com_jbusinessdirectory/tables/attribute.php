<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class JTableAttribute extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_attributes', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	function getAttributes(){
		$query = "select a.* from #__jbusinessdirectory_attributes a ";
		
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function getActiveAttributes(){
		$query = "select a.* from #__jbusinessdirectory_attributes a where status =1 order by a.name";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function getAttributesWithTypes(){
		$query = "select a.*, at.code as attr_type
					from #__jbusinessdirectory_attributes a
				  	left join #__jbusinessdirectory_attribute_types AS at on at.id=a.type ";
	
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function getAttributesConfiguration($ids){
		$query = "select a.*,
				GROUP_CONCAT(DISTINCT ao.name ORDER BY ao.name asc SEPARATOR ',')  options,
				GROUP_CONCAT(DISTINCT ao.id ORDER BY ao.name asc SEPARATOR ',')  optionsIDS,
				at.code as attributeTypeCode
				from #__jbusinessdirectory_attributes a
				left join #__jbusinessdirectory_attribute_types AS at on at.id=a.type
				left join #__jbusinessdirectory_attribute_options as ao on ao.attribute_id = a.id
				where a.status = 1 and a.id in ($ids)
				group by a.id
				order by a.ordering
				";
	
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function changeState($attributeId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_attributes SET status = IF(status, 0, 1) WHERE id = ".$attributeId ;
		$db->setQuery( $query );
		if (!$db->query()){
			return false;
		}
		return true;
	}
	
}