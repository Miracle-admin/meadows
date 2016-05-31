<?php

/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$preserve = $params->get('preserve');
?>
<div id="companies-search" class="business-directory<?php echo $moduleclass_sfx ?>">
	<div id="searchform" class="ui-tabs <?php echo $layoutType?>">
			<?php $title = $params->get('title'); ?>
			<?php if(!empty($title)){ ?>
				<h1><?php echo $title ?></h1>
			<?php } ?>
			
			<?php $description = $params->get('description'); ?>
			<?php if(!empty($description)){ ?>
				<p><?php echo $description ?></p>
			<?php } ?>
			
			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory'.$menuItemId) ?>" method="post" name="keywordSearch" id="keywordSearch" onsubmit="return checkSearch()">
			
				<input type='hidden' name='view' value='events'>
				<input type='hidden' name='preserve' value='<?php echo $preserve?>'>
				
				<div class="form-container">
					<?php if($params->get('showKeyword')){ ?>
						<div class="form-field">
							<input class="search-field" type="text" placeholder="<?php echo JText::_("LNG_SEARCH")?>" name="searchkeyword" id="searchkeyword" value="<?php echo $preserve?$session->get('ev-searchkeyword'):"";?>" />
						</div>
					<?php } ?>
				
					<?php if($params->get('showTypes')){ ?>
					<div class="form-field">
						<select name="typeSearch" id="typeSearch">
							<option value="0"><?php echo JText::_("LNG_ALL_TYPES") ?></option>
							<?php foreach($types as $type){?>
								<option value="<?php echo $type->id?>" <?php  echo $session->get('ev-typeSearch')==$type->id && $preserve?" selected ":"" ?> ><?php echo $type->name?></option>
							<?php } ?>
						</select>
					</div>
					<?php }?>
					
					
					<?php if($params->get('showStartDate')){ ?>
						<div class="form-field">
							<?php echo JHTML::calendar($startDate,'startDate','startDate',$appSettings->calendarFormat, array('class'=>'dir-date','onchange'=>'', 'placeholder'=>JText::_("LNG_START_DATE"))); ?>
						</div>
					<?php } ?>
					
					<?php if($params->get('showStartDate')){ ?>
						<div class="form-field">
								<?php echo JHTML::calendar($endDate,'endDate','endDate',$appSettings->calendarFormat, array('class'=>'dir-date','onchange'=>'', 'placeholder'=>JText::_("LNG_END_DATE"))); ?>
						</div>
					<?php } ?>
					
					<?php if($params->get('showZipcode')){ ?>
						<div class="form-field">
							<input class="search-field" placeholder="<?php echo JText::_("LNG_ZIPCODE")?>" type="text" name="zipcode" id="zipcode" value="<?php $session = JFactory::getSession(); echo $preserve?$session->get('of-zipcode'):"";?>" />
						</div>
					<?php } ?>
					
					<?php if($params->get('showCities')){ ?>
					<div class="form-field">
						<select name="citySearch" id="citySearch">
							<option value="0"><?php echo JText::_("LNG_ALL_CITIES") ?></option>
							<?php foreach($cities as $city){?>
								<option value="<?php echo $city->city?>" <?php $session = JFactory::getSession(); echo $session->get('of-citySearch')==$city->city && $preserve?" selected ":"" ?> ><?php echo $city->city?></option>
							<?php }?>
						</select>
					</div>
					<?php } ?>
					
					<?php if($params->get('showRegions')){ ?>
					<div class="form-field">
						<select name="regionSearch" id="regionSearch">
							<option value="0"><?php echo JText::_("LNG_ALL_REGIONS") ?></option>
							<?php foreach($regions as $region){?>
								<option value="<?php echo $region->county?>" <?php $session = JFactory::getSession(); echo $session->get('of-regionSearch')==$region->county && $preserve?" selected ":"" ?> ><?php echo $region->county?></option>
							<?php }?>
							
						</select>
					</div>
					<?php } ?>
				</div>
				
				<button type="submit" class="ui-dir-button ui-dir-button-green search-dir-button">
					<i class="dir-icon-search"></i>
					<span class="ui-button-text"><?php echo JText::_("LNG_SEARCH")?></span>
				</button>
			</form>
	</div>
	<div class="clear"></div>
</div>


<script>

function checkSearch(){ 
	<?php if($params->get('mandatoryKeyword')){ ?>
		if(document.getElementById('searchkeyword') && jQuery("#searchkeyword").val().length == 0){
			jQuery("#searchkeyword").focus();
			return false;
		}
	<?php } ?>

	<?php if($params->get('mandatoryTypes')){ ?>
		var foo = document.getElementById('typeSearch');
		if (foo)
		{
		   if (foo.selectedIndex == 0)
		   {
			   jQuery("#typeSearch").focus();
			   jQuery("#typeSearch_chosen span").trigger("click");
			   return false;
		   } 
		}
	<?php } ?>

	<?php if($params->get('mandatoryStartDate')){ ?>
		if(document.getElementById('startDate') && jQuery("#startDate").val().length == 0){
			jQuery("#startDate").focus();
			return false;
		}
	<?php } ?>

	<?php if($params->get('mandatoryEndDate')){ ?>
		if(document.getElementById('endDate') && jQuery("#endDate").val().length == 0){
			jQuery("#endDate").focus();
			return false;
		}
	<?php } ?>

	<?php if($params->get('mandatoryCities')){ ?>
	var foo = document.getElementById('citySearch');
	if (foo)
	{
	   if (foo.selectedIndex == 0)
	   {
		   jQuery("#citySearch").focus();
		   return false;
	   } 
	}
<?php } ?>

<?php if($params->get('mandatoryRegions')){ ?>
	var foo = document.getElementById('regionSearch');
	if (foo)
	{
	   if (foo.selectedIndex == 0)
	   {
		   jQuery("#regionSearch").focus();
			return false;
	   } 
	}
<?php } ?>
	
	
	return true;
}

jQuery(document).ready(function(){
	<?php if($params->get('autocomplete')){?>
	    jQuery("#typeSearch").chosen();
	    jQuery("#citySearch").chosen();
	    jQuery("#regionSearch").chosen();
	 <?php } ?> 
});

</script>



