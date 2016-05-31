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
class VMVendorViewVendorprofile extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null) 
	{
		JHtml::_('bootstrap.framework');//breaks page js
		$app =  JFactory::getApplication();
		$user = JFactory::getUser();
		$userid = $app->input->getInt('userid');
		if($user->id == 0 && !$userid )
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILE_MUSTLOGIN') );
			return false;
		}
		elseif($user->id>0 && !$userid)
		{
			$userid = $user->id;
		}
		$this->ismyprofile = 0;
		if($userid == $user->id)
			$this->ismyprofile = 1;
		$allowed = 1;
		
		$cparams 	= JComponentHelper::getParams('com_vmvendor');
		$exclude_users		= $cparams->get('exclude_users');
		$log_profilevisits	= $cparams->get('log_profilevisits', 1);
		if( $log_profilevisits && $user->id!='0' && $user->id!=$userid && !in_array( $user->id , $exclude_users) )
		{
			$this->get('logvisit');
		}
		$enable_vendormap		= $cparams->get('enable_vendormap',0);
		if($enable_vendormap)
			$this->coords				= $this->get('vendorlocation');
		
		$this->myproducts_array	= $this->get('myproducts');
		$this->main_currency	= $this->get('currency');
		$this->vendor_data		= $this->get('vendordata');
		$this->user_thumb		= $this->get('userthumb');
		$this->jgroup			= $this->get('vendorjgroup');
		$this->vendor_thumb_url = $this->get('VendorThumb');
		$this->dashboard_itemid	= $this->get('DashboardItemid');
		$this->addproduct_itemid= $this->get('Addproductitemid');		
		$this->allmyproducts	= $this->get('allmyproducts');
		$this->myproducts	= $this->myproducts_array[0];
		$this->total		= $this->myproducts_array[1];
 		$this->limit		= $this->myproducts_array[2];
		$this->limitstart	= $this->myproducts_array[3];
		// Assign data to the view
		
		if( count($this->myproducts)<1)
		{
			$app->enqueueMessage( JText::_('COM_VMVENDOR_PROFILE_NOVENDORYET') );
			//if($user->id != $userid)
			//return false;
		}
		$pagination = new JPagination( $this->total, $this->limitstart, $this->limit );	
		$pagination->setAdditionalUrlParam('catfilter', $app->input->get('catfilter','','int') );
		
		$this->assignRef('pagination', $pagination );
		
 		$this->tabsOptions = array( 'active' => 'vendorprofileTab_1' );
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR  . '/components/com_virtuemart/helpers/config.php';
		
		
		// Display the view
		parent::display($tpl);
	}
}