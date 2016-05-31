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

class JTableAttributeOptions extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_attribute_options', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	function getAttributeOptions($attributeId){
		$query = "select ao.*
					from #__jbusinessdirectory_attributes a
					left join #__jbusinessdirectory_attribute_options ao on a.id=ao.attribute_id
					where a.id= $attributeId
					order by ao.name asc";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function getAllAttributesWithOptions(){
		$query = "select a.id as attr_id,a.name as attr_name, a.code,ao.*,at.code			
					from #__jbusinessdirectory_attributes a
					left join #__jbusinessdirectory_attribute_types AS at on at.id=a.type
					left join #__jbusinessdirectory_attribute_options as ao on ao.attribute_id = a.id
					where a.status = 1
					group by ao.id
					";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	
	function getAllAttributeOptions(){
		$query = "select ao.*
				from #__jbusinessdirectory_attribute_options ao order by ao.name asc";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function deleteAllAtributeOptions($attributeId){
		$query = "delete from #__jbusinessdirectory_attribute_options where attribute_id in ($attributeId)";
		$this->_db->setQuery( $query );
		$this->_db->query();
	}
	
	function deleteAtributeOptions($attributeId, $ids){
		if(!empty($ids)){
			$query = "delete from #__jbusinessdirectory_attribute_options where attribute_id in ($attributeId) and id not in ($ids)";
		}else{
			$query = "delete from #__jbusinessdirectory_attribute_options where attribute_id in ($attributeId)";
		}
		$this->_db->setQuery( $query );
		$this->_db->query();
	}
	
	
	
}