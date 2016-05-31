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
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
$preserve = $params->get('preserve');

?>
<div class="module-search-map">
	<?php 
		if($params->get('showMap')){
			require JPATH_SITE.'/components/com_jbusinessdirectory/include/search-map.php';
		} 
	?>
</div>

<div id="companies-search" class="business-directory<?php echo $moduleclass_sfx ?> <?php echo $layoutType?>" >
	<div id="searchform" class="ui-tabs ">
			<?php $title = $params->get('title'); ?>
			<?php if(!empty($title)){ ?>
				<h1><?php echo $title ?></h1>
			<?php } ?>
			
			<?php $description = $params->get('description'); ?>
			<?php if(!empty($description)){ ?>
				<p><?php echo $description ?></p>
			<?php } ?>
			
			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory'.$menuItemId) ?>" method="post" name="keywordSearch" id="keywordSearch" onsubmit="return checkSearch()">
				<div class="form-container">
					<?php if($params->get('showKeyword')){ ?>
						<div class="form-field">
							<input class="search-field" type="text" placeholder="<?php echo JText::_("LNG_BUSINESS_NAME_OR_CATEGORY")?>" name="searchkeyword" id="searchkeyword" value="<?php  echo $preserve?$session->get('searchkeyword'):"";?>" />
						</div>
					<?php } ?>
					<?php if($params->get('showKeyword') && false){ ?>
						<div class="form-field">
							<input class="search-field" placeholder="<?php echo JText::_("LNG_LOCATION")?>" type="text" name="searchkeywordLocation" id="searchkeywordLocation" value="<?php  echo $preserve?$session->get('searchkeywordLocation'):"";?>" />
						</div>
					<?php } ?>
					<?php if($params->get('showCategories')){ ?>
					<div class="form-field">
						<select name="categorySearch" id="categories">
							<option value="0"><?php echo JText::_("LNG_ALL_CATEGORIES") ?></option>
							<?php foreach($categories as $category){?>
								<option value="<?php echo $category->id?>" <?php echo $session->get('categorySearch')==$category->id && $preserve?" selected ":"" ?> ><?php echo $category->name?></option>
								<?php if(!empty($category->subcategories)){?>
									<?php foreach($category->subcategories as $subCat){?>
											<option value="<?php echo $subCat->id?>" <?php  echo $session->get('categorySearch')==$subCat->id && $preserve?" selected ":"" ?> >-- <?php echo $subCat->name?></option>
									<?php }?>
								<?php }?>
							<?php }?>
						</select>
					</div>
					<?php }?>
					
					<?php if($params->get('showTypes')){ ?>
					<div class="form-field">
						<select name="typeSearch" id="typeSearch">
							<option value="0"><?php echo JText::_("LNG_ALL_TYPES") ?></option>
							<?php foreach($types as $type){?>
								<option value="<?php echo $type->id?>" <?php  echo $session->get('typeSearch')==$type->id && $preserve?" selected ":"" ?> ><?php echo $type->name?></option>
							<?php } ?>
						</select>
					</div>
					<?php }?>
					
					<?php if($params->get('showZipcode')){ ?>
						<div class="form-field">
							<div id="dir-search-preferences" style="display:none">
								<h3 class="title"><?php echo JText::_("LNG_SEARCH_PREFERENCES")?><i class="dir-icon-close" onclick="jQuery('#dir-search-preferences').hide()"></i></h3>
								<div class="geo-radius">
									<div><?php echo JText::_("LNG_RADIUS")?> (<?php echo $appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?>)</div>
								</div>
								<div>
									<input type="text" id="geo-location-radius" name="radius" value="<?php echo !empty($radius)?$radius: "0" ?>">
								</div>
								<div class="geo-location">
									<?php echo JText::_("LNG_GEOLOCATION")?>
									<div id="loading-geo-locaiton" class="ui-autocomplete-loading" style="display:none"></div>
									<a id="enable-geolocation" class="toggle btn-on <?php echo !empty($geoLocation)?"active":""?>" title="Grid" href="javascript:enableGeoLocation()"><?php echo strtoupper(JText::_("LNG_ON")) ?></a>
									<a id="disable-geolocation" class="toggle btn-off <?php echo empty($geoLocation)?"active":""?>" title="List" href="javascript:disableGeoLocation()"><?php echo strtoupper(JText::_("LNG_OFF")) ?></a>
								</div>
							</div>
							<i class="dir-icon-map-marker"></i>
							<input class="search-field" placeholder="<?php echo JText::_("LNG_ZIPCODE")?>" type="text" name="zipcode" id="zipcode" value="<?php  echo $preserve?$session->get('zipcode'):"";?>" />
							<i class="dir-icon-bullseye"  onclick='jQuery("#dir-search-preferences").show()'></i>
						</div>
					<?php } ?>
					
					<?php if($params->get('showCities')){ ?>
					<div class="form-field">
						<select name="citySearch" id="citySearch">
							<option value="0"><?php echo JText::_("LNG_ALL_CITIES") ?></option>
							<?php foreach($cities as $city){?>
								<option value="<?php echo $city->city?>" <?php  echo $session->get('citySearch')==$city->city && $preserve?" selected ":"" ?> ><?php echo $city->city?></option>
							<?php }?>
						</select>
					</div>
					<?php } ?>
					
					<?php if($params->get('showRegions')){ ?>
					<div class="form-field">
						<select name="regionSearch" id="regionSearch">
							<option value="0"><?php echo JText::_("LNG_ALL_REGIONS") ?></option>
							<?php foreach($regions as $region){?>
								<option value="<?php echo $region->county?>" <?php  echo $session->get('regionSearch')==$region->county && $preserve?" selected ":"" ?> ><?php echo $region->county?></option>
							<?php }?>
							
						</select>
					</div>
					<?php } ?>
					
					<?php if($params->get('showCountries')){ ?>
					<div class="form-field">
						<select name="countrySearch" id="countrySearch">
							<option value="0"><?php echo JText::_("LNG_ALL_COUNTRIES") ?></option>
							<?php foreach($countries as $country){?>
								<option value="<?php echo $country->id?>" <?php echo $session->get('countrySearch')==$country->id && $preserve?" selected ":"" ?> ><?php echo $country->country_name?></option>
							<?php }?>
							
						</select>
					</div>
					<?php } ?>
					
					<?php if(!empty($customAttributes)){ ?>
						
						<?php 
							
							require_once JPATH_SITE.'/components/com_jbusinessdirectory/classes/attributes/attributeservice.php';
							$renderedContent = AttributeService::renderAttributesSearch($customAttributes, false, array());
							echo $renderedContent;
						?>
						
					<?php } ?>
				</div>
				
				<button type="submit" class="ui-dir-button ui-dir-button-green search-dir-button">
					<i class="dir-icon-search"></i>
					<span class="ui-button-text"><?php echo JText::_("LNG_SEARCH")?></span>
				</button>
				
				<a style="display:none" id="categories-link" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&controller=categories&view=categories&task=displaycategories') ?>"><?php echo JText::_("LNG_CATEGORY_LIST")?></a>
				<input type="hidden" name="view" value="search">
				<input type="hidden" name="preserve" value="<?php echo $preserve?>">
				<input type="hidden" name="geo-latitude" id="geo-latitude" value="">
				<input type="hidden" name="geo-longitude" id="geo-longitude" value="">
				<input type="hidden" name="geolocation" id="geolocation" value="<?php echo $geoLocation ?>">
			</form>
	</div>
	<div class="clear"></div>
