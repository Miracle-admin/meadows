<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class VmvendorControllerAskvendor extends JControllerForm
{
	/**
	 * Custom Constructor
	 */
 	function __construct()	{
		parent::__construct( );
	}
	
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function send()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model 				 = $this->getModel('askvendor');
		// Get the data from POST
		$data  = $this->input->post->get('jform', array(), 'array');
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			return false;
		}

		$user 			= JFactory::getUser();
		$db 			= JFactory::getDBO();
		$doc 			= JFactory::getDocument();
		$juri 			= JURI::base();
		$app 			= JFactory::getApplication();
		
		
		$validate = $model->validate($form, $data);

		if ($validate === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage( $errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_vvmendor.askvendor.data', $data);

			// Redirect back to the form.
			$this->setRedirect(JRoute::_('index.php?option=com_vmvendor&view=askvendor&tmpl=component', false));
			return false;
		}
		
		
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$naming 		= $cparams->get('naming', 'username');	
		
		/*$fromname 		= $app->input->post->get('formname');
		$mailfrom 		= $app->input->post->get('formemail');
		$subject 		= $app->input->post->get('formsubject');
		$formmessage  	= $app->input->post->get('formmessage');
		$product_url 	= $app->input->post->get('formhref');
		$emailto		= $app->input->post->get('emailto');
		$format			= $app->input->get->get('format','html');*/
		
		
		$data  = $this->input->post->get('jform', array(), 'array');
		$fromname 		=$data['formname'];
		$mailfrom 		= $data['formemail'];
		$subject 		= $data['formsubject'];
		$formmessage  	= $data['formmessage'];
		$emailto		= $this->input->post->get('emailto','','raw');
		$product_url	= $this->input->post->get('formhref','','raw');

		if( $fromname !='' && $mailfrom !='' &&  $subject!='' &&   $formmessage!='' )
		{

			$message = JFactory::getMailer(); 
			$config = JFactory::getConfig();
			$body .= $formmessage.",\r\n\r\n";
			$body .= urldecode($product_url);
			
			
			$mailerror = JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED');
			$message->addRecipient($emailto); 
			$message->addBCC( $mailfrom );
			$message->setSubject($subject);
			$message->setBody($body);
			$sender = array( $mailfrom, $fromname );
			$message->setSender($sender);
			$sent = $message->send();
			
			
			if ($sent != 1) 
				$sent = 2;
			/*if($product_url)
				$app->redirect($product_url);
			else*/
				$app->redirect('index.php?option=com_vmvendor&view=askvendor&tmpl=component&sent='.$sent );
		}
	}

	
}
?>