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

$result = array();
$columns = 3;
$colLenght = intval(round(count($categories)/$columns));

$tmp = array();

array_unshift($categories,"a");

for($i = 0 ; $i<=$colLenght; $i++){ 
	$result[] = $categories[$i];
	if(isset($categories[$i+$colLenght+1]))
	$result[] = $categories[$i+$colLenght+1];
	if(isset($categories[$i+2*$colLenght+2]))
	$result[] = $categories[$i+2*$colLenght+2];
}					
$categories= $result;
unset($categories[0]);

?>
<style>
.ui-autocomplete {
		width: 630px!important;
		padding: 20px;
	}

	.ui-menu .ui-menu-item a{
		display: inline!important;
		
	}
	
	 .ui-autocomplete li{
		float: left;
		width: 190px !important;
		background: #CECECE;
		margin-right: 10px!important;
		padding: 2px !important;
	 }
</style>

<div id="companies-search" class="business-directory<?php echo $moduleclass_sfx ?>">
	<!-- strong id="current-search"><?php echo JText::_("LNG_FIND_BUSINESS")?></strong-->
	
	<div id="searchform" class="ui-tabs <?php echo $layoutType?>">
			<?php $title = $params->get('title'); ?>
			<?php if(!empty($title)){ ?>
				<h1><?php echo $title ?></h1>
			<?php } ?>
			
			<?php $description = $params->get('description'); ?>
			<?php if(!empty($description)){ ?>
				<p><?php echo $description ?></p>
			<?php } ?>
			
			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="keywordSearch" id="keywordSearch">
				<input type='hidden' name='task' value='searchCompaniesByName'>
				<input type='hidden' name='controller' value='search'>
				<input type='hidden' name='view' value='search'>
				<div class="form-container">
					<div class="form-field">
						<span class="label">
							<label onclick="hideLabel()" id="keywordLabel" for="keywordSearch" style="opacity: 1"><?php echo JText::_("LNG_BUSINESS_NAME_OR_CATEGORY")?></label>
							<input class="search-field" onclick="hideLabel()" onblur="showLabel()" type="text" name="searchkeyword" id="searchkeyword" value="<?php $session = JFactory::getSession(); echo $session->get('searchkeyword');?>" />
						</span>
					</div>
					<?php if($params->get('showCategories')){ ?>
					<div class="form-field">
						<span class="label">
							<select name="categorySearch" id="categories">
								<option value="0"><?php echo JText::_("LNG_ALL_CATEGORIES") ?></option>
								<?php foreach($categories as $category){?>
									<option value="<?php echo $category->id?>" <?php $session = JFactory::getSession(); echo $session->get('categorySearch')==$category->id?" selected ":"" ?> ><?php echo $category->name?></option>
								<?php }?>
								
							</select>
						</span>
					</div>
					<?php }?>
					
					<?php if($params->get('showZipcode')){ ?>
					<div class="form-field">
						<span class="label">
							<label onclick="hideLabel()" id="zipcode-label" for="zipcode" style="opacity: 1"><?php echo JText::_("LNG_ZIPCODE")?></label>
							<input class="search-field" onclick="hideZipcodeLabel()" onblur="showZipcodeLabel()" type="text" name="zipcode" id="zipcode" value="<?php $session = JFactory::getSession(); echo $session->get('zipcode');?>" />
						</span>
					</div>
					<?php } ?>
					
					<?php if($params->get('showCities')){ ?>
					<div class="form-field">
						<span class="label">
							<select name="citySearch" id="citySearch">
								<option value="0"><?php echo JText::_("LNG_ALL_CITIES") ?></option>
								<?php foreach($cities as $city){?>
									<option value="<?php echo $city->city?>" <?php $session = JFactory::getSession(); echo $session->get('citySearch')==$city->city?" selected ":"" ?> ><?php echo $city->city?></option>
								<?php }?>
								
							</select>
						</span>
					</div>
					<?php } ?>
					
					<?php if($params->get('showRegions')){ ?>
					<div class="form-field">
						<span class="label">
							<select name="regionSearch" id="regionSearch">
								<option value="0"><?php echo JText::_("LNG_ALL_REGIONS") ?></option>
								<?php foreach($regions as $region){?>
									<option value="<?php echo $region->county?>" <?php $session = JFactory::getSession(); echo $session->get('regionSearch')==$region->county?" selected ":"" ?> ><?php echo $region->county?></option>
								<?php }?>
								
							</select>
						</span>
					</div>
					<?php } ?>
					
					<?php if($params->get('showCountries')){ ?>
					<div class="form-field">
						<span class="label">
							<select name="countrySearch" id="countrySearch">
								<option value="0"><?php echo JText::_("LNG_ALL_COUNTRIES") ?></option>
								<?php foreach($countries as $country){?>
									<option value="<?php echo $country->id?>" <?php $session = JFactory::getSession(); echo $session->get('countrySearch')==$country->id?" selected ":"" ?> ><?php echo $country->country_name?></option>
								<?php }?>
								
							</select>
						</span>
					</div>
					<?php } ?>
				</div>
				
				<button type="submit" class="ui-dir-button-green search-dir-button">
					<span class="ui-button-text"><?php echo JText::_("LNG_SEARCH")?></span>
				</button>
				
				<a  style="display:none" id="categories-link" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&controller=categories&view=categories&task=displaycategories') ?>"><?php echo JText::_("LNG_CATEGORY_LIST")?></a>
			</form>
	</div>
	<div class="clear"></div>
</div>


<script>

function checkSearch(){ 

	if(jQuery("#searchkeyword").val().length == 0){
		jQuery("#searchkeyword").focus();
		var label = document.getElementById("keywordLabel");
		label.style.display ="none";
		return false;
	}
	return true;
}

function hideLabel(){
	var label = document.getElementById("keywordLabel");
	label.style.display ="none";
	jQuery("#searchkeyword").focus();
}

function showLabel(){
	var element = document.getElementById("searchkeyword");
	var label = document.getElementById("keywordLabel");
	if(!element.value){
		label.style.display ="block";
	}else{
		
		label.style.display ="none";
	}
}

function hideZipcodeLabel(){
	var label = document.getElementById("zipcode-label");
	label.style.display ="none";
	jQuery("#zipcode").focus();
}

function showZipcodeLabel(){
	var element = document.getElementById("zipcode");
	var label = document.getElementById("zipcode-label");

	if(element == null){
		return;
	}
	
	if(!element.value){
		label.style.display ="block";
	}else{
		label.style.display ="none";
	}
}

<?php if($params->get('autocomplete')){?>

jQuery(document).ready(function(){
    jQuery("#categories").combobox();

    jQuery("#citySearch").combobox();
    jQuery("#regionSearch").combobox();
    jQuery("#countrySearch").combobox();
});

<?php } ?>
showLabel();
showZipcodeLabel();
</script>



