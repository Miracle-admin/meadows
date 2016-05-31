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

class JTablePaymentProcessorFields extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_payment_processor_fields', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	function getPaymentProcessorFields($processorId){
		$query = " SELECT * FROM #__jbusinessdirectory_payment_processor_fields where processor_id=$processorId";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function deleteProcessorFields($ids){
		$query = "delete from #__jbusinessdirectory_payment_processor_fields WHERE processor_id in ($ids)";
		$this->_db->setQuery( $query );
		if (!$this->_db->query())
		{
			return false;
		}
		return true;
	}
}