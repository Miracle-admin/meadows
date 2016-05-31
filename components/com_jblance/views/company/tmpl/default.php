<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/user/tmpl/dashboard.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Displays the user Dashboard (jblance)
 */
$model=$this->model;
$app =& JFactory::getApplication();
 ?>
 
 <script type="text/javascript">
 jQuery(function(){
	   jQuery('.checkb').change(function() {
    		var temp=jQuery("#non-checked").val();
		   jQuery("#non-checked").val(temp+' '+jQuery(this).val());
		  var uncheck=jQuery("input:checkbox:not(:checked)").val();
		 jQuery("#filters-form").submit();
	})
	 /*To check uncheck onclick***/
	   jQuery('.parent').click(function(event) {
		var id = jQuery(this).attr('id');
		
		if(jQuery(this).prop("checked")) {
                jQuery('.parent_'+id).prop("checked", true);
            } else {
               
                
		   jQuery('.parent_'+id).each(function(){
				 var temp=jQuery("#non-checked").val();
				jQuery("#non-checked").val(temp+' '+jQuery(this).val());
				jQuery(this).prop("checked", false);
				})
			jQuery("#filters-form").submit();	
            }                
		});
		 /*To check uncheck onclick***/
		
 })
  </script>
  <div class="container">
 <form  method="post" id="filters-form" action="<?php echo JRoute::_('index.php?option=com_jblance&view=company') ?>">
 <?php

 $checkvalarr=array($this->filters);
 print_r($checkvalarr);
 $parents=$this->parents;
	foreach($parents as $parent)
	{
		echo '<input type="checkbox" class="parent chkbx_'.$parent->id.'" id="'.$parent->id.'" name="" checked="true">'.$parent->category.'<br>';
		$children=$model->getChild($parent->id);

	foreach($children as $child)
	{
		if(in_array($child->id,$checkvalarr))
		{
			echo '<input class="checkb parent_'.$parent->id.'" name="category_id['.$child->id.']"  id="checkb parent_'.$parent->id.'" onChange="" type="checkbox" value="'.$child->id.'"/>-- '.$child->category.'<br>';
		}
		else
		{
			echo '<input class="checkb parent_'.$parent->id.'" name="category_id['.$child->id.']"  id="checkb parent_'.$parent->id.'" onChange="" checked="true" type="checkbox" value="'.$child->id.'"/>-- '.$child->category.'<br>';
		}
		$state=$app->getUserState( $child->category);
		$app->setUserState( $child->category, '' );
	}

}
 $mcompany=$this->companies;
 foreach($mcompany as $mycompany)
 {
	 ?>
	 <div class="company_container">
     	<div class="company_name">
        	<?php echo $mycompany->biz_name; ?>
        </div>
        <div class="company_image">
        	<img src="<?php echo JURI::root().'images/jblance/'.$mycompany->picture; ?>"/>
        </div>
        <div class="location">
        	<?php echo $mycompany->title; ?>
        </div>
     </div>
     <?php
	}
	 echo $this->pagination->getListFooter(); 
	
	

	?>
    <input type="text" id="non-checked"  name="filterunchecked"/>
    </form>
</div>	