</div>


<script>

function checkSearch(){ 

	jQuery("#searchkeyword").removeClass("required");
	<?php if($params->get('mandatoryKeyword')){ ?>
		if(document.getElementById('searchkeyword') && jQuery("#searchkeyword").val().length == 0){
			jQuery("#searchkeyword").focus();
			jQuery("#searchkeyword").addClass("required");	
			return false;
		}
	<?php } ?>

	jQuery("#categories").removeClass("required");
	jQuery("#categories_chosen").removeClass("required");
	<?php if($params->get('mandatoryCategories')){ ?>
		var foo = document.getElementById('categories');
		if (foo)
		{
		   if (foo.selectedIndex == 0)
		   {
			   jQuery("#categories").focus();
			   jQuery("#categories").addClass("required");
			   jQuery("#categories_chosen").addClass("required");
			   return false;
		   } 
		}
	<?php } ?>

	jQuery("#typeSearch").removeClass("required");
	jQuery("#typeSearch_chosen").removeClass("required");
	<?php if($params->get('mandatoryCities')){ ?>
		var foo = document.getElementById('typeSearch');
		if (foo)
		{
		   if (foo.selectedIndex == 0)
		   {
			   jQuery("#typeSearch").focus();
			   jQuery("#typeSearch").addClass("required");
			   jQuery("#typeSearch_chosen").addClass("required");
			   return false;
		   } 
		}
	<?php } ?>

	jQuery("#zipcode").removeClass("required");
	<?php if($params->get('mandatoryZipCode')){ ?>
		if(document.getElementById('zipcode') && jQuery("#zipcode").val().length == 0){
			jQuery("#zipcode").focus();
			 jQuery("#zipcode").addClass("required");
			return false;
		}
	<?php } ?>

	jQuery("#citySearch").removeClass("required");
	jQuery("#citySearch_chosen").removeClass("required");
	<?php if($params->get('mandatoryCities')){ ?>
		var foo = document.getElementById('citySearch');
		if (foo)
		{
		   if (foo.selectedIndex == 0)
		   {
			   jQuery("#citySearch").focus();
			   jQuery("#citySearch").addClass("required");
			   jQuery("#citySearch_chosen").addClass("required");
			   return false;
		   } 
		}
	<?php } ?>

	jQuery("#regionSearch").removeClass("required");
	jQuery("#regionSearch_chosen").removeClass("required");
	<?php if($params->get('mandatoryRegions')){ ?>
		var foo = document.getElementById('regionSearch');
		if (foo)
		{
		   if (foo.selectedIndex == 0)
		   {
			   jQuery("#regionSearch").focus();
			   jQuery("#regionSearch").addClass("required");
			   jQuery("#regionSearch_chosen").addClass("required");
				return false;
		   } 
		}
	<?php } ?>

	jQuery("#countrySearch").removeClass("required");
	jQuery("#countrySearch_chosen").removeClass("required")
	<?php if($params->get('mandatoryCountries')){ ?>
		var foo = document.getElementById('countrySearch');
		if (foo)
		{
		   if (foo.selectedIndex == 0)
		   {
			   jQuery("#countrySearch").focus();
			   jQuery("#countrySearch").addClass("required");
			   jQuery("#countrySearch_chosen").addClass("required");
			   
			   return false;
		   } 
		}
	<?php } ?>

	
		
	
	return true;
}

