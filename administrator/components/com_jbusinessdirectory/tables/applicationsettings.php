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

class TableApplicationSettings extends JTable
{
	var $applicationsettings_id		= null;
	var $company_name				= null;
	var $company_email				= null;
	var $currency_id				= null;
	var $css_style					= null;
	var $css_module_style			= null;
	var $show_frontend_language		= null;
	var $default_frontend_language	= null;
	var $date_format_id				= null;
	

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableApplicationSettings(& $db) {
	
		parent::__construct('#__jbusinessdirectory_applicationsettings', 'applicationsettings_id', $db);
	}
	
	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	function updateOrder($orderId,$orderEmail){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_applicationsettings SET order_id = '".$orderId."',order_email='".$orderEmail."'";
		$db->setQuery($query);
		$result =  $db->query();
	
		return $result;
	}
	
}