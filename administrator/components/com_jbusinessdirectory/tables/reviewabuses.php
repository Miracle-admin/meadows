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

class JTableReviewAbuses extends JTable
{

	var $id				= null;
	var $reviewId		= null;
	var $email			= null;
	var $description	= null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function JTableReviewAbuses(& $db) {

		parent::__construct('#__jbusinessdirectory_company_review_abuses', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

}