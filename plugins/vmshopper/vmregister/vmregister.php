<?php
// no direct access
defined( '_JEXEC' ) or die;
 if (!class_exists('VmConfig')) {
	require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
}

if (!class_exists('vmShopperPlugin')) {
	require(JPATH_VM_PLUGINS . DS . 'vmShopperPlugin.php');
}
class plgVmShopperVmregister extends vmShopperPlugin
{

//triggers on on after user store
function plgVmAfterUserStore($data)
{
jimport('joomla.user.helper'); 
//get the user id
$user_id=$data['virtuemart_user_id'];
//add user to the buyer group
JUserHelper::addUserToGroup($user_id, 12);
}

//abstract method
public function plgVmOnUpdateOrderBEShopper($_orderID){
echo";l;l;l";die;
	}

}
?>