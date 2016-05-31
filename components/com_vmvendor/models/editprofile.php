<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class VmvendorModelEditprofile extends JModelForm
{
	/**
	 * @var string msg
	 */
	//protected $msg;
 
	public function getVendordata() 
	{
		$app		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$userid 	= $app->input->get('userid','','INT');
		if(!$userid)
			$userid = $user->id;
		$db 		= JFactory::getDBO();
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();		
		$q ="SELECT  vvl.vendor_store_desc , vvl.vendor_terms_of_service , vvl.vendor_legal_info , vvl.vendor_store_name , vvl.vendor_phone , vvl.vendor_url ,
		vv.virtuemart_vendor_id ,
		vmva.address,  vmva.zip , vmva.city , vmva.virtuemart_state_id , vmva.virtuemart_country_id ,
		vpe.paypal_email 
		FROM #__virtuemart_vendors_".VMLANG." vvl 
		LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id = vvl.virtuemart_vendor_id 
		LEFT JOIN #__vmvendor_vendoraddress vmva ON vmva.vendor_user_id = vv.virtuemart_user_id 
		LEFT JOIN #__vmvendor_paypal_emails vpe ON vpe.userid =  vv.virtuemart_user_id 
		WHERE vv.virtuemart_user_id='".$userid."' " ;	
		$db->setQuery($q);
		$this->vendor_data = $db->loadRow();
		//var_dump($this->vendor_data);
		return $this->vendor_data;
	}
	
	public function getVendorthumb() 
	{
		$app		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$userid 	= $app->input->get('userid','','INT');
		if(!$userid)
			$userid = $user->id;
		$db = JFactory::getDBO();		
		$q ="SELECT vm.file_url_thumb
		FROM #__virtuemart_medias vm 
		LEFT JOIN #__virtuemart_vendor_medias vvm ON vvm.virtuemart_media_id = vm.virtuemart_media_id 
		LEFT JOIN #__virtuemart_vmusers vv ON vv.virtuemart_vendor_id =vvm.virtuemart_vendor_id 
		WHERE vv.virtuemart_user_id = '".$userid."' " ;	
		$db->setQuery($q);
		$this->vendor_thumb = $db->loadResult();
		return $this->vendor_thumb;
	}
	
	public function getJSProfileallowed($profiletypes_ids) 
	{
		$db 				= JFactory::getDBO();
		$user 				= JFactory::getUser();
		$cparams 			= JComponentHelper::getParams('com_vmvendor');
		$profiletypes_mode 	= $cparams->get('profiletypes_mode');		
		$allowed = 0;
		if($profiletypes_mode==1)
			$q = "SELECT profile_id FROM #__community_users WHERE userid='".$user->id."' ";
		if($profiletypes_mode==2)
			$q = "SELECT profiletype FROM #__xipt_users WHERE userid='".$user->id."' ";
		$db->setQuery($q);
		$user_profile_id = $db->loadResult();
		$allowedprofiles_array = array();
		if(strpos( $profiletypes_ids , ',' ) )
		{
			$exploded = explode( ',' , $profiletypes_ids);
			$count = count($exploded);
			for($i= 0 ; $i < $count ; $i++ )
			{
				$allowedprofiles_array[] = $exploded[$i];	
			}		  
		}
		else
			$allowedprofiles_array[] = $profiletypes_ids;
		if(  in_array ($user_profile_id, $allowedprofiles_array ) )
			$allowed	= 1 ;
		return $allowed;
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vmvendor.editprofile', 'editprofile', array('control' => 'jform', 'load_data' => true));
		if (empty($form))
		{
			return false;
		}

		//$id = $this->getState('contact.id');
		//$params = $this->getState('params');
		//$contact = $this->_item[$id];
		//$params->merge($contact->params);
		return $form;
	}
}
?>