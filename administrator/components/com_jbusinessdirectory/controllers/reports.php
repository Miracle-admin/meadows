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



class JBusinessDirectoryControllerReports extends JControllerLegacy
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
	}

	function generateReport(){
		
		parent::display();
	}
	
	/**
	 * Removes an item
	 */
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');
	
		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseWarning(500, JText::_('COM_JBUSINESSDIRECTORY_NO_REPORT_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel("Report");
	
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);
	
			// Remove the items.
			if (!$model->delete($cid))
			{
				$this->setMessage($model->getError());
			} else {
				$this->setMessage(JText::plural('COM_JBUSINESSDIRECTORY_N_REPORTS_DELETED', count($cid)));
			}
		}
	
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=reports');
	}
	
	public function exportReportToCSV(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model = $this->getModel('Reports');
		$data = $model->getReportData();
		$model->exportReportToCSV($data);
		
		exit;
	}
}