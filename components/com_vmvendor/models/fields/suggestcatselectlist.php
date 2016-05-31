<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2015 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : http://www.nordmograph.com/extensions
 */
  
 defined('JPATH_BASE') or die; 
 /* 
 jimport('joomla.html.html'); 
 jimport('joomla.form.formfield'); 
 jimport('joomla.form.helper'); 
 */ 
 JFormHelper::loadFieldClass('sql'); 
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldSuggestcatselectlist extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'suggestcatselectlist'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
         // Initialize variables. 
        
         $html = ''; 
  		
         // Load user 
         $user = JFactory::getUser(); 
         $user_id = $user->get('id'); 
		 
		 $app = JFactory::getApplication();
		echo $cat_id = $app->input->get('jform[suggestcatselectlist]');
		 
		 
		
  
         // do the SQL  
		
		 $virtuemart_vendor_id = $this->getVendorid();
		 
		  $subcats_array =  array();
			 //echo '<select name = "'.$this->name.'" class="catseleclist">';
			 echo '<select name = "jform[suggestcatselectlist]" class="required" required >';
			 echo '<option value="" >'.JText::_('COM_VMVENDOR_CATSUGGEST_CHOOSECAT').'</option>';
			echo  '<option value="0" >'.JText::_('COM_VMVENDOR_CATSUGGEST_ROOT').'</option>';
			
			if (!class_exists( 'VmConfig' ))
			require JPATH_ADMINISTRATOR .  '/components/com_virtuemart/helpers/config.php';
		VmConfig::loadConfig();
	
	function traverse_tree_down( $category_id, $level )
	{
		$db 	= JFactory::getDBO();	
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$forbidcatids 	= $cparams->get('forbidcatids');
		$onlycatids 	= $cparams->get('onlycatids');
		$banned_cats = explode(',',$forbidcatids);
		$prefered_cats = explode(',',$onlycatids);
		
		
		$level++;
		$q = "SELECT * FROM `#__virtuemart_categories_".VMLANG."` AS vmcl, `#__virtuemart_category_categories` AS vmcc,   `#__virtuemart_categories` AS vmc
			WHERE vmcc.`category_parent_id` = '".$category_id."' 
			AND vmcl.`virtuemart_category_id` = `category_child_id` 
			AND vmc.`virtuemart_category_id` = vmcl.`virtuemart_category_id` 
			AND vmc.`published`='1' ";
		foreach($banned_cats as $banned_cat){
			$q .= "AND vmc.`virtuemart_category_id` !='".$banned_cat."' ";
		}
		if($onlycatids !=''){
			$q .= " AND vmc.`virtuemart_category_id` IN (". implode(',', $prefered_cats ) .") ";
			
		}	
		$q .= "	ORDER BY vmc.`ordering` ASC ";
		$db->setQuery($q);
		$cats = $db->loadObjectList();
		foreach($cats as $cat)
		{
			echo '<option value="'.$cat->virtuemart_category_id.'" >';
			$parent =0;
			for ($i=1; $i<$level; $i++)
			{
				echo ' . ';
			}
			if($level >1)
				echo '  |_ ';
			echo JText::_($cat->category_name).'</option>';
			traverse_tree_down( $cat->category_child_id, $level );
		}
	}
			$traverse = traverse_tree_down(0,0 );
	echo '</select>';
			  
			  
			  
			  
		
	
         // return the HTML 
        // return $data; 
     } 
	 
	 public function getVendorid() 
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user->id."' ";
		$db->setQuery($q);
		$virtuemart_vendor_id = $db->loadResult();
		$this->_virtuemart_vendor_id = $virtuemart_vendor_id;
		return $this->_virtuemart_vendor_id;
	}
	  

 } 
 ?> 