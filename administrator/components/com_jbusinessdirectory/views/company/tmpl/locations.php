<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html');
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';


// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$attributeConfig = $this->item->defaultAtrributes;
$enablePackages = $this->appSettings->enable_packages;

?>
<div class="category-form-container">
<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&tmpl=component&layout=locations'); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
	<div class="clr mandatory oh">
		<p>This information is required</p>
	</div>
	<div class="button-row">
		<button type="button" class="ui-dir-button" onclick="saveLocation();">
				<span class="ui-button-text"><?php echo JText::_("LNG_SAVE")?></span>
		</button>
		<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
				<span class="ui-button-text"><?php echo JText::_("LNG_CLOSE")?></span>
		</button>
	</div>
	<fieldset class="boxed">
		<h2> <?php echo JText::_('LNG_COMPANY_DETAILS');?></h2>
		<p><?php echo JText::_('LNG_COMPANY_DETAILS_TXT');?></p>
		<p><?php echo JText::_('LNG_ADDRESS_SUGESTION');?></p>
		<div class="form-box">
	
			<div class="detail_box">
				<label for="address_id"><?php echo JText::_('LNG_ADDRESS')?></label> 
				<input type="text" id="autocomplete" class="input_txt" placeholder="Enter your address" onFocus="" ></input>
				<div class="clear"></div>
			</div>
			<br/>
		
			<div class="detail_box">
				<label for="name"><?php echo JText::_('LNG_NAME')?></label> 
				<input type="text" name="name" id="name" class="input_txt text-input" value="<?php echo $this->location->name ?>">
				<div class="clear"></div>
				<span class="error_msg" id="frmStreetNumber_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
			</div>
		
	
			<?php if($attributeConfig["street_number"]!=ATTRIBUTE_NOT_SHOW){?>
			<div class="detail_box">
				<?php if($attributeConfig["street_number"] == ATTRIBUTE_MANDATORY){?>
					<div  class="form-detail req"></div>
				<?php }?>
				<label for="address_id"><?php echo JText::_('LNG_STREET_NUMBER')?></label> 
				<input type="text" name="street_number" id="street_number" class="input_txt text-input <?php echo $attributeConfig["street_number"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->location->street_number ?>">
				<div class="clear"></div>
				<span class="error_msg" id="frmStreetNumber_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
			</div>
			<?php } ?>
			
			<?php if($attributeConfig["address"]!=ATTRIBUTE_NOT_SHOW){?>
			<div class="detail_box">
				<?php if($attributeConfig["address"] == ATTRIBUTE_MANDATORY){?>
				<div  class="form-detail req"></div>
			<?php }?>
				<label for="address_id"><?php echo JText::_('LNG_ADDRESS')?></label> 
				<input type="text" name="address" id="route" class="input_txt <?php echo $attributeConfig["address"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input" value="<?php echo $this->location->address ?>">
				<div class="clear"></div>
				<span class="error_msg" id="frmAddress_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
			</div>
			<?php } ?>
			
			<?php if($attributeConfig["country"]!=ATTRIBUTE_NOT_SHOW){?>
			
			<div class="detail_box">
				<?php if($attributeConfig["country"] == ATTRIBUTE_MANDATORY){?>
					<div  class="form-detail req"></div>
				<?php }?>
				<label for="countryId"><?php echo JText::_('LNG_COUNTRY')?> </label>
				<div class="clear"></div>
				<select class="input_sel <?php echo $attributeConfig["country"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> select" name="countryId" id="country" >
						<option value=''></option>
						<?php
							foreach( $this->item->countries as $country ){
						?>
							<option <?php echo $this->location->countryId==$country->id? "selected" : ""?> 
								value='<?php echo $country->id?>'
							><?php echo $country->country_name ?></option>
						<?php
							}
						?>
				</select>
				<div class="clear"></div>
				<span class="error_msg" id="frmCountry_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
			</div>
			<?php } ?>
			
			<?php if($attributeConfig["city"]!=ATTRIBUTE_NOT_SHOW){?>
			
			<div class="detail_box">
				<?php if($attributeConfig["city"] == ATTRIBUTE_MANDATORY){?>
					<div  class="form-detail req"></div>
				<?php }?>
				<label for="city_id"><?php echo JText::_('LNG_CITY')?> </label> 
				<input type="text" class="input_txt <?php echo $attributeConfig["city"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input" type="text" name="city" id="locality" value="<?php echo $this->location->city ?>">
				<div class="clear"></div>
				<span class="error_msg" id="frmCity_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
			</div>
			<?php } ?>
			
			<?php if($attributeConfig["region"]!=ATTRIBUTE_NOT_SHOW){?>
			
			<div class="detail_box" id="districtContainer">
				<?php if($attributeConfig["region"] == ATTRIBUTE_MANDATORY){?>
					<div  class="form-detail req"></div>
				<?php }?>
				<label for="district_id"><?php echo JText::_('LNG_COUNTY')?> </label> 
				<input type="text" class="input_txt <?php echo $attributeConfig["region"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input" name="county" id="administrative_area_level_1" value="<?php echo $this->location->county ?>" />
				<div class="clear"></div>
				<span class="error_msg" id="frmDistrict_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
			</div>
			<?php } ?>
			
			<?php if($attributeConfig["postal_code"]!=ATTRIBUTE_NOT_SHOW){?>
			
			<div class="detail_box" id="districtContainer">
				<?php if($attributeConfig["postal_code"] == ATTRIBUTE_MANDATORY){?>
					<div  class="form-detail req"></div>
				<?php }?>
				<label for="district_id"><?php echo JText::_('LNG_POSTAL_CODE')?> </label> 
				<input type="text" class="input_sel <?php echo $attributeConfig["postal_code"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" name="postalCode" id="postal_code" value="<?php echo $this->location->postalCode ?>" />
				<div class="clear"></div>
			</div>
			<?php } ?>
			
			<?php if($attributeConfig["phone"]!=ATTRIBUTE_NOT_SHOW) { ?>					
					<div class="detail_box">
						<?php if($attributeConfig["phone"] == ATTRIBUTE_MANDATORY){?>
							<div  class="form-detail req"></div>
						<?php }?>
						<label for="phone"><?php echo JText::_('LNG_TELEPHONE')?></label> 
						<input type="text"	name="phone" id="phone" class="input_txt <?php echo $attributeConfig["phone"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input"
							value="<?php echo $this->location->phone ?>">
						<div class="clear"></div>
					</div>
			<?php } ?>
	
			<?php if(!$enablePackages || isset($this->item->package->features) && in_array(GOOGLE_MAP,$this->item->package->features)){ ?>
				<?php if($attributeConfig["map"]!=ATTRIBUTE_NOT_SHOW){?>
										
					<div class="detail_box">
						<?php if($attributeConfig["map"] == ATTRIBUTE_MANDATORY){?>
							<div  class="form-detail req"></div>
						<?php }?>
						<label for="latitude"><?php echo JText::_('LNG_LATITUDE')?> </label> 
						<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
						<input class="input_txt <?php echo $attributeConfig["map"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" type="text" name="latitude" id="latitude" value="<?php echo $this->location->latitude ?>">
						<div class="clear"></div>
					</div>
			
					<div class="detail_box">
						<?php if($attributeConfig["map"] == ATTRIBUTE_MANDATORY){?>
							<div  class="form-detail req"></div>
						<?php }?>
						<label for="longitude"><?php echo JText::_('LNG_LONGITUDE')?> </label>
						<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
						<input class="input_txt <?php echo $attributeConfig["map"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" type="text" name="longitude" id="longitude" value="<?php echo $this->location->longitude ?>">
						<div class="clear"></div>
					</div>
					
					<div id="map-container">
						<div id="company_map">
						</div>
					</div>
				<?php }?>
			<?php } ?>
		</div>
	</fieldset>
	<div class="button-row">
		<button type="button" class="ui-dir-button" onclick="saveLocation();">
				<span class="ui-button-text"><?php echo JText::_("LNG_SAVE")?></span>
		</button>
		<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
				<span class="ui-button-text"><?php echo JText::_("LNG_CLOSE")?></span>
		</button>
	</div>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
	<input type="hidden" name="company_id" value="<?php echo !empty($this->item->id)?$this->item->id:$this->location->company_id ?>" /> 
	<input type="hidden" name="locationId" id="locationId" value="<?php echo isset($this->location->id)?$this->location->id:"0" ?>" />
	
	
	<?php if(isset($isProfile)){ ?>
		<input type="hidden" name="task" id="task" value="managecompany.saveLocation" />
		<input type="hidden" name="view" id="view" value="managecompany" /> 
	<?php }else{ ?>
		<input type="hidden" name="task" id="task" value="company.saveLocation" />
		<input type="hidden" name="view" id="view" value="company" />
	<?php }?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
