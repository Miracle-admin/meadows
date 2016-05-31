<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.html.pagination' );
/**
 * HTML View class for the HelloWorld Component
 */
class VMVendorViewProfilevisits extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		JHtml::_('bootstrap.framework');//breaks page js
		$app =  JFactory::getApplication();
		$user = JFactory::getUser();
		if(!$user->id)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILE_MUSTLOGIN') );
			return false;
		}
	

		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$profileman		= $cparams->get('profileman');
		
		$this->visits	= $this->get('visits');
		$this->visitors	= $this->get('visitors');
		if($profileman!='0')
			$this->profileItemid = 	$this->get('UserprofileItemid');
		if($profileman=='es')
			require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
		elseif($profileman=='js')
		{
			require_once JPATH_BASE.'/components/com_community/libraries/core.php';
			$config				= CFactory::getConfig();
			$naming 			= $config->get('displayname','username');
			$juri_storage		= $juri;
			if($config->get('storages3bucket')!='' &&   $config->get('storages3bucket_url')!=''  && $config->get('user_avatar_storage')=='s3' )
			{
				$juri_storage	= '//'.str_replace( '<bucket>', $config->get('storages3bucket') , $config->get('storages3bucket_url') ).'/';
			}
		}
	
		
		/*if( count($this->visits)<1)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILEVISITS_NOVISITORS') );
				return false;
		}*/
		
		
		/*
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );	
		
		$pagination->setAdditionalUrlParam('catfilter', $app->input->get('catfilter','','int') );
		
		$this->assignRef('pagination', $pagination );*/
		
 		
		
		
		// Display the view
		parent::display($tpl);
	}
}