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
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{	
		jQuery("#item-form").validationEngine('detach');
		if (task == 'offer.cancel' || task == 'offer.aprove' || task == 'offer.disaprove' || !validateCmpForm()) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		jQuery("#item-form").validationEngine('attach');
	}
</script>

<?php 

$user = JFactory::getUser();

$options = array(
	'onActive' => 'function(title, description){
		description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
	'onBackground' => 'function(title, description){
		description.setStyle("display", "none");
		title.addClass("closed").removeClass("open");
	}',
	'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
	'useCookie' => true, // this must not be a string. Don't use quotes.
);
?>

<?php 
if(isset($isProfile)) { ?>
	<div class="button-row">
		<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveCompanyInformation();">
			<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
		</button>
		<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
			<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
		</button>
	</div>
	<div class="clear"></div>		
<?php 
} ?>

<div class="category-form-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_OFFER_DETAILS');?></h2>
			<p><?php echo JText::_('LNG_DISPLAY_INFO_TXT');?></p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_SUBJECT')?> </label> 
					<input type="text" name="subject" id="subject" class="input_txt validate[required]"  value="<?php echo $this->item->subject ?>" maxLength="100">
					<div class="clear"></div>
					<span class="error_msg" id="frmCompanySubject_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>
				<div class="detail_box" style="<?php echo isset($isProfile)?"display:none":""?>">
					<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
					<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="<?php echo $this->item->alias ?>"  maxLength="100">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="subject"><?php echo JText::_('LNG_CATEGORY')?> </label> 
					<select name="categories[]" id="categories-offers" multiple="multiple">
						<option val=""><?php echo JText::_("LNG_SELECT_CAT") ?></option>
						<?php echo JHtml::_('select.options', $this->categoryOptions, 'value', 'text',$this->item->selectedCategories);?>
					</select>
					<div class="clear"></div>					
				</div>
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="short_description"><?php echo JText::_('LNG_SHORT_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<?php 
					if($this->appSettings->enable_multilingual) {
						echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
						foreach( $this->languages  as $k=>$lng ) {
							echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
							$langContent = isset($this->translations[$lng."_short"])?$this->translations[$lng."_short"]:"";
							if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)) {
								$langContent = $this->item->short_description;
							}
							echo "<textarea id='short_description'.$lng' name='short_description_$lng' class='input_txt' cols='75' rows='4' maxLength='250'>$langContent</textarea>";
							echo "<div class='clear'></div>";
						}
						echo JHtml::_('tabs.end');
					}
					else { ?>
						<textarea name="short_description" id="short_description" class="input_txt validate[required]"  cols="75" rows="4"  maxLength="250"><?php echo $this->item->short_description ?></textarea>
					<?php 
					} ?>
				</div>
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<?php 
					if($this->appSettings->enable_multilingual) {
						echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
						foreach( $this->languages  as $k=>$lng ) {
							echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
							$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
							if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)) {
								$langContent = $this->item->description;
							}
							$editor = JFactory::getEditor();
							echo $editor->display('description_'.$lng, $langContent, '95%', '200', '70', '10', false);
						}
						echo JHtml::_('tabs.end');
					}
					else {
						$editor = JFactory::getEditor();
						echo $editor->display('description', $this->item->description, '95%', '200', '70', '10', false);
					} ?>
				</div>
				<div class="detail_box">
					<label for="startDate"><?php echo JText::_('LNG_OFFER_START_DATE')?> </label> 
					<?php 
						$this->item->startDate = $this->item->startDate=="0000-00-00"?"":$this->item->startDate;
						echo JHTML::_('calendar', $this->item->startDate, 'startDate', 'startDate', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); 
					?>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="endDate"><?php echo JText::_('LNG_OFFER_END_DATE')?> </label>
					<?php 
						$this->item->endDate = $this->item->endDate=="0000-00-00"?"":$this->item->endDate;
						echo JHTML::_('calendar', $this->item->endDate, 'endDate', 'endDate', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); 
					?>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="startDate"><?php echo JText::_('LNG_PUBLISH_START_DATE')?> </label> 
					<?php 
					   $this->item->publish_start_date = $this->item->publish_start_date=="0000-00-00"?"":$this->item->publish_start_date;
					   echo JHTML::_('calendar', $this->item->publish_start_date, 'publish_start_date', 'publish_start_date', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); 
					?>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="endDate"><?php echo JText::_('LNG_PUBLISH_END_DATE')?> </label>
					<?php 
    				   $this->item->publish_end_date= $this->item->publish_end_date=="0000-00-00"?"":$this->item->publish_end_date;
					   echo JHTML::_('calendar', $this->item->publish_end_date, 'publish_end_date', 'publish_end_date', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); 
					 ?>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="price"><?php echo JText::_('LNG_PRICE')?> </label> 
					<input type="text" name="price" id="price" class="input_txt" value="<?php echo $this->item->price ?>">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="specialPrice"><?php echo JText::_('LNG_SPECIAL_PRICE')?> </label> 
					<input type="text" name=specialPrice id="specialPrice" class="input_txt" value="<?php echo $this->item->specialPrice ?>">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="companyId"><?php echo JText::_('LNG_ASSOCIATED_COMPANY')?></label> 
					<select name="companyId" id="companyId" class="inputbox input-medium validate[required]">
						<?php echo JHtml::_('select.options', $this->companies, 'id', 'name', $this->item->companyId);?>
					</select>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="state"><?php echo JText::_('LNG_STATE')?></label> 
					<select name="state" id="state" class="inputbox input-medium">
						<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->item->state);?>
					</select>
					<div class="clear"></div>
				</div>
			</div>
		</fieldset>
		<fieldset class="boxed" style="display:none">
			<h2> <?php echo JText::_('LNG_CONFIGURATION');?></h2>
			<div class="form-box">
				<div class="detail_box" >
					<div class="form-detail req"></div>
					<label for="article_id"><?php echo JText::_('LNG_VIEW_TYPE')?> </label> 
					<div>
						<fieldset id="view_type_fld" class="radio btn-group btn-group-yesno">
							<input type="radio" class="validate[required]" name="view_type" id="view_type1" value="1" <?php echo $this->item->view_type==1 || empty($this->item->view_type) ? 'checked="checked"' :""?> />
							<label class="btn" for="view_type1"><?php echo JText::_('LNG_OFFER')?></label> 
							<input type="radio" class="validate[required]" name="view_type" id="view_type2" value="2" <?php echo $this->item->view_type==2? 'checked="checked"' :""?> />
							<label class="btn" for="view_type2"><?php echo JText::_('LNG_ARTICLE')?></label> 
							<input type="radio" class="validate[required]" name="view_type" id="view_type3" value="3" <?php echo $this->item->view_type==3? 'checked="checked"' :""?> />
							<label class="btn" for="view_type3"><?php echo JText::_('LNG_URL')?></label> 
						</fieldset>
					</div>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="article_id"><?php echo JText::_('LNG_ARTICLE_ID')?> </label> 
					<input class="input_txt" type="text" name="article_id" id="article_id" value="<?php echo $this->item->article_id ?>">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="url"><?php echo JText::_('LNG_URL')?> </label>
					<input class="input_txt" type="text" name="url" id="url" value="<?php echo $this->item->url ?>">
					<div class="clear"></div>
				</div>
			</div>	
		</fieldset>
		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_LOCATION');?></h2>
			<div class="form-box">
				<div class="detail_box">
					<label for="address_id"><?php echo JText::_('LNG_ADDRESS')?></label> 
					<input type="text" id="autocomplete" class="input_txt" placeholder="<?php echo JText::_("LNG_ENTER_ADDRESS") ?>" onFocus="" ></input>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="subject"><?php echo JText::_('LNG_ADDRESS')?> </label> 
					<input type="text" name="address" id="route" class="input_txt" value="<?php echo $this->item->address ?>">
					<div class="clear"></div>					
				</div>
				<div class="detail_box">
					<label for="subject"><?php echo JText::_('LNG_CITY')?> </label> 
					<input type="text" name="city" id="locality" class="input_txt" value="<?php echo $this->item->city ?>">
					<div class="clear"></div>					
				</div>
				<div class="detail_box">
					<label for="subject"><?php echo JText::_('LNG_REGION')?> </label> 
					<input type="text" name="county" id="administrative_area_level_1" class="input_txt" value="<?php echo $this->item->county ?>">
					<div class="clear"></div>					
				</div>
				<div class="detail_box">
					<label for="latitude"><?php echo JText::_('LNG_LATITUDE')?> </label> 
					<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
					<input class="input_txt" type="text" name="latitude" id="latitude" value="<?php echo $this->item->latitude ?>">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="longitude"><?php echo JText::_('LNG_LONGITUDE')?> </label>
					<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
					<input class="input_txt" type="text" name="longitude" id="longitude" value="<?php echo $this->item->longitude ?>">
					<div class="clear"></div>
				</div>
				<div id="map-container">
					<div id="company_map"></div>
				</div>	
			</div>
		</fieldset>
		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_OFFER_PICTURES');?></h2>
			<p> <?php echo JText::_('LNG_OFFER_PICTURES_INFORMATION_TEXT');?>.</p>
			<input type='button' name='btn_removefile' id='btn_removefile' value='x' style='display:none'>
			<input type='hidden' name='crt_pos' id='crt_pos' value=''>
			<input type='hidden' name='crt_path' id='crt_path' value=''>
			<TABLE class='picture-table' align='left' border='0'>
				<TR>
					<TD>
						<TABLE class="admintable" align='center' id='table_offer_pictures' name='table_offer_pictures'>
							<?php 
							foreach( $this->item->pictures as $picture ) { ?>
								<TR>
									<TD align='left'>
										<textarea cols='50' rows='2' name='offer_picture_info[]' id='offer_picture_info'><?php echo $picture['offer_picture_info']?></textarea>
									</TD>
									<td align='center'>
										<img class='img_picture_offer' src='<?php echo JURI::root()."/".PICTURES_PATH.$picture['offer_picture_path']?>'/>
										<BR>
										<?php echo basename($picture['offer_picture_path'])?>
										<input type='hidden' value='<?php echo $picture['offer_picture_enable']?>' name='offer_picture_enable[]' id='offer_picture_enable'>
										<input type='hidden' value='<?php echo $picture['offer_picture_path']?>' name='offer_picture_path[]' id='offer_picture_path'>
									</td>
									<td align='center'>
										<img class='btn_picture_delete' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
											onclick="
												if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_PICTURE',true)?>')) 
													return; 
												var row = jQuery(this).parents('tr:first');
												var row_idx = row.prevAll().length;
												jQuery('#crt_pos').val(row_idx);
												jQuery('#crt_path').val('<?php echo $picture['offer_picture_path']?>');
												jQuery('#btn_removefile').click();"/>
									</td>
									<td align='center'>
										<img class='btn_picture_status' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($picture['offer_picture_enable'] ? 'checked' : 'unchecked').".gif"?>'
											onclick="
												var form = document.adminForm;
												var v_status = null;
												var pos = jQuery(this).closest('tr')[0].sectionRowIndex;

												if( form.elements['offer_picture_enable[]'].length == null ) {
													v_status  = form.elements['offer_picture_enable[]'];
												}
												else {
													v_status  = form.elements['offer_picture_enable[]'][pos];
												}
												if( v_status.value == '1') {
													jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/unchecked.gif"?>');
													v_status.value ='0';
												}
												else {
													jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>');
													v_status.value ='1';
												}"/>
									</td>
									<td align="center">
										<span class="span_up" onclick='var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());'>
											<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/up-icon.png">
										</span>
										<span class="span_down" onclick='var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());'>
											<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/down-icon.png">
										</span>
									</td>
								</TR>
							<?php 
							} ?>
						</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan ="2">
						<?php echo JText::_('LNG_PLEASE_CHOOSE_A_FILE'); ?> <input name="uploadfile" id="multiImageUploader" size="50" type="file" />
					</TD>
				</TR>
			</TABLE>
		</fieldset>
		<?php 
		if($this->appSettings->enable_attachments == 1) { ?>
			<fieldset class="boxed">
				<h2> <?php echo JText::_('LNG_ATTACHMENTS');?></h2>
				<p> <?php echo JText::_('LNG_ATTACHMENTS_INFORMATION_TEXT');?>.</p>
				<input type='button' name='btn_removefile_at' id='btn_removefile_at' value='x' style='display:none'>
				<input type='hidden' name='crt_pos_a' id='crt_pos_a' value=''>
				<input type='hidden' name='crt_path_a' id='crt_path_a' value=''>
				<table class='picture-table' align='left' border='0'>
					<tr>
						<td align='left' class="key">
							<?php echo JText::_('LNG_ATTACHMENTS'); ?>:
						</td>
					</tr>
					<TR>
						<TD>
							<TABLE class="admintable" align='center' id='table_offer_attachments' name='table_offer_attachments' >
								<?php 
								if(!empty($this->item->attachments))
									foreach( $this->item->attachments as $attachment ) { ?>
										<TR>
											<TD align='left'>
												<input type="text" name='attachment_name[]' id='attachment_name' value="<?php echo $attachment->name ?>" /><br/>
												<?php echo basename($attachment->path)?>
											</TD>
											<td align='center'>
												<img class='btn_attachment_delete' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
													onclick="
														if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_ATTACHMENT',true)?>')) 
															return; 
														var row = jQuery(this).parents('tr:first');
														var row_idx = row.prevAll().length;
														jQuery('#crt_pos_a').val(row_idx);
														jQuery('#crt_path_a').val('<?php echo $attachment->path?>');
														jQuery('#btn_removefile_at').click();"/>
											</td>
											<td align='center'>
												<input type='hidden' value='<?php echo $attachment->status?>' name='attachment_status[]' id='attachment_status'>
												<input type='hidden' value='<?php echo $attachment->path?>' name='attachment_path[]' id='attachment_path'>
												<img class='btn_attachment_status' src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($attachment->status ? 'checked' : 'unchecked').".gif"?>'
													onclick="
														var form = document.adminForm;
														var v_status = null;
														var pos = jQuery(this).closest('tr')[0].sectionRowIndex;

														if( form.elements['attachment_status[]'].length == null ) {
															v_status  = form.elements['attachment_status[]'];
														}
														else {
															v_status  = form.elements['attachment_status[]'][pos];
														}
														if( v_status.value == '1') {
															jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/unchecked.gif"?>');
															v_status.value ='0';
														} 
														else {
															jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>');
															v_status.value ='1';
														}"/>
											</td>
											<td align="center">
												<span class="span_up" onclick='var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());'>
													<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/up-icon.png">
												</span>
												<span class="span_down" onclick='var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());'>
													<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/down-icon.png">
												</span>
											</td>
										</TR>
									<?php 
									} ?>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD colspan ="2">
							<?php echo JText::_('LNG_PLEASE_CHOOSE_A_FILE'); ?> <input name="uploadAttachment" id="multiFileUploader" size="50" type="file" />
						</TD>
					</TR>
				</TABLE>
			</fieldset>
		<?php 
		} ?>	
		<?php 
		if(isset($isProfile)) { ?>
			<div class="button-row">
				<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveCompanyInformation();">
					<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
				</button>
				<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
					<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
				</button>
			</div>
		<?php 
		} ?>

		<script type="text/javascript">
			function saveCompanyInformation() {
				if(validateCmpForm())
					return false;
				jQuery("#task").val('managecompanyoffer.save');
				var form = document.adminForm;
				form.submit();
			}
			
			function cancel() {
				jQuery("#task").val('managecompanyoffer.cancel');
				var form = document.adminForm;
				form.submit();
			}
			
			function validateCmpForm() {
				var isError = jQuery("#item-form").validationEngine('validate');
				return !isError;
			}
		</script>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
		<input type="hidden" name="task" id="task" value="" /> 
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
	
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>