<script>
var placeSearch, autocomplete;
  var component_form = {
	'street_number': 'short_name',
    'route': 'long_name',
    'locality': 'long_name',
    'administrative_area_level_1': 'long_name',
    'country': 'long_name',
    'postal_code': 'short_name'
  };
  
  function initializeAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), { types: [ 'geocode' ] });
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      fillInAddress();
    });
  }
  
  function fillInAddress() {
    var place = autocomplete.getPlace();

    for (var component in component_form) {
        //console.debug(component);
        var obj = document.getElementById(component);
        if(typeof maybeObject != "undefined"){
	      document.getElementById(component).value = "";
	      document.getElementById(component).disabled = false;
        }
    }
    
    for (var j = 0; j < place.address_components.length; j++) {
      var att = place.address_components[j].types[0];
    
      if (component_form[att]) {
        var val = place.address_components[j][component_form[att]];
        //console.debug("#"+att);
        //console.debug(val);
        //console.debug(jQuery(att).val());
        jQuery("#"+att).val(val);
        if(att=='country'){
        	jQuery('#country option').filter(function () {
        		   return jQuery(this).text() === val;
        		}).attr('selected', true);
        }
      }
    }

    if(typeof map != "undefined"){
	    if (place.geometry.viewport) {
	        map.fitBounds(place.geometry.viewport);
	      } else {
	        map.setCenter(place.geometry.location);
	        map.setZoom(17); 
	        addMarker(place.geometry.location);
	      }
	 }
  }
    
  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
      });
    }
  }


  var map;
  var markers = [];


