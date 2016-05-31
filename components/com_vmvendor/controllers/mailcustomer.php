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
class VmvendorControllerMailcustomer extends VmvendorController
{
 	function __construct()
	{
		parent::__construct( );
	}
	
	public function mailcustomer() 
	{
		$user 			= JFactory::getUser();
		$db 			= JFactory::getDBO();
		$doc 			= JFactory::getDocument();
		$juri 			= JURI::base();
		$app 			= JFactory::getApplication();
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$naming 				= $cparams->get('naming', 'username');	
		$customercontactform	= $cparams->get('customercontactform', '1'); //1=email 	11=email+admin    2=jomsocial pms
		$emailto		= $app->input->get('emailto','','raw');
		
		$data  = $this->input->post->get('jform', array(), 'array');
		/*$fromname 		=$data['formname'];
		$mailfrom 		= $data['formemail'];
		$subject 		= $data['formsubject'];
		$formmessage 	= $data['formmessage'];*/
		
		$fromname 		= $app->input->get('formname','','raw');
		$mailfrom 		= $app->input->get('formemail','','raw');
		$subject 		= $app->input->get('formsubject','','raw');
		$formmessage 	= $app->input->get('formmessage','','raw');
		
		//$app->enqueueMessage($fromname.' '.$mailfrom.' '.$subject.' '.$formmessage);
		
		if( $fromname!='' && $mailfrom!='' &&  $subject!='' && $formmessage!='' )
		{
			$product_url = $app->input->post->get('formhref');
			$message 	= JFactory::getMailer(); 
			$config 	= JFactory::getConfig();
			
			$body = $formmessage.",\r\n\r\n";
			$body .= urldecode($product_url);
			$mailerror = JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED');
			$message->addRecipient($emailto); 
			$message->addBCC( $mailfrom );
			if($customercontactform=='11')
				$message->addBCC( $config->get( 'config.mailfrom' ) );   // site admin
			$message->setSubject($subject);
			$message->setBody($body);
			$sender = array( $mailfrom, $fromname );
			$message->setSender($sender);
			$sent = $message->send();
			if ($sent != 1) 
				echo  $mailerror;
			else
			{
				echo JText::_('COM_VMVENDOR_MAILCUSTOMER_SENT');	//Email sent successfully
				$message = '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_MAILCUSTOMER_SENT' );
				$app->enqueueMessage( $message );
			}
			
			
			$app->redirect(
			'index.php?option=com_vmvendor&view=mailcustomer&Itemid='.$app->input->get('Itemid').'&tmpl=component&sent='.$sent);
			
		}
		else
			JError::raiseWarning('100' ,  JText::_('COM_VMVENDOR_ASKVENDOR_EMAILFAILED')  );
		
	}	
}
?>