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
class VmvendorControllerEdittax extends JControllerForm
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
	
	public function save($key = NULL, $urlVar = NULL)
	{
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		if (!class_exists( 'VmConfig' ))
			require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
		$app 					= JFactory::getApplication();
		$user 					=  JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$model      			= $this->getModel ( 'edittax' );
		$view       			= $this->getView  ( 'edittax','html' );	
		$cparams 				= JComponentHelper::getParams( 'com_vmvendor' );
		$tax_mode 			= $cparams->get('tax_mode',0);
		
		$calc_id = $app->input->getInt('calc_id' );

		$data  = $this->input->post->get('jform', array(), 'array');
		$tax_name						=	$data['calc_name'];
		$tax_descr						=	$data['calc_descr'];
		$tax_mathop						=	$data['calc_mathop'];
		$tax_kind						=	$data['calc_kind'];
		$tax_value						=	$data['calc_value'];
		//$tax_cats						=	$app->input->post->get('taxproductcats');
		$tax_cats						=	$data['taxcatselect'];
		
		$tax_vendor_id						=	$app->input->post->getInt('calc_vendor_id');
		
		$tax_shoppergroups					=	explode( ',', $app->input->post->get('calc_shoppergroups') );
	
		// get currency
		$q ="SELECT `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id` ='".$tax_vendor_id."' " ;
		$db->setQuery($q);
		$currency_id = $db->loadResult();
		
		if(!$calc_id) // addition
		{
			$q = "INSERT INTO `#__virtuemart_calcs` 
			( `calc_jplugin_id` , `virtuemart_vendor_id` , `calc_name` , `calc_descr` , `calc_kind` , `calc_value_mathop` , `calc_value` , `calc_currency` , `calc_shopper_published` , `calc_vendor_published` , `publish_up` , `publish_down` , `for_override` , `calc_params` , `ordering` , `shared` , `published` , `created_on` , `created_by` )
			VALUES
			( '0' , '".$db->escape($tax_vendor_id)."' , '".$db->escape($tax_name)."' ,'".$db->escape($tax_descr)."' , 'VatTax' , '".$tax_mathop."' , '".$db->escape($tax_value)."' , '".$currency_id."' , '1' , '1' , '0000-00-00 00:00:00' , '0000-00-00 00:00:00' , '0' , '' , '0' , '0' , '1' , '".date('Y-m-d H:i:s')."' ,'".$user->id."'  )";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$virtuemart_calc_id = $db->insertid();
			// Procdct categories addition
			if(!in_array( 'all' ,$tax_cats) )
			{
				for($i = 0; $i < count($tax_cats) ; $i++ )
				{
					if($tax_cats[$i]>0)
					{
						$q ="INSERT INTO `#__virtuemart_calc_categories` 
					( `virtuemart_calc_id` , `virtuemart_category_id` )
					VALUES
					('".$virtuemart_calc_id."','".$tax_cats[$i] ."') ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				
				}
			}
			// Shopper groups addition
			
			/*for($i = 0; $i < count($tax_shoppergroups) ; $i++ )
			{
				if($tax_shoppergroups[$i]>0)
				{
					$q ="INSERT INTO `#__virtuemart_calc_shoppergroups` 
				( `virtuemart_calc_id` , `virtuemart_shoppergroup_id` )
				VALUES
				('".$virtuemart_calc_id."','".$tax_shoppergroups[$i] ."') ";
					$db->setQuery($q);
					if (!$db->query()) die($db->stderr(true));
				}		
			}*/
	
			$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_TAXADDED_SUCCESS' );
			$app->enqueueMessage( $message );
			$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
		}
		else  // edition
		{
			$q = "UPDATE `#__virtuemart_calcs` 
			SET
			 `virtuemart_vendor_id` 	= '".$db->escape($tax_vendor_id)."' ,
			 `calc_name`			 	= '".$db->escape($tax_name)."' ,
			 `calc_descr` 			 	= '".$db->escape($tax_descr)."' ,
			 `calc_kind` 				= '".$db->escape($tax_kind)."' ,
			 `calc_value_mathop` 		= '".$tax_mathop."' ,
			 `calc_value` 				= '".$db->escape($tax_value)."' ,
			 `calc_currency` 			= '".$db->escape($currency_id)."' ,
			 `modified_on` 				= '".date('Y-m-d H:i:s')."' ,
			 `modified_by` 				= '".$user->id."' 
			 WHERE virtuemart_calc_id ='".$calc_id."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			//$virtuemart_calc_id = $db->insertid();
			// Procdct categories removal
			$q = "DELETE FROM #__virtuemart_calc_categories WHERE virtuemart_calc_id ='".$calc_id."'  ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			// Procdct categories addition
			if(!in_array( 'all' ,$tax_cats) )
			{
				for($i = 0; $i <= count($tax_cats) ; $i++ )
				{
					if($tax_cats[$i]>0)
					{
						$q ="INSERT INTO `#__virtuemart_calc_categories` 
					( `virtuemart_calc_id` , `virtuemart_category_id` )
					VALUES
					('".$calc_id."','".$tax_cats[$i] ."') ";
						$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
					}
				
				}
			}
			else
			{
			$q ="DELETE FROM #__virtuemart_calc_categories WHERE virtuemart_calc_id ='".$calc_id."' "	;
			$db->setQuery($q);
						if (!$db->query()) die($db->stderr(true));
			}
			
	
			$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_DASHBOARD_TAXEDITED_SUCCESS' );
			$app->enqueueMessage( $message );
			$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
			
		}
	}
	
}
?>