function initialize() {
	<?php 
		$latitude = isset($this->location->latitude) && strlen($this->location->latitude)>0?$this->location->latitude:"0";
		$longitude = isset($this->location->longitude) && strlen($this->location->longitude)>0?$this->location->longitude:"0";
   ?>
  var companyLocation = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
  var mapOptions = {
    zoom: <?php echo !(isset($this->location->latitude) && strlen($this->location->latitude))?1:15?>,
    center: companyLocation,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var mapdiv = document.getElementById("company_map");
  mapdiv.style.width = '100%';
  mapdiv.style.height = '400px';
  map = new google.maps.Map(mapdiv,  mapOptions);


  var latitude = '<?php echo  $this->location->latitude ?>';
  var longitude = '<?php echo  $this->location->longitude ?>';
  
  if(latitude && longitude)
      addMarker(new google.maps.LatLng(latitude, longitude ));
		  
  google.maps.event.addListener(map, 'click', function(event) {
	 deleteOverlays();
     addMarker(event.latLng);
  });

}

// Add a marker to the map and push to the array.
function addMarker(location) {
 document.getElementById("latitude").value = location.lat();
 document.getElementById("longitude").value = location.lng();

  marker = new google.maps.Marker({
    position: location,
    map: map
  });
  markers.push(marker);
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the overlays from the map, but keeps them in the array.
function clearOverlays() {
  setAllMap(null);
}

// Shows any overlays currently in the array.
function showOverlays() {
  setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteOverlays() {
  clearOverlays();
  markers = [];
}

function saveLocation(){
	if(validateCmpForm()){
		parent.updateLocation(jQuery("#locationId").val(), jQuery("#name").val(), jQuery("#street_number").val(),jQuery("#route").val(),jQuery("#locality").val(),jQuery("#administrative_area_level_1").val(),jQuery("#country :selected").text());
		jQuery("#item-form").submit();
	}	
}

function cancel(){
	parent.closeLocationDialog();
}

function validateCmpForm(){

	var isError = jQuery("#item-form").validationEngine('validate');
	return isError;
}


jQuery(document).ready(function(){
	jQuery("#item-form").validationEngine('attach');
	initializeAutocomplete();
	<?php if(!$enablePackages || isset($this->item->package->features) && in_array(GOOGLE_MAP,$this->item->package->features)){ ?>
		initialize();
	<?php }?>

	if(jQuery("#locationId").val()>0){
		parent.updateLocation(jQuery("#locationId").val(), jQuery("#name").val(), jQuery("#street_number").val(),jQuery("#route").val(),jQuery("#locality").val(),jQuery("#administrative_area_level_1").val(),jQuery("#country :selected").text());
	}
});
</script>