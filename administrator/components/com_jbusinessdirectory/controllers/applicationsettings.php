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
jimport('joomla.application.component.controllerform');

class JBusinessDirectoryControllerApplicationSettings extends JControllerForm
{
	
	public function display($cachable = false, $urlparams = false)
	{
	}
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	 
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model = $this->getModel('applicationsettings');
		$post = JRequest::get( 'post' ); 

		$config = new JConfig();
		$post['sendmail_from'] = $config->mailfrom;
		$post['sendmail_name'] = $config->fromname;
		
		$post['terms_conditions'] = JRequest::getVar('terms_conditions', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		if(isset($post['linkedin']) && strlen($post['linkedin'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['linkedin'])) {
				$post['linkedin'] = "http://" . $post['linkedin'];
			}
		}
		if(isset($post['facebook']) && strlen($post['facebook'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['facebook'])) {
				$post['facebook'] = "http://" . $post['facebook'];
			}
		}
		if(isset($post['twitter']) && strlen($post['twitter'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['twitter'])) {
				$post['twitter'] = "http://" . $post['twitter'];
			}
		}
		if(isset($post['googlep']) && strlen($post['googlep'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['googlep'])) {
				$post['googlep'] = "http://" . $post['googlep'];
			}
		}
		if(isset($post['youtube']) && strlen($post['youtube'])>1){
			if (!preg_match("~^(?:f|ht)tps?://~i", $post['youtube'])) {
				$post['youtube'] = "http://" . $post['youtube'];
			}
		}
		
		if ($model->store($post)) {
			$msg = JText::_('LNG_SETTINGS_APPLICATION_SAVED');
		} else {
			$msg = JText::_('LNG_ERROR_SAVING_SETTINGS_APPLICATION');
		}

		$task     = $this->getTask();
		
		switch ($task)
		{
			case 'apply':
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item , false));
				break;
			default:
				// Check the table in so it can be edited.... we are done with it anyway
				$link = 'index.php?option=com_jbusinessdirectory';
				$this->setRedirect($link, $msg);
				break;
		}
		
		
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel($key = NULL)
	{
		$msg = JText::_('LNG_OPERATION_CANCELLED');
		$this->setRedirect( 'index.php?option=com_jbusinessdirectory', $msg );
	}
}