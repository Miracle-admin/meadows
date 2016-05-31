<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class VmvendorModelDashboard extends JModelItem
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	public function getMysales() 
	{
		$db 			= JFactory::getDBO();
		$user 			= JFactory::getUser();
		$app			= JFactory::getApplication();
		// Get the pagination request variables
		$limit 			= $app->getUserStateFromRequest('com_vmvendor.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart 	= $app->input->get('limitstart', 0, 'INT');
		
		$q = "SELECT DISTINCT(voi.virtuemart_order_item_id) , voi.virtuemart_product_id , voi.order_item_sku , voi.order_item_name , voi.product_quantity, voi.product_item_price , voi.order_status, 
		vpc.virtuemart_category_id ,
		vp.virtuemart_vendor_id , 
		vo.virtuemart_user_id , vo.order_number , vo.order_currency ,vo.created_on ,
		vu.address_type , vu.name , vu.company , vu.title , vu.last_name , vu.first_name , vu.middle_name , vu.address_1 , vu.address_2 , vu.phone_1 , vu.phone_2 , vu.city ,  vu.zip , 
		voui.address_type AS guest_address_type , voui.company  AS guest_company , voui.title  AS guest_title , voui.last_name AS guest_last_name  , voui.first_name  AS guest_first_name , voui.middle_name AS guest_middle_name  , voui.address_1  AS guest_address_1 , voui.address_2 AS guest_address_2  , voui.phone_1  AS guest_phone_1 , voui.phone_2  AS guest_phone_2 , voui.city  AS guest_city  ,  voui.zip AS guest_zip  , 
		voui.customer_note , 
		u.email ,
		vc.country_name ,
		vs.state_name ,
		vs2.state_name AS guest_state_name 
		FROM #__virtuemart_order_items voi
		LEFT JOIN `#__virtuemart_product_categories` vpc ON vpc.virtuemart_product_id = voi.virtuemart_product_id 
		LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_product_id = voi.virtuemart_product_id 
		LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
		LEFT JOIN #__virtuemart_orders vo ON vo.virtuemart_order_id = voi.virtuemart_order_id 
		LEFT JOIN #__virtuemart_userinfos vu ON vu.virtuemart_user_id = vo.virtuemart_user_id 
		LEFT JOIN #__users u ON u.id = vo.virtuemart_user_id 
		LEFT JOIN #__virtuemart_countries vc ON vc.virtuemart_country_id = vu.virtuemart_country_id 
		LEFT JOIN #__virtuemart_states vs ON vs.virtuemart_state_id = vu.virtuemart_state_id 
		
		LEFT JOIN #__virtuemart_order_userinfos voui ON voui.virtuemart_order_id = vo.virtuemart_order_id AND voui.address_type='BT' 
		LEFT JOIN #__virtuemart_states vs2 ON vs2.virtuemart_state_id = voui.virtuemart_state_id 
		WHERE vv.virtuemart_user_id = '".$user->id."' 
		AND vo.order_status='C' 
		GROUP BY voi.virtuemart_order_item_id 
		ORDER BY voi.virtuemart_order_item_id DESC ";
		$db->setQuery($q);		
		$total = $this->_getListCount($q);
		$mysales = $this->_getList($q, $limitstart, $limit);
		return array($mysales, $total, $limit, $limitstart);
	}
	
	
	public function getCurrency() 
	{
		$db = JFactory::getDBO();
		$q ="SELECT vc.`currency_symbol` , vc.`currency_positive_style` , vc.`currency_decimal_place` , vc.`currency_decimal_symbol` , vc.`currency_thousands` 
		FROM `#__virtuemart_currencies` vc 
		LEFT JOIN `#__virtuemart_vendors` vv ON vv.`vendor_currency` = vc.`virtuemart_currency_id` 
		WHERE vv.`virtuemart_vendor_id` ='1' " ;		
		$db->setQuery($q);
		$this->main_currency = $db->loadRow();
		return $this->main_currency;
	}
	
	
	static function getVendorPoints() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q ="SELECT points FROM #__vmvendor_userpoints WHERE userid='".$user->id."' " ;		
		$db->setQuery($q);
		$mypoints = $db->loadResult();
		return $mypoints;
	}
	static function getPointsDetails( $limit ) 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q ="SELECT insert_date, points , datareference FROM #__vmvendor_userpoints_details 
		WHERE userid='".$user->id."' AND status='1' AND approved='1'
		ORDER BY id DESC LIMIT ".$limit ;		
		$db->setQuery($q);
		$mypointsdetails = $db->loadObjectList();
		return $mypointsdetails;
	}
	
	
	/*public function getColumnchart() 
	{
		$user 		=  JFactory::getUser();
		$db = JFactory::getDBO();
		$start_date = JRequest::getVar('start_date');
		if(!$start_date)
			$start_date= date('Y-m').'-01 00:00:00';
		$end_date = JRequest::getVar('end_date');
		if(!$end_date)
			$end_date= date('Y-m-d H:i:s');
		$time_unit = JRequest::getVar('time_unit');
		if(!$time_unit)
			$time_unit = 'days';
		$subject = JRequest::getVar('subject');
		if(!$subject)
			$subject = 'revenue';
			
			
			
			
		if($time_unit == 'days')
			$orderby = 'SUBSTR( voi.created_on , 0, 10)';
		elseif($time_unit == 'months')
			$orderby = 'SUBSTR( voi.created_on , 0, 7)';
		elseif($time_unit == 'years')
			$orderby = 'SUBSTR( voi.created_on , 0, 4)';
		
		echo $q = "SELECT voi.virtuemart_order_item_id , voi.virtuemart_product_id ,  voi.product_quantity, voi.product_item_price ,
		vp.virtuemart_vendor_id , 
		vo.virtuemart_user_id , vo.order_number , vo.order_currency , vo.customer_note , vo.created_on ,
		vu.city ,  vu.zip , 
		vc.country_name ,
		vs.state_name 
		FROM #__virtuemart_order_items voi 
		LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_product_id = voi.virtuemart_product_id 
		LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
		LEFT JOIN #__virtuemart_orders vo ON vo.virtuemart_order_id = voi.virtuemart_order_id 
		LEFT JOIN #__virtuemart_userinfos vu ON vu.virtuemart_user_id = vo.virtuemart_user_id 
		LEFT JOIN #__users u ON u.id = vo.virtuemart_user_id 
		LEFT JOIN #__virtuemart_countries vc ON vc.virtuemart_country_id = vu.virtuemart_country_id 
		LEFT JOIN #__virtuemart_states vs ON vs.virtuemart_state_id = vu.virtuemart_state_id 
		WHERE vv.virtuemart_user_id = '".$user->id."' AND vo.order_status='C' 
		AND voi.created_on >= '".$start_date."' 
		AND voi.created_on <= '".$end_date."' 
		GROUP BY ".$orderby." 
		ORDER BY voi.virtuemart_order_item_id DESC 
		 ";
		$db->setQuery($q);
		
		
		$this->column_chart = $db->loadObjectList();
		return $this->column_chart;
	}*/
	
	
	public function getMyreviews() 
	{
		$db = JFactory::getDBO();
		if (!class_exists('VmConfig')) {
                    require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
                }
                VmConfig::loadConfig();
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$naming 		= $cparams->get('naming', 'username');
		$user 			= JFactory::getUser();
		$app 			= JFactory::getApplication();
		// Get the pagination request variables
		$limit 			= $app->getUserStateFromRequest('com_vmvendor.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart 	= $app->input->get('limitstart', 0, 'INT');		
		$q ="SELECT vrr.virtuemart_rating_review_id , vrr.virtuemart_product_id, vrr.comment , vrr.review_rating , vrr.created_on ,
		vrr.published , 
		u.id , u.".$naming." , 
		vpc.virtuemart_category_id , 
		vp.product_sku ,
		vpl.product_name
		 FROM `#__virtuemart_rating_reviews` vrr 		 
		 LEFT JOIN #__users u ON u.id = vrr.created_by 
		 LEFT JOIN `#__virtuemart_product_categories` vpc ON vpc.virtuemart_product_id = vrr.virtuemart_product_id 
		 LEFT JOIN #__virtuemart_products vp ON vp.virtuemart_product_id = vrr.virtuemart_product_id 
		 LEFT JOIN #__virtuemart_products_".VMLANG." vpl ON vpl.virtuemart_product_id = vrr.virtuemart_product_id 
		 LEFT JOIN #__virtuemart_vendors vv ON vv.virtuemart_vendor_id = vp.virtuemart_vendor_id 
		 WHERE vv.created_by='".$user->id."'
		 ORDER BY vrr.published ASC , vrr.virtuemart_rating_review_id DESC " ;		
		$db->setQuery($q);
		$total_reviews = $this->_getListCount($q);
		$myreviews = $this->_getList($q, $limitstart, $limit);
		return array($myreviews, $total_reviews, $limit, $limitstart);
	}
	
	
	
	
	
	public function getMyTaxes() 
	{
		$db 		= JFactory::getDBO();
		$user 		= JFactory::getUser();
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vendors` WHERE `created_by`='".$user->id."' "; 
		$db->setQuery($q);
		$my_vendorid = $db->loadResult();
		$q ="SELECT vc.`virtuemart_calc_id` , vc.`virtuemart_vendor_id`,  vc.`calc_name` , vc.`calc_descr` ,
		 vc.`calc_kind` , vc.`calc_value_mathop` , vc.`calc_value` , vc.`calc_currency` ,  vc.`ordering`  ,  vc.`shared` 
			FROM `#__virtuemart_calcs` vc 
			WHERE vc.`published`='1' 
			AND (vc.`shared` ='1' OR  vc.`virtuemart_vendor_id`='".$my_vendorid."' ) 
	
			AND (vc.`publish_up`='0000-00-00 00:00:00' OR vc.`publish_up` <= NOW() )  
			AND (vc.`publish_down`='0000-00-00 00:00:00' OR vc.`publish_down` >= NOW() ) 	
			ORDER BY vc.`shared` DESC , vc.`ordering` ASC , vc.`virtuemart_calc_id` ASC ";
		$db->setQuery($q);  //		AND vc.`calc_vendor_published` ='1' 
		$this->mytaxes = $db->loadObjectList();
		return $this->mytaxes;
	}
	
	
	
	function getVendorprofileItemid()
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE `link`='index.php?option=com_vmvendor&view=vendorprofile' AND `type`='component'  AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  AND access='1' ";
		$db->setQuery($q);
		return $profile_itemid = $db->loadResult();
	
	}
	
	function getShipmentExtensionID()
	{
		$db 	= JFactory::getDBO();
	 	$q = "SELECT extension_id 
		FROM `#__extensions` WHERE enabled='1' AND folder = 'vmshipment' AND element='weight_countries'";
		$db->setQuery($q);
		$extension_id = $db->loadResult();
		return $extension_id;
	}

	static function getMyShipments()
	{
		$db 	= JFactory::getDBO();
		$user   = JFactory::getUser();
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
	 	$q = "SELECT vs.virtuemart_shipmentmethod_id , vs.shipment_params, vs.published ,
	 	vsl.shipment_name, vsl.shipment_desc 
		FROM `#__virtuemart_shipmentmethods` vs 
		JOIN `#__virtuemart_shipmentmethods_".VMLANG."` vsl 
		ON vsl.virtuemart_shipmentmethod_id = vs.virtuemart_shipmentmethod_id
		WHERE vs.shipment_element ='weight_countries' AND vs.shared='0'  
		AND vs.virtuemart_vendor_id='".VmvendorModelDashboard::getVendorid()."' 
		ORDER BY vs.published DESC, vsl.shipment_name ASC ";

		$db->setQuery($q);
		$myshipments = $db->loadObjectList();
		return $myshipments;
	}

	static function getCountryName($id)
	{ // used in Myshipments list
		$db 	= JFactory::getDBO();
		$q = "SELECT country_name FROM #__virtuemart_countries WHERE virtuemart_country_id='".$id."'";
		$db->setQuery($q);
		$country_name = $db->loadResult();
		return $country_name;
	}
	static function getShipmentTaxName($id)
	{ // used in Myshipments list
		$db 	= JFactory::getDBO();
		$q = "SELECT calc_name FROM #__virtuemart_calcs WHERE virtuemart_calc_id='".$id."'";
		$db->setQuery($q);
		$calc_name = $db->loadResult();
		return $calc_name;
	}

	static function getVendorid() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();

		return $virtuemart_vendor_id;
	}
	
}