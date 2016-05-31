<?php

/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Vm2wishlists model.
 */
class Vm2wishlistsModelList extends JModelItem {

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
        $app = JFactory::getApplication('com_vm2wishlists');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_vm2wishlists.edit.list.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_vm2wishlists.edit.list.id', $id);
        }
        $this->setState('list.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if (isset($params_array['item_id'])) {
            $this->setState('list.id', $params_array['item_id']);
        }
        $this->setState('params', $params);
    }

    /**
     * Method to get an ojbect.
     *
     * @param	integer	The id of the object to get.
     *
     * @return	mixed	Object on success, false on failure.
     */
    public function &getData($id = null) {
        if ($this->_item === null) {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('list.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id)) {
                // Check published state.
                if ($published = $this->getState('filter.published')) {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = JArrayHelper::toObject($properties, 'JObject');
            } elseif ($error = $table->getError()) {
                $this->setError($error);
            }
        }

        
					$this->_item->privacy = JText::_('COM_VM2WISHLISTS_LISTS_PRIVACY_OPTION_' . $this->_item->privacy);
		if ( isset($this->_item->created_by) ) {
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

        return $this->_item;
    }

    public function getTable($type = 'List', $prefix = 'Vm2wishlistsTable', $config = array()) {
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to check in an item.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkin($id = null) {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('list.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param	integer		The id of the row to check out.
     * @return	boolean		True on success, false on failure.
     * @since	1.6
     */
    public function checkout($id = null) {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('list.id');

        if ($id) {

            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = JFactory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    public function getCategoryName($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('title')
                ->from('#__categories')
                ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public function publish($id, $state) {
        $table = $this->getTable();
        $table->load($id);
        $table->state = $state;
        return $table->store();
    }

    public function delete($id) {
        $table = $this->getTable();
        return $table->delete($id);
    }
	
	public function getProducts()
	{
		$this->item = $this->get('Data');
		
		$db 		= JFactory::getDbo();
		$app 		= JFactory::getApplication();
		$format		= $app->input->get('format');
		$user 		= JFactory::getUser();
		$lang 		= JFactory::getLanguage();
		$langtag 	= $lang->get('tag');
		$dblangtag 	= strtolower(str_replace('-','_',$langtag));
		
		if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();	
			
		$userid 	= $app->input->getInt('userid');
		
		
		
		if(!$userid)
			$userid = $user->id;
		
		$listid = $app->input->getInt('id');
		if($listid =='')
		{
			$menus= $app->getMenu();
		 	$menu = $menus->getActive();
		 	$listid = $menu->params->get('item_id');
		}
		require_once JPATH_BASE.'/components/com_vm2wishlists/helpers/vm2wishlists.php';
		
			
		$privacy = Vm2wishlistsFrontendHelper::getListPrivacy( $listid );
		if($privacy!='0' && $format=='feed')
			return false;
		 
		$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
		$naming 		= $cparams->get('naming', 'username');
		$limit 			= $cparams->get('limit', '50');
		 
		$limitstart = $app->input->get('limitstart', 0, 'INT');	
		
		
		
		if(  isset($_COOKIE['vm2wishlists-'.$listid]) && ( $user->id ==  $userid )  && $user->id >0 )
		{  // this is my list page, I have a list cookie and I am loggedin:
			if($user->id)
			{ // import wishlist to DB
				$j =0;
				if( strpos( $_COOKIE['vm2wishlists-'.$listid] , ',') ){
					$productz = explode( ',' , $_COOKIE['vm2wishlists-'.$listid] );
					for($i=0 ; $i < count($productz) ;$i++)
					{
						if( $productz[$i] !='' && $productz[$i] !="0")
						{
							$q ="SELECT COUNT(*) FROM `#__vm2wishlists_items` WHERE `virtuemart_product_id` = ".$productz[$i]." ";
							$db->setQuery($q);
							$yet_in = $db->loadResult();
							if( $yet_in <1 )
							{
								$q = "INSERT INTO `#__vm2wishlists_items` ( `virtuemart_product_id` , `listid` , `userid` , `date_added` )
								VALUES ( '".$productz[$i]."' , '".$listid."' , '".$user->id."' , '".date('Y-m-d H:i:s')."') ";
								$db->setQuery( $q );
								if (!$db->query()) die($db->stderr(true));
								$j++;
							}
						}
					}
				}
				elseif($_COOKIE['vm2wishlists-'.$listid] !='' && $_COOKIE['vm2wishlists-'.$listid]!="0")
				{
					$q ="SELECT COUNT(*) FROM `#__vm2wishlists_items` 
					WHERE `virtuemart_product_id` = ".$_COOKIE['vm2wishlists-'.$listid]." ";
					$db->setQuery($q);
					$yet_in = $db->loadResult();
					if( $yet_in <1 )
					{
						$q = "INSERT INTO `#__vm2wishlists_items` ( `virtuemart_product_id` , `listid` , `userid` , `date_added` )
						VALUES 
						( '".$_COOKIE['vm2wishlists-'.$listid]."' , '".$listid."' , '".$user->id."' , '".date('Y-m-d H:i:s')."') ";
						$db->setQuery( $q );
						if (!$db->query()) die($db->stderr(true));
						$j++;
						$productz[] = $_COOKIE['vm2wishlists-'.$listid];
					}
				}
				setcookie ("vm2wishlists-".$listid , '' , time()  , "/"); // empty cookie
				if($j>0)
				{
					$message = $j. ' '. JText::_( 'COM_VM2WISHLISTS_IMPORTTODB' );
					$app->enqueueMessage( $message );
				}
				$app->redirect( 'index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$user->id.'&Itemid='.$app->input->getInt('Itemid') );
			}	
		}
		elseif( isset($_COOKIE['vm2wishlists-'.$listid]) && ( $user->id ==  $userid ) && $user->id =='0' )
		{  // not logued in, but cookie set we list from cookie
			if( strpos( $_COOKIE['vm2wishlists-'.$listid] , ',') )
			{
				$productz = explode( ',' , $_COOKIE['vm2wishlists-'.$listid] );
				
			}
			elseif($_COOKIE['vm2wishlists-'.$listid] !='' && $_COOKIE['vm2wishlists-'.$listid]!="0")
			{
						$j = 1;
						$productz[] = $_COOKIE['vm2wishlists-'.$listid];
			}
			$productz = implode(',' , $productz);
	
			$q = "SELECT DISTINCT(vmp.virtuemart_product_id),
			 vmp.virtuemart_vendor_id , vmp.product_sku sku ,  vmp.product_in_stock , vmp.product_ordered , 
			vmpl.product_name name, vmpl.product_s_desc , 
			vmcl.category_name , vmcl.virtuemart_category_id,
			vmm.`file_url` , vmm.file_url_thumb ,
			vpp.product_price,
			vrr.comment, vrr.review_rates 
			FROM #__virtuemart_products vmp 
			JOIN #__virtuemart_products_".VMLANG."  vmpl ON vmpl.virtuemart_product_id = vmp.virtuemart_product_id 
			JOIN #__virtuemart_product_categories vmpc ON vmpc.virtuemart_product_id = vmp.virtuemart_product_id 
			JOIN #__virtuemart_categories_".VMLANG." vmcl ON vmcl.virtuemart_category_id = vmpc.virtuemart_category_id 
			LEFT JOIN #__virtuemart_product_medias vmpm ON vmp.virtuemart_product_id  = vmpm.virtuemart_product_id AND (vmpm.ordering='0' OR vmpm.ordering='1' )
			LEFT JOIN #__virtuemart_medias vmm ON vmpm.virtuemart_media_id  = vmm.virtuemart_media_id  AND SUBSTR( vmm.file_mimetype , 1 ,6 ) = 'image/' AND vmm.published='1'  
			LEFT JOIN #__virtuemart_rating_reviews vrr ON vmp.virtuemart_product_id = vrr.virtuemart_product_id AND vrr.created_by = '".$userid."' 
			LEFT JOIN #__virtuemart_product_prices vpp ON  vmp.virtuemart_product_id = vpp.virtuemart_product_id 
			WHERE vmp.published='1' 		
			AND vmp.virtuemart_product_id IN(".$productz.")";
		//$q .= " GROUP BY vmcl.virtuemart_category_id " ;
			if( $app->input->getInt('del')!='0' )
				$q .= " AND vmp.virtuemart_product_id !='".$app->input->getInt('del')."' ";
			$q .= " GROUP BY  vmp.virtuemart_product_id 
			 ORDER BY vmcl.virtuemart_category_id ASC " ;	
		}
		else
		{
	
			$q = "SELECT DISTINCT(vmp.virtuemart_product_id) ,
			 vwi.virtuemart_product_id , vwi.id, vwi.date_added,   
			vmp.virtuemart_vendor_id , vmp.product_sku sku, vmp.product_in_stock , vmp.product_ordered , 
			vmpl.product_name name, vmpl.product_s_desc , 
			vmcl.category_name , vmcl.virtuemart_category_id,
			vmm.`file_url` , vmm.file_url_thumb ,
			vpp.product_price,
			vrr.comment, vrr.review_rates 
			FROM `#__vm2wishlists_items` vwi 
			JOIN #__virtuemart_products vmp ON vmp.virtuemart_product_id = vwi.virtuemart_product_id 
			JOIN #__virtuemart_products_".VMLANG."  vmpl ON vmpl.virtuemart_product_id = vwi.virtuemart_product_id 
			JOIN #__virtuemart_product_categories vmpc ON vmpc.virtuemart_product_id = vwi.virtuemart_product_id 
			JOIN #__virtuemart_categories_".VMLANG." vmcl ON vmcl.virtuemart_category_id = vmpc.virtuemart_category_id 
			LEFT JOIN #__virtuemart_product_medias vmpm ON vwi.virtuemart_product_id  = vmpm.virtuemart_product_id AND (vmpm.ordering='0' OR vmpm.ordering='1' )
			LEFT JOIN #__virtuemart_medias vmm ON vmpm.virtuemart_media_id  = vmm.virtuemart_media_id  AND SUBSTR( vmm.file_mimetype , 1 ,6 ) = 'image/' AND vmm.published='1'  
			LEFT JOIN #__virtuemart_rating_reviews vrr ON vwi.virtuemart_product_id = vrr.virtuemart_product_id AND vrr.created_by = '".$userid."' 
			LEFT JOIN #__virtuemart_product_prices vpp ON  vwi.virtuemart_product_id = vpp.virtuemart_product_id 
			WHERE vwi.listid ='".$listid."'  
			AND vwi.userid='".$userid."' 
			AND vmp.published='1' 
			 GROUP BY  vmp.virtuemart_product_id 
			 ORDER BY vmcl.virtuemart_category_id ASC, vwi.id DESC " ;
		
		}
		// LEFT JOIN `#__virtuemart_ratings` vr ON vr.virtuemart_rating_id = vrr.virtuemart_rating_review_id 
		//$this->reviews = $db->loadObjectList();
		// AND vrr.published='1' AND vp.published='1' 
		$db->setQuery($q);
		$total = @$this->_getListCount($q);
		$products = $this->_getList($q, $limitstart, $limit);
		return array($products, $total, $limit, $limitstart);
	}
	
	
	
	
	
	
	
	
	
	
	public function getListdata()
	{
		$db 		= JFactory::getDBO();
		$app 		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$lang 		= JFactory::getLanguage();
		$langtag 	= $lang->get('tag');
		$dblangtag 	= strtolower(str_replace('-','_',$langtag));

		
        //$menuitemid = $app->input->getInt( 'Itemid' );  // this returns the menu id number so you can reference parameters
        
		
		$listid = $app->input->getInt('id');
		if($listid =='')
		{
			$menus= $app->getMenu();
		 	$menu = $menus->getActive();
		 	$listid = $menu->params->get('item_id');
		}

		$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
		$naming 		= $cparams->get('naming', 'username');
		
		$userid = $app->input->getInt('userid');	
		if(!$userid)
			$userid = $user->id;
		
		$q ="SELECT vwl.id , vwl.list_name , vwl.list_description , vwl.icon_class ,vwl.privacy ,
		vwl.amz_link, vwl.amz_base, vwl.amz_prefix ,
		 vwl.ordering ,  vwl.state ,  vwl.created_by ";
		 if($user->id !='0' OR $userid)
			$q .=" ,u.".$naming;

		$q .=" FROM `#__vm2wishlists_lists` vwl ";
		 if($user->id !='0' OR $userid)
		 	$q .=" JOIN #__users u ON u.id = '".$userid."' ";
		$q .=" WHERE vwl.id ='".$listid."' ";
		$db->setQuery($q);
		$this->list_data = $db->loadObject();
		return $this->list_data;
		
		
	}
	
	public function getOwnerdata()
	{
		$app 		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$db 		= JFactory::getDBO();
		$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
		$naming 		= $cparams->get('naming', 'username');
		$profileman 	= $cparams->get('profileman', '0');
		$userid = $app->input->getInt('userid');
		if(!$userid)
			$userid = $user->id;
		
		$q = "SELECT u.".$naming."   ";
		if($profileman=='cb'){
			$q .= ", c.avatar   ";
		}
		elseif($profileman=='js'){
			$q .= ", cu.thumb AS avatar ";
		}
		$q .=" FROM #__users u "	;	
		if($profileman=='cb')
			$q .= "JOIN #__comprofiler c ON c.user_id = u.id ";
		elseif($profileman=='js')
			$q .= "JOIN #__community_users cu ON cu.userid = u.id ";
			
		$q .=" WHERE u.id='".$userid."' ";
			
		$db->setQuery($q);
		$this->owner_data = $db->loadRow();
		return $this->owner_data;
		
	}
	
	/*public function getFriends()
	{
		$app 		= JFactory::getApplication();
		$user 		= JFactory::getUser();
		$db 		= JFactory::getDBO();
		$userid 	= $app->input->getInt('userid');	
		$cparams 	= JComponentHelper::getParams('com_vm2wishlists');
		$profileman = $cparams->getValue('profileman', '0');  // 0: no    cb: CB   js: Jomsocial es:easysocial
		$owner_friends = array();
		if(!$userid)
			$userid = $user->id;
		if($profileman=='es'){
			$q ="SELECT connect_to FROM #__community_connection WHERE  connect_from='".$userid."' ";
			$db->setQuery($q);
			$friends = $db->loadObjectlist();
			if( count($friends) > 0){
				foreach($friends as $friend){
					$owner_friends[] = $friend->connect_to;
				}
			}
		}
		if($profileman=='js'){
			$q ="SELECT connect_to FROM #__community_connection WHERE  connect_from='".$userid."' ";
			$db->setQuery($q);
			$friends = $db->loadObjectlist();
			if( count($friends) > 0){
				foreach($friends as $friend){
					$owner_friends[] = $friend->connect_to;
				}
			}
		}
		return $owner_friends;
	}*/
	
	
	public function getPriceformat() 
	{
		$db = JFactory::getDBO();
		$q ="SELECT curr.*  
		FROM `#__virtuemart_currencies` AS curr 
		LEFT JOIN `#__virtuemart_vendors` AS vend ON vend.`vendor_currency` = curr.`virtuemart_currency_id`  
		WHERE vend.`virtuemart_vendor_id` = '1' ";
		$db->setQuery($q);
		$price_format 	= $db->loadRow();
		$this->_price_format = $price_format;
		return $this->_price_format;
	}
	
	static function applytaxes( $pricebefore, $catid ,  $vendor_id)
	{
			$is_shopper = 1;
			
			$db = JFactory::getDBO();
			$q ="SELECT DISTINCT(vc.`virtuemart_calc_id`) , vc.`calc_name` , vc.`calc_kind` , vc.`calc_value_mathop` , vc.`calc_value` , vc.`calc_currency` ,  vc.`ordering` 
				FROM `#__virtuemart_calcs` vc 
				LEFT JOIN `#__virtuemart_calc_categories` vcc ON vcc.`virtuemart_calc_id` = vc.`virtuemart_calc_id`
				WHERE vc.`published`='1' 
				AND (vc.`shared` ='1' OR vc.`virtuemart_vendor_id` = '".$vendor_id."' )" ;
			if($is_shopper)
				$q .= " AND vc.`calc_shopper_published` = '1' ";
	
			$q .= "AND (vc.`publish_up`='0000-00-00 00:00:00' OR vc.`publish_up` <= NOW() ) ";
			$q .= "AND (vc.`publish_down`='0000-00-00 00:00:00' OR vc.`publish_down` >= NOW() ) 
			AND vcc.`virtuemart_category_id` ='".$catid."' 
                            GROUP BY vc.`virtuemart_calc_id` 
				ORDER BY vc.`ordering` ASC";
			$db->setQuery($q);
			$taxes = $db->loadObjectList();
			$price_withtax = $pricebefore;
			if(count($taxes)>0)
			{
				foreach($taxes as $tax)
				{
					$calc_value_mathop = $tax->calc_value_mathop;
					$calc_value = $tax->calc_value;
					switch ($calc_value_mathop)
					{
						case '+':
							$price_withtax = $price_withtax + $calc_value;
						break;
						case '-':
							$price_withtax = $price_withtax - $calc_value;
						break;
						case '+%':
							$price_withtax = $price_withtax + ( ( $price_withtax * $calc_value ) / 100 );
						break;
						case '-%':
							$price_withtax = $price_withtax - ( ( $price_withtax * $calc_value ) / 100 );
						break;
					}	
				}
			}
			return $price_withtax;	
		}
		
	static function getVMItemid($catid)
	{
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` 
		WHERE (
		`link` LIKE 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=".$catid."%' 
		OR `link` LIKE 'index.php?option=com_virtuemart&view=virtuemart&productsublayout=%' 
		OR `link` LIKE 'index.php?option=com_virtuemart&view=virtuemart%' 
		)
		AND `type`='component'  
		AND ( language ='".$lang->getTag()."' OR language='*') AND published='1'  AND client_id='0' ";
		$db->setQuery($q);
		return $vm_itemid = $db->loadResult();
	}
	
	public function getFriends()
	{
		$user 	= JFactory::getUser();
		$db = JFactory::getDBO();
		$app 	= JFactory::getApplication();
		$userid = $app->input->getInt('userid');	
		$cparams 		= JComponentHelper::getParams('com_vm2wishlists');
		$profileman 	= $cparams->get('profileman', '0');
		$owner_friends = array();
		if(!$userid)
			$userid = $user->id;
		if($profileman=='js')
		{
			$q ="SELECT connect_to FROM #__community_connection WHERE  connect_from='".$userid."' ";
			$db->setQuery($q);
			$friends = $db->loadObjectlist();
			if( count($friends) > 0)
			{
				foreach($friends as $friend)
				{
					$owner_friends[] = $friend->connect_to;
				}
			}
		}
		elseif($profileman=='es')
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
			$my     = Foundry::user();
			if($my->isFriends( $userid ) OR $user->id == $userid)
				$owner_friends[] = $user->id;
				
		}
		elseif($profileman=='cb')
		{
			$q ="SELECT memberid FROM #__comprofiler_members WHERE  referenceid='".$userid."' AND accepted='1' AND pending='0' ";
			$db->setQuery($q);
			$friends = $db->loadObjectlist();
			if( count($friends) > 0)
			{
				foreach($friends as $friend)
				{
					$owner_friends[] = $friend->memberid ;
				}
			}
		}
		return $owner_friends;
	}

}
