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
 class JFormFieldSubcats extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'subcats'; 
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     { 
         // Initialize variables. 
         $html = array(); 
         $html[] = ''; 
  		
        
     } 
	 
	 function getCatmaxprice($catid){
		 $db 						= JFactory::getDBO();
		 $q = "SELECT MAX(ba.price) FROM #__bannerauctions ba 
		 JOIN #__banners b ON b.id = ba.banner_id 
		 WHERE b.catid='".$catid."' AND b.state='1' ";
		 $db->setQuery($q);
		 $cat_maxprice = $db->loadResult();
		 return $cat_maxprice ;
		 
	 }
	 
	function getVendorsSubcats($category_id, $level , $subcats_array , $html)
	{
		$db 						= JFactory::getDBO();
		$app = JFactory::getApplication();
		$banner_id = $app->input->get('banner_id');
		$level++;		
		$q = "SELECT c.id, c.title,  c.description 
			FROM #__categories  c 
			WHERE   c.parent_id ='".$category_id."'  
			AND c.published='1' 
			AND c.access='1' 
			AND c.extension='com_banners' 
			AND c.title LIKE CONCAT('%', '[' , '%') 
			AND c.title LIKE CONCAT('%', 'x' , '%')
			AND c.title LIKE CONCAT('%', ']' , '%') ";
		if($banner_id>0)
		{
			$q = "SELECT c.id, c.title,  c.description 
			FROM #__categories  c 
			WHERE   c.id ='".$category_id."'  
			AND c.published='1' 
			AND c.access='1' 
			AND c.extension='com_banners' 
			LIMIT 1 ";			
		}
		$db->setQuery($q);
		$cats = $db->loadObjectList();
		foreach($cats as $cat){
			$subcats_array[] = $cat->id;
			if(!$banner_id)
				$this->getVendorsSubcats( $cat->id, $level , $subcats_array , $html);
		}
		$j = 0;
		foreach($cats as $i =>$cat)
		{
			$j++;
			$html[] = '<div class="panel panel-info">
						  <div class="panel-heading">
						  <div style="display: inline;">
						<input type = "radio" 
						required="true" 
							 name = "'.$this->name.'"
							 id = "'.$this->id. $i. '"
							 value = "'.$cat->id.'" ';
			if($banner_id >0)
				$html[] = ' checked="checked" ';
			$html[] = ' class="required" ';		
			$html[] = ' /> <label for="'.$this->id. $i. '" style="display:inline;">';
			if($banner_id >0)
				$html[] = '<span class="icon-lock"></span> ';
			$html[] = '<strong>'.$cat->title.'</strong></label></div></div>';
			$html[] = '<div class="panel-body">
				<div>'.$cat->description.'</div>';
			$cat_maxprice = $this->getCatmaxprice($cat->id);
			if($cat_maxprice)
				$html[] = '<div>'.JText::_('COM_BANNERSAUCTIONS_FIELD_SUBCATS_CURRENTPRICE').' <span class="badge badge-success hasTooltip" title="'.JText::_('COM_BANNERSAUCTIONS_FIELD_POINTPRICE_DESC').'">'.$cat_maxprice.'</span></div>';
			$html[] = '<div>';//// we get the modules and pages where it's displayed
			$q ="SELECT me.id , me.title  , me.link   , mm.menuid , mo.params
				FROM #__menu me
				JOIN #__modules_menu mm ON mm.menuid = me.id OR mm.menuid='0' 
				JOIN #__modules mo ON mm.moduleid = mo.id 
				WHERE mo.module='mod_banners' 
				AND mo.access='1' 
				AND mo.published='1' 
				AND me.published='1' 
				AND mo.position IS NOT NULL 
				ORDER BY me.id ASC";
				// mo.params LIKE  '%catid\":[\"\"]%'
			$db->setQuery($q);
			$ad_pages = $db->loadObjectlist();
			$allpages = 0;
			$html[] = JText::_('COM_BANNERSAUCTIONS_FIELD_SUBCATS_PAGES').': ';
			$i = 0;
			foreach($ad_pages as $ad_page)
			{
				$module_params_obj = json_decode($ad_page->params);
				$module_cats = $module_params_obj ->{'catid'};
				if(in_array( $cat->id , $module_cats) )
				{
					$i++;
					if($ad_page->menuid!='0')
						$html[] = ' <a target="_blank" href="'.JRoute::_($ad_page->link.'&Itemid='.$ad_page->menuid).'"> <span class="icon-link"></span>'.$ad_page->title.'</a> ';
					else
						$allpages = 1;						
				}
			}
			if($allpages)
				$html[] = '<div>'.JText::_('COM_BANNERSAUCTIONS_FIELD_SUBCATS_ALLPAGETYPES').'</div>';
			if(!$i)
				$html[] = '<div class="alert alert-warning"><span class="vmv-icon-warning"></span> '.JText::_('COM_BANNERSAUCTIONS_FIELD_SUBCATS_NOTSETONANYPAGE').'</div>' ;
			$html[] = '</div>';
			$html[] = '</div>';
			$html[] = '</div>';
			if( $i==0  && !$banner_id)
				return implode($html);
			elseif($banner_id >0)
				return implode($html);
						
			if(!$banner_id)
				 $this->getVendorsSubcats( $cat->id, $level , $subcats_array, $html);
		}
	}	
} 
?> 