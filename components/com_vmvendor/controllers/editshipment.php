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
class VmvendorControllerEditshipment extends JControllerForm
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
		VmConfig::loadConfig();
		$app 					= JFactory::getApplication();
		$user 					= JFactory::getUser();		
		$juri 					= JURI::base();
		$db						= JFactory::getDBO();	
		$model      			= $this->getModel ( 'editshipment' );
		$view       			= $this->getView  ( 'editshipment','html' );	
		$cparams 				= JComponentHelper::getParams( 'com_vmvendor' );

		$shipment_mode 			= $cparams->get('shipment_mode',0);
		if($shipment_mode==0)
				return false;
		
		$shipmentid 	= $app->input->post->get('shipmentid' ,null, 'int');
		$jpluginid 		= $app->input->post->get('jpluginid' ,null, 'int');

		$data  					= $this->input->post->get('jform', array(), 'array');		
		$shipment_name			=	$data['shipment_name'];
		$shipment_desc			=	$data['shipment_desc'];
		$shipment_published		=	$data['shipment_published'];
		$show_on_pdetails		=	$data['show_on_pdetails'];
		$multicountries			=	$data['multicountries'];
		$ziprangestart			=	$data['ziprange_start'];
		$ziprangeend			=	$data['ziprange_end'];
		$lowestweight			=	$data['lowest_weight'];
		$highestweight			=	$data['highest_weight'];
		$weightunit				=	$data['weightunit'];
		$minimum_orderproducts	=	$data['minimum_orderproducts'];
		$maximum_orderproducts	=	$data['maximum_orderproducts'];
		$minimum_orderamount	=	$data['minimum_orderamount'];
		$maximum_orderamount	=	$data['maximum_orderamount'];
		$shipment_cost			=	$data['shipment_cost'];
		$package_fee			=	$data['package_fee'];
		$shipmenttaxrules		=	$data['shipmenttaxrules'];
		$freeshipment_amount	=	$data['freeshipment_amount'];

		$countries ='[';
		$i = 0;
		foreach($multicountries as $multicountry)
		{
			$i++;
			$countries .= '"'.$multicountry.'"';
			if($i<count($multicountries) )
				$countries .= ',';
		}
		$countries .=']';

		$shipment_params = 'shipment_logos=""|show_on_pdetails="'.$db->escape($show_on_pdetails).'"|countries="'.$countries.'"|zip_start="'.$db->escape($ziprangestart).'"|zip_stop="'.$db->escape($ziprangeend).'"|weight_start="'.$db->escape($lowestweight).'"|weight_stop="'.$db->escape($highestweight).'"|weight_unit="'.$db->escape($weightunit).'"|nbproducts_start='.$db->escape($minimum_orderproducts).'|nbproducts_stop='.$db->escape($maximum_orderproducts).'|orderamount_start="'.$db->escape($minimum_orderamount).'"|orderamount_stop="'.$db->escape($maximum_orderamount).'"|shipment_cost="'.$db->escape($shipment_cost).'"|package_fee="'.$db->escape($package_fee).'"|tax_id="'.$db->escape($shipmenttaxrules).'"|free_shipment="'.$db->escape($freeshipment_amount).'"|';
		//
		$ship_vendor_id			=	$app->input->post->get('ship_vendor_id', '' , 'INT');
		$shipment_shoppergroups	=	explode( ',', $app->input->post->get('shipment_shoppergroups') );

		if(!$shipmentid) // addition
		{
			$q = "INSERT INTO `#__virtuemart_shipmentmethods` 
			( `virtuemart_vendor_id` , `shipment_jplugin_id` , `shipment_element` , `shipment_params` , `shared` ,
			 `published` , `created_on` , `created_by` )
			VALUES
			(  '".$db->escape($ship_vendor_id)."' , '".$jpluginid."' ,'weight_countries' , '".$shipment_params."' , '0' ,
			 '".$db->escape($shipment_published)."' , '".date('Y-m-d H:i:s')."' , '".$user->id."'  )";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));
			$virtuemart_shipment_id = $db->insertid();
			
			$slug = $virtuemart_shipment_id.'-'.JFilterOutput::stringURLSafe($shipment_name);
			$q = "INSERT INTO `#__virtuemart_shipmentmethods_".VMLANG."` 
			( `virtuemart_shipmentmethod_id` , `shipment_name` , `shipment_desc` , `slug`)
			VALUES
			(  '".$virtuemart_shipment_id."' , '".$db->escape($shipment_name)."' ,
			 '".$db->escape($shipment_desc)."' , '".$db->escape($slug)."' )";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));


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
	
			$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_EDITSHIPMENT_SHIPMENTADDED_SUCCESS' );
			$app->enqueueMessage( $message );
			$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
		}
		else  // edition
		{
			$slug = $shipmentid.'-'.JFilterOutput::stringURLSafe($shipment_name);

			$q = "UPDATE `#__virtuemart_shipmentmethods` 
			SET
			 `shipment_params` 			= '".$db->escape($shipment_params)."' ,
			 `published`			 	= '".$shipment_published."' ,
			 `modified_on` 			 	= '".date('Y-m-d H:i:s')."' ,
			 `modified_by` 				= '".$user->id."' 
			  WHERE virtuemart_shipmentmethod_id ='".$shipmentid."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));

			$q = "UPDATE `#__virtuemart_shipmentmethods_".VMLANG."` 
			SET
			 `shipment_name` 	= '".$db->escape($shipment_name)."' ,
			 `shipment_desc`	= '".$db->escape($shipment_desc)."' ,
			 `slug` 			= '".$db->escape($slug)."' 
			 WHERE virtuemart_shipmentmethod_id ='".$shipmentid."' ";
			$db->setQuery($q);
			if (!$db->query()) die($db->stderr(true));

			$message .= '<i class="vmv-icon-ok"></i> '.JText::_( 'COM_VMVENDOR_EDITSHIPMENT_SHIPMENTEDITED_SUCCESS' );
			$app->enqueueMessage( $message );
			$app->redirect('index.php?option=com_vmvendor&view=dashboard&Itemid='.$app->input->get('Itemid'));
		}
	}
}
?>