jQuery(document).ready(function(){
	<?php if($params->get('autocomplete')){?>
	    jQuery("#categories").chosen();
	    jQuery("#citySearch").chosen();
	    jQuery("#regionSearch").chosen();
	    jQuery("#countrySearch").chosen();
		jQuery("#typeSearch").chosen();
	 <?php } ?>

    jQuery("#searchkeyword").autocomplete({
		source: "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=categories.getCategories') ?>",
		minLength: 2,
		select: function( event, ui ) {
			jQuery(this).val(ui.item.label);
			return false;
		}
	});

    jQuery("#zipcode").focusin(function() {
    	jQuery("#dir-search-preferences").slideDown(500);
    });
    jQuery("#zipcode").focusout(function() {
    	jQuery("#dir-search-preferences").slideUp(500);
    });
    
    <?php if($params->get('showZipcode')){ ?>
	    jQuery("#geo-location-radius").ionRangeSlider({
	        grid: true,
	        min: 0,
	        max: 500,
	        from: <?php echo !empty($radius)?$radius: "0" ?>,
	        to: 500,
	    });
    <?php } ?>
  
});


function enableGeoLocation(){
	 if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setGeoLocation);
        //console.debug("geolocation enabled");
        jQuery("#loading-geo-locaiton").show();
    }else{
    	//console.debug("geolocation not supported");
    } 

	 jQuery("#enable-geolocation").addClass("active");
	 jQuery("#disable-geolocation").removeClass("active");    
	 jQuery("#geolocation").val(1);
}

function disableGeoLocation(){
	jQuery("#enable-geolocation").removeClass("active");
	jQuery("#disable-geolocation").addClass("active");   
	jQuery("#geolocation").val(0);
	jQuery("#loading-geo-locaiton").hide();
	jQuery("#geo-latitude").val('');
	jQuery("#geo-longitude").val('');
}


function setGeoLocation(position){
	 jQuery("#loading-geo-locaiton").hide();
	//  console.debug("set geolocation");
	 var latitude = position.coords.latitude;
	 var longitude = position.coords.longitude;
	
	 jQuery("#geo-latitude").val(latitude);
	 jQuery("#geo-longitude").val(longitude);
}

<?php if($params->get('showMap')){ ?>
	loadMapScript();
<?php }?>



</script>



