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

class JBusinessDirectoryControllerBusinessUser extends JControllerLegacy
{
	
	function __construct()
	{
		parent::__construct();
	}

	function checkUser(){
		
		$user = JFactory::getUser();
		$filterParam = "";
		$filter_package = JFactory::getApplication()->input->get("filter_package");
		
		if(!empty($filter_package)){
			$filterParam ="&filter_package=".$filter_package;
		}
		
		if($user->id == 0 ){
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=businessuser'.$filterParam, false));
		}
		/* else{
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&task=subscription.processsubscription'.$filterParam, false));
		} */
		
		else{
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompany&showSteps=true&layout=edit'.$filterParam, false));
		}
		
		return;
	}
	
	function loginUser(){
		$model = $this->getModel("businessuser");
		
		$filterParam = "";
		$filter_package = JFactory::getApplication()->input->get("filter_package");
		
		if(!empty($filter_package)){
			$filterParam ="&filter_package=".$filter_package;
		}
		
		if(!$model->loginUser()){
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=businessuser'.$filterParam, false));
		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompany&showSteps=true&layout=edit'.$filterParam, false));
		}
	}
	
	function addUser(){
		$model = $this->getModel("businessuser");
		$filterParam = "";
		$filter_package = JFactory::getApplication()->input->get("filter_package");
		
		if(!empty($filter_package)){
			$filterParam ="&filter_package=".$filter_package;
		}

		$data = JRequest::get('post');
		
		if(!$model->addJoomlaUser($data)){
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=businessuser'.$filterParam, false));
		}else{
			$model->loginUser();
			$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompany&showSteps=true&layout=edit'.$filterParam, false));
		}
	}
}