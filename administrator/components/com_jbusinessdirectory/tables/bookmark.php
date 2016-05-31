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

class JTableBookmark extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_bookmarks', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	
	function getBookmark($companyId, $userId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_bookmarks where company_id=$companyId and user_id=$userId";
		$db->setQuery($query);
		$result =  $db->loadObject();
		
		return $result;
	}
}