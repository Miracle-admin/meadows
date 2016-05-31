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

class JTableCompanyVideos extends JTable
{
	var $id				= null;
	var $companyId		= null;
	var $picture_info	= null;
	var $picture_path	= null;
	var $picture_order	= null;
	var $picture_enable = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_company_videos', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	
	function deleteAllForCompany($companyId){
		$db =JFactory::getDBO();
		$sql = "delete from #__jbusinessdirectory_company_videos where companyId=".$companyId;
		$db->setQuery($sql);
		return $db->query();
	}
	
	function getCompanyVideos($companyId){
		$db =JFactory::getDBO();
		$sql = "select * from #__jbusinessdirectory_company_videos where companyId=".$companyId;
		$db->setQuery($sql);
		return $db->loadObjectList();	
		
	}
}