<?php 

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

if($appSettings->enable_google_map_clustering) {
?>
	<script src="https://googlemaps.github.io/js-marker-clusterer/examples/data.json"></script>
	<script src="https://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>
<?php } ?>

<script>
	function initialize() {
		var center = new google.maps.LatLng(37.4419, -122.1419);

		<?php 
			$height = "450px";
			if(isset($mapHeight)) {
				$height = $mapHeight;
			}
			$width = "100%";
			if(isset($mapWidth)) {
				$width = $mapWidth;
			}
		?>
		var mapdiv = document.getElementById("companies-map");
		mapdiv.style.width =  "<?php echo $width ?>";
		mapdiv.style.height = "<?php echo $height ?>";

		var map = new google.maps.Map(mapdiv, {
			zoom: 3,
			center: center,
			scrollwheel: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP
			
		});

		var companies = [
			<?php 
			$db = JFactory::getDBO();

			if(!isset($companies))
				$companies = $this->companies;

			$index = 1;

			foreach($companies as $company) {
				$description = str_replace("\r\n","",$company->short_description);
				$description = str_replace("\'","",$description);
				$description = $db->escape($description);
				$marker = 0;

				if(!empty($company->categoryMaker)) {
					$marker = JURI::root().PICTURES_PATH.$company->categoryMaker;
				}       

				$contentPhone = (isset($company->packageFeatures) && in_array(PHONE,$company->packageFeatures) || !$appSettings->enable_packages)?
				'<div class="info-phone"><i class="dir-icon-phone"></i> '.$db->escape($company->phone).'</div>':"";
				$contentString = '<div class="info-box">'.
					'<div class="title">'.$db->escape($company->name).'</div>'.
					'<div class="info-box-content">'.
					'<div class="address" itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">'.$db->escape(JBusinessUtil::getAddressText($company)).'</div>'.
					$contentPhone.
					'<a href="'.$db->escape(JBusinessUtil::getCompanyLink($company)).'"><i class="dir-icon-external-link"></i> '.$db->escape(JText::_("LNG_MORE_INFO",true)).'</a>'.
					'</div>'.
					'<div class="info-box-image">'.
					(!empty($company->logoLocation)?'<img src="'. JURI::root().PICTURES_PATH.$db->escape($company->logoLocation).'" alt="'.$db->escape($company->name).'">':"").
					'</div>'.
					'</div>';

				if(!empty($company->latitude) && !empty($company->longitude) && (isset($company->packageFeatures) && in_array(GOOGLE_MAP,$company->packageFeatures) || !$appSettings->enable_packages)) {
					echo "['".$db->escape($company->name)."', \"$company->latitude\",\"$company->longitude\", 4,'".$contentString."','".$index."','".$marker."'],"."\n";
				}

				if(!empty($company->locations) && (isset($company->packageFeatures) && in_array(GOOGLE_MAP,$company->packageFeatures) || !$appSettings->enable_packages)) {
					$locations = explode(",",$company->locations);
					
					foreach($locations as $location) {
						$loc = explode("|",$location);
						$contentPhoneLocation = (isset($company->packageFeatures) && in_array(PHONE,$company->packageFeatures) || !$appSettings->enable_packages)?
						'<div class="info-phone"><i class="dir-icon-phone"></i> '.$db->escape($loc[7]).'</div>':"";
							
						$address = JBusinessUtil::getLocationAddressText($loc[2],$loc[3],$loc[4],$loc[5],$loc[6]);
							
						$contentStringLocation = '<div class="info-box">'.
								'<div class="title">'.$db->escape($company->name).'</div>'.
								'<div class="info-box-content">'.
								'<div class="address" itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">'.$db->escape($address).'</div>'.
								$contentPhoneLocation.
								'<a href="'.$db->escape(JBusinessUtil::getCompanyLink($company)).'"><i class="dir-icon-external-link"></i> '.$db->escape(JText::_("LNG_MORE_INFO",true)).'</a>'.
								'</div>'.
								'<div class="info-box-image">'.
								(!empty($company->logoLocation)?'<img src="'. JURI::root().PICTURES_PATH.$db->escape($company->logoLocation).'" alt="'.$db->escape($company->name).'">':"").
								'</div>'.
								'</div>';
						
						echo "['".htmlspecialchars($company->name, ENT_QUOTES)."', \"$loc[0]\",\"$loc[1]\", 4,'".$contentStringLocation."','".$index."','".$marker."'],"."\n";
					}
				}

				$index++;
			} ?>
		];

		setMarkers(map, companies);
	}

	function setMarkers(map, locations) {
		// Add markers to the map

		// Marker sizes are expressed as a Size of X,Y
		// where the origin of the image (0,0) is located
		// in the top left of the image.

		// Origins, anchor positions and coordinates of the marker
		// increase in the X direction to the right and in
		// the Y direction down.

		var bounds = new google.maps.LatLngBounds();

		var markers = [];

		for (var i = 0; i < locations.length; i++) {
			var company = locations[i];

			var pinColor = "0071AF";
			var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(company[5])+"|" + pinColor+"|FFFFFF",
				new google.maps.Size(23, 32),
				new google.maps.Point(0,0),
				new google.maps.Point(10, 34));
			var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
				new google.maps.Size(40, 37),
				new google.maps.Point(0, 0),
				new google.maps.Point(12, 35));

			var shape = {
				coord: [1, 1, 1, 20, 18, 20, 18 , 1],
				type: 'poly'
			};

			if(company[6] != '0') {
				pinImage = new google.maps.MarkerImage(company[6],
				// This marker is 20 pixels wide by 32 pixels tall.
				new google.maps.Size(32, 32),
				// The origin for this image is 0,0.
				new google.maps.Point(0,0),
				// The anchor for this image is the base of the flagpole at 0,32.
				new google.maps.Point(0, 32));
			}else{
	            var ms_ie = false;
			    var ua = window.navigator.userAgent;
			    var old_ie = ua.indexOf('MSIE ');
			    var new_ie = ua.indexOf('Trident/');
			
			    if ((old_ie > -1) || (new_ie > -1)) {
			        ms_ie = true;
			    }
			
			    if ( ms_ie ) {
			        
			         pinImage = new google.maps.MarkerImage("<?php echo JURI::root().PICTURES_PATH.'/marker_home.png' ?>",
		              // This marker is 20 pixels wide by 32 pixels tall.
		              new google.maps.Size(32, 32),
		              // The origin for this image is 0,0.
		              new google.maps.Point(0,0),
		              // The anchor for this image is the base of the flagpole at 0,32.
		              new google.maps.Point(0, 32));
			    }  
			}

			var myLatLng = new google.maps.LatLng(company[1], company[2]);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				icon: pinImage,
				shadow: pinShadow,
				shape: shape,
				title: company[0],
				zIndex: company[3]
			});

			markers.push(marker);

			var contentBody = company[4];
			var infowindow = new google.maps.InfoWindow({
				content: contentBody,
				maxWidth: 210
			});

			google.maps.event.addListener(marker, 'click', function(contentBody) {
				return function() {
					infowindow.setContent(contentBody);//set the content
					infowindow.open(map,this);
				}
			} (contentBody));

			bounds.extend(myLatLng);
		}

		<?php if($appSettings->enable_google_map_clustering) { ?>
			var markerCluster = new MarkerClusterer(map, markers);
		<?php } ?>

		<?php if(isset($this) && !empty($this->location["latitude"])) { ?>
			var pinImage = new google.maps.MarkerImage(" https://maps.google.com/mapfiles/kml/shapes/library_maps.png",
			new google.maps.Size(31, 34),
			new google.maps.Point(0,0),
			new google.maps.Point(10, 34));

			var myLatLng = new google.maps.LatLng(<?php echo $this->location["latitude"] ?>,  <?php echo $this->location["longitude"] ?>);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				icon: pinImage
			});

			<?php $session = JFactory::getSession(); $radius = $session->get("radius"); ?>

			<?php if(!empty($radius)) { ?>
				// Add circle overlay and bind to marker
				var circle = new google.maps.Circle({
					map: map,
					radius: <?php echo $radius * 1600;?>,
					strokeColor: "#006CD9",
					strokeOpacity: 0.7,
					strokeWeight: 2,
					fillColor: "#006CD9",
					fillOpacity: 0.15,
				});
				circle.bindTo('center', marker, 'position');
			<?php } ?>
			bounds.extend(myLatLng);
		<?php } ?>
		map.fitBounds(bounds);

		var listener = google.maps.event.addListener(map, "idle", function() { 
			if (map.getZoom() > 16) map.setZoom(16);
			google.maps.event.removeListener(listener);
		});
	}

	function loadMapScript() {
		if(!initialized) {
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "https://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
			document.body.appendChild(script);
			initialized = true;
		}
	}
	var initialized = false;
</script>

<div id="companies-map" style="position: relative;"></div>