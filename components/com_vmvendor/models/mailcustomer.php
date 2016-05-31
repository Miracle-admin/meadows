<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class VmvendorModelMailcustomer extends JModelItem
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	public function getCustomercontacts()
	{
		$cparams 					= JComponentHelper::getParams('com_vmvendor');
		$app						= JFactory::getApplication();
		$naming 					= $cparams->get('naming');
		$db = JFactory::getDBO();
		$q = "SELECT `".$naming."`, `email` FROM `#__users` WHERE `id`='".$app->input->get('customer_userid','','INT')."' ";
		$db->setQuery($q);
		$this->customercontacts = $db->loadRow();
		return $this->customercontacts;
	}
	
	public function getOrderItem()
	{
		$db 	= JFactory::getDBO();
		$app	= JFactory::getApplication();
		$q = "SELECT  voi.`virtuemart_product_id` , voi.`order_item_sku` , voi.`order_item_name`, voi.`product_quantity` ,
		vo.`order_number`
		FROM `#__virtuemart_order_items` voi 
		LEFT JOIN `#__virtuemart_orders` vo ON vo.`virtuemart_order_id` = voi.`virtuemart_order_id` 
		WHERE voi.`virtuemart_order_item_id` = '".$app->input->get('orderitem_id','','INT')."' ";
		$db->setQuery($q);
		$this->orderitem = $db->loadRow();
		return $this->orderitem;
	}
	
	public function getGuestContact()
	{
		$db 			= JFactory::getDBO();
		$app			= JFactory::getApplication();
		$orderitem_id 	= $app->input->get('orderitem_id','','INT');
		$q = "SELECT  vou.`first_name` , vou.`middle_name` , vou.`last_name` , vou.`email` 
		FROM `#__virtuemart_order_userinfos` vou 
		JOIN  #__virtuemart_order_items voi ON vou.virtuemart_order_id = voi.virtuemart_order_id 
		WHERE vou.address_type= 'BT' 
		AND voi.virtuemart_order_item_id ='".$orderitem_id."' ";
		$db->setQuery($q);
		$this->orderitem = $db->loadRow();
		return $this->orderitem;
	}	
}
?>