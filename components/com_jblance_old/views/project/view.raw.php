<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * XML View class for the HelloWorld Component
 */
class JblanceViewProject extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
		
	   $app  	= JFactory::getApplication();
		$layout = $app->input->get('layout', 'editproject', 'string');
		$model	= $this->getModel();
		$user	= JFactory::getUser();
		
		JblanceHelper::isAuthenticated($user->id, $layout);
		
		if($layout == 'projectprogress'){
			$return  = $model->getProjectProgress();
			$row = $return[0];
			$messages = $return[1];
			$pdashboard=$model->getProjectdashboard();
			$this->assignRef('row', $row);
			$this->assignRef('messages', $messages);
			$this->assignRef('rows', $rows);
			$this->assignRef('project', $project);
			$this->assignRef('title', $pdashboard['project_title']);
			$this->assignRef('description', $pdashboard['description']);
			$this->assignRef('remaining', $pdashboard['remaining']);
			$this->assignRef('bids', $pdashboard['bids']);
			$this->assignRef('location', $pdashboard['location']);
			$this->assignRef('budget', $pdashboard['budget']);
			$this->assignRef('approved', $pdashboard['approved']);
			$this->assignRef('projectStatusBar', $pdashboard['projectBar']);
			$this->assignRef('category', $pdashboard['category']);
			$this->assignRef('upgrades', $pdashboard['upgrades']);
			$this->assignRef('upgradespurchased', $pdashboard['upgradespurchased']);
			$this->assignRef('step', $pdashboard['step']);
			$user=JFactory::getUser();
			
			$doc = JFactory::getDocument();
		    $doc->setTitle(ucfirst(ucfirst(trim($pdashboard['project_title']))).":Work progress");
		}
            
        }
}