<script>
	var maxCategories = <?php echo isset($this->item->package)?$this->item->package->max_categories :$this->appSettings->max_categories ?>;

	jQuery(document).ready(function() {
		jQuery("#item-form").validationEngine('attach');
		var categoriesSelectList = null;
		categoriesSelectList = jQuery("select#categories-offers").selectList({ 
			sort: true,
			classPrefix: 'cities_ids',
			instance: true,
			onAdd: function (select, value, text) {
				if( jQuery("select#categories-offers option:selected").length >= maxCategories-1 ) {
					jQuery("select.cities_ids-select").hide();
				}
			},
			onRemove: function (select, value, text) {
				if( jQuery("select#categories-offers option:selected").length <= maxCategories ) {
					jQuery("select.cities_ids-select").show();
				}
			}
		});
		if( jQuery("select#categories-offers option:selected").length >= maxCategories ) {
			jQuery("select.cities_ids-select").hide();
		}

		initializeAutocomplete();
	});

	var map;
	var markers = [];

	function initialize() {
		<?php 
			$latitude = isset($this->item->latitude) && strlen($this->item->latitude)>0?$this->item->latitude:"0";
			$longitude = isset($this->item->longitude) && strlen($this->item->longitude)>0?$this->item->longitude:"0";
 		?>
		var companyLocation = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
		var mapOptions = {
			zoom: <?php echo !(isset($this->item->latitude) && strlen($this->item->latitude))?1:15?>,
			center: companyLocation,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var mapdiv = document.getElementById("company_map");
		mapdiv.style.width = '100%';
		mapdiv.style.height = '400px';
		map = new google.maps.Map(mapdiv,  mapOptions);
		var latitude = '<?php echo  $this->item->latitude ?>';
		var longitude = '<?php echo  $this->item->longitude ?>';
		if(latitude && longitude)
	    	addMarker(new google.maps.LatLng(latitude, longitude ));
		google.maps.event.addListener(map, 'click', function(event) {
			deleteOverlays();
			addMarker(event.latLng);
		});
	}

	//Add a marker to the map and push to the array.
	function addMarker(location) {
		document.getElementById("latitude").value = location.lat();
		document.getElementById("longitude").value = location.lng();
		marker = new google.maps.Marker({
			position: location,
			map: map
		});
		markers.push(marker);
	}

	//Sets the map on all markers in the array.
	function setAllMap(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	//Removes the overlays from the map, but keeps them in the array.
	function clearOverlays() {
		setAllMap(null);
	}

	//Shows any overlays currently in the array.
	function showOverlays() {
		setAllMap(map);
	}

	//Deletes all markers in the array by removing references to them.
	function deleteOverlays() {
		clearOverlays();
		markers = [];
	}
	var initialized = false;  


	var placeSearch, autocomplete;
	var component_form = {
		'route': 'long_name',
		'locality': 'long_name',
		'administrative_area_level_1': 'long_name'
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
			var obj = document.getElementById(component);
			if(typeof maybeObject != "undefined") {
				document.getElementById(component).value = "";
				document.getElementById(component).disabled = false;
			}
		}

		for (var j = 0; j < place.address_components.length; j++) {
			var att = place.address_components[j].types[0];
			if (component_form[att]) {
				var val = place.address_components[j][component_form[att]];
				jQuery("#"+att).val(val);
				if(att=='country') {
					jQuery('#country option').filter(function () {
						return jQuery(this).text() === val;
					}).attr('selected', true);
				}
			}
		}

		if(typeof map != "undefined") {
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			}
			else {
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

	window.onload = initialize;
</script>