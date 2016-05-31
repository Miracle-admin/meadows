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

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{	
		
		var recurring_id = jQuery("#recurring_id").val();
		var frequency = jQuery('input:radio[name=frequency]:checked').val();
		
		//if(frequency == 0 && recurring_id!="" && (task == 'event.save' || task == 'event.apply')){
		//	showSaveDialog(task);
		//}else 
		jQuery("#item-form").validationEngine('detach');
		if (task == 'event.cancel' || task == 'event.aprove' || task == 'event.disaprove' || !validateCmpForm()){
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

<?php  if(isset($isProfile)) { ?>
<div class="button-row">
	<div class="button-row">
		<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveEventCompanyInformation();">
				<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
		</button>
		<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
				<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
		</button>
	</div>
</div>
<div class="clear"></div>		
<?php  } ?>

<div class="category-form-container">	
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed" >

			<h2> <?php echo JText::_('LNG_EVENT_DETAILS');?></h2>
			<p><?php echo JText::_('LNG_DISPLAY_INFO_TXT');?></p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_NAME')?> </label> 
					<input type="text" name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>"  maxLength="100">
					<div class="clear"></div>
				</div>
				<div class="detail_box" style="<?php echo isset($isProfile)?"display:none":""?>">
					<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
					<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="<?php echo $this->item->alias ?>"  maxLength="100">
					<div class="clear"></div>
				</div>
				
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="short_description"><?php echo JText::_('LNG_SHORT_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<?php 
						if($this->appSettings->enable_multilingual){
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ){
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng."_short"])?$this->translations[$lng."_short"]:"";
								if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)){
									$langContent = $this->item->short_description;
								}
								
								echo "<textarea id='short_description'.$lng' name='short_description_$lng' class='input_txt' cols='75' rows='4' maxLength='250'>$langContent</textarea>";
								echo "<div class='clear'></div>";
							}
							
							echo JHtml::_('tabs.end');
						}else {
						?>
							<textarea name="short_description" id="short_description" class="input_txt validate[required]"  cols="75" rows="4"  maxLength="250"
								><?php echo $this->item->short_description ?></textarea>
					<?php 
						}
					?>
				</div>
				
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<?php 
						if($this->appSettings->enable_multilingual){
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
							
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ){
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
								if($lng==JFactory::getLanguage()->getDefault() && empty($langContent)){
									$langContent = $this->item->description;
								}
								$editor = JFactory::getEditor();
								echo $editor->display('description_'.$lng, $langContent, '95%', '200', '70', '10', false);
							}
							echo JHtml::_('tabs.end');
						}else {
							
							$editor = JFactory::getEditor();
							echo $editor->display('description', $this->item->description, '95%', '200', '70', '10', false);

						}
						?>
				</div>
				
				<div class="detail_box" style="display:none">
					<label for="price"><?php echo JText::_('LNG_LOCATION')?> </label> 
					<input type="text" name="location" id="location" class="input_txt" value="<?php echo $this->item->location ?>">
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="companyTypes"><?php echo JText::_('LNG_TYPE')?> </label> 
					<select class="input_sel validate[required] select" name="type" id="companyTypes">
						<option  value=""> </option>
						<?php foreach( $this->item->types as $type ) { ?>
							<option <?php echo $this->item->type==$type->id? "selected" : ""?> value='<?php echo $type->id?>'><?php echo $type->name ?></option>
						<?php } ?>
					</select>
					<div class="clear"></div>
				</div>
				
				<div class="detail_box">
					<label for="price"><?php echo JText::_('LNG_PRICE')?> </label> 
					<input type="text"
						name="price" id="price" class="input_txt"
						value="<?php echo $this->item->price ?>">
					<div class="clear"></div>
					
				</div>
				
				<div class="detail_box">
					<label for="startDate"><?php echo JText::_('LNG_START_DATE')?> </label> 
					<?php echo JHTML::_('calendar', $this->item->start_date, 'start_date', 'start_date', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<div class="clear"></div>
				</div>
				
				
				<div class="detail_box">
					<label for="endDate"><?php echo JText::_('LNG_END_DATE')?> </label>
					<?php echo JHTML::_('calendar', $this->item->end_date, 'end_date', 'end_date', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<div class="clear"></div>
				</div>
				
				
				<div class="detail_box">
					<label for="startDate"><?php echo JText::_('LNG_START_TIME')?> </label> 
					<input type="text" name="start_time" id="start_time" class="input_txt timepicker" value="<?php echo $this->item->start_time ?>">
					<div class="clear"></div>
				</div>
				
				<div class="detail_box">
					<label for="endDate"><?php echo JText::_('LNG_END_TIME')?> </label>
					<input type="text" name="end_time" id="end_time" class="input_txt timepicker" value="<?php echo $this->item->end_time ?>">
					<div class="clear"></div>
				</div>
				
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="companyId"><?php echo JText::_('LNG_COMPANY')?></label> 
					<select name="company_id" class="inputbox input-medium validate[required]">
						<?php echo JHtml::_('select.options', $this->companies, 'id', 'name', $this->item->company_id);?>
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
						<input type="text"
							name="address" id="route" class="input_txt" value="<?php echo $this->item->address ?>">
						<div class="clear"></div>					
					</div>
					
					<div class="detail_box">
						<label for="subject"><?php echo JText::_('LNG_CITY')?> </label> 
						<input type="text"
							name="city" id="locality" class="input_txt" value="<?php echo $this->item->city ?>">
						<div class="clear"></div>					
					</div>
					
					<div class="detail_box">
						<label for="subject"><?php echo JText::_('LNG_REGION')?> </label> 
						<input type="text"
							name="county" id="administrative_area_level_1" class="input_txt" value="<?php echo $this->item->county ?>">
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
						<div id="company_map">
						</div>
					</div>
					
				</div>
				
			</fieldset>
			
			<fieldset class="boxed form-horizontal" id="repeat-container" style="display:none">
				<h2> <?php echo JText::_('LNG_REPEAT');?></h2>
			
				<div class="control-group">
					<div class="control-label"><label id="frequency-lbl" for="frequency" class="hasTooltip" title=""><?php echo JText::_('LNG_REPEATS'); ?></label></div>
					<div class="controls">
						<fieldset id="frequency" class="radio btn-group btn-group-yesno">
							<input id="jrepeat1" type="radio" value="0" name="frequency" checked="checked">
							<label class="btn" for="jrepeat1">No-repeat</label>
							<input id="jrepeat2" type="radio" value="1" name="frequency">
							<label class="btn" for="jrepeat2">Daily</label>
							<input id="jrepeat3" type="radio" value="2" name="frequency">
							<label class="btn" for="jrepeat3">Weekly</label>
							<input id="jrepeat4" type="radio" value="3" name="frequency">
							<label class="btn" for="jrepeat4">Monthly</label>
							<input id="jrepeat5" type="radio" value="4" name="frequency">
							<label class="btn" for="jrepeat5">Yearly</label>
						</fieldset>
					</div>
				</div>
				<div id="repeat-options" style="display:none">
					<div class="control-group">
						<div class="control-label"><label id="company_name-lbl" for="company_name" class="hasTooltip" title=""><?php echo JText::_('LNG_REPEAT_EVERY'); ?></label></div>
						<div class="controls">
							<select	id="interval" name="interval">
								<?php
									for($i = 1; $i <  31; $i++){
								?>
									<option value = '<?php echo $i?>'> <?php echo $i ?></option>
								<?php }	?>
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_START_ON'); ?></strong><br />Enter the start date" id="menu_item_id-lbl" for="menu_item_id" class="hasTooltip required" title=""><?php echo JText::_('LNG_START_ON'); ?></label></div>
						<div class="controls"><input name="rstart_date" id="rstart_date" value="<?php echo $this->item->start_date?>" disabled="" size="30" type="text"></div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_ENDS'); ?></strong><br />Define when the repeat ends" id="menu_item_id-lbl" for="menu_item_id" class="hasTooltip required" title=""><?php echo JText::_('LNG_ENDS'); ?></label></div>
						<div class="controls">
							<div class="control-row">
								<input type="radio" name="endson" id="endson1" value="1">
								<label title="Ends after a number of occurrences" for="endson1"><?php echo JText::_("LNG_AFTER")?> <input title="Occurrences" disabled="" value="" size="3" name="occurrences" id="occurrences"> <?php echo JText::_('LNG_OCCURANCES'); ?></label>
							</div>
							<div class="control-row">
								<input type="radio" checked="checked" name="endson" id="endson2" value="2">
								<label title="Ends on a specified date" for="endson2">On 
									<?php echo JHTML::_('calendar', $this->item->end_date, 'rend_date', 'rend_date', $this->appSettings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
								</label>
							</div>
						</div>
					</div>
	
					<div id="week-days-group" class="control-group">
						<div class="control-label">
							<label title="" class="hasTooltip"	for="week_days"	 id="week_days-lbl"	data-original-title="&lt;strong&gt;Days&lt;/strong&gt;&lt;br /&gt;The week days the events should be created on."><?php echo JText::_("LNG_DAYS")?></label>
						</div>
						<div class="controls">
							<fieldset class="checkboxes" id="week_days">
								<ul>
									<?php
										for ($i = 0; $i < 7; $i++){
											$day = JBusinessUtil::dayToString($i, true) ;
									?>
										 	<li>
										 		<input type="checkbox" value="<?php echo $i?>" name="week_days[]" id="week_days<?php echo $i?>" aria-invalid="false">
												<label for="week_days<?php echo $i?>" aria-invalid="false"><?php echo $day ?></label>
											</li>
									<?php }?>
								</ul>
							</fieldset>
						</div>
					</div>
		
					<div id="monthly-repeat" class="control-group">
						<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_REPEAT_BY'); ?></strong><br />Repeat by day of the month" id="menu_item_id-lbl" for="menu_item_id" class="hasTooltip required" title=""><?php echo JText::_('LNG_REPEAT_BY'); ?></label></div>
						<div class="controls">
							<span>	
								<input id="dayMonth" title="Repeat by day of the month" type="radio" value="1" checked="checked" name="repeatby"> 
								<label title="Repeat by day of the month" for="dayMonth">day of the month</label>
							</span>
							<span>	
								<input id="dayWeek" title="Repeat by day of the week" type="radio" value="2" name="repeatby">
								<label title="Repeat by day of the week" for=""dayWeek"">day of the week</label></span>
						</div>
					</div>
				</div>
			</fieldset>
			
			<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_EVENT_PICTURES');?></h2>
			<p> <?php echo JText::_('LNG_EVENT_PICTURES_INFORMATION_TEXT');?>.</p>
			<input type='button' name='btn_removefile' id='btn_removefile' value='x' style='display:none'>
			<input type='hidden' name='crt_pos' id='crt_pos' value=''>
			<input type='hidden' name='crt_path' id='crt_path' value=''>
			<TABLE class='picture-table' align='left' border='0'>
				<tr>
					<TD align='left' class="key"><?php echo JText::_('LNG_PICTURES');  ?>:</TD>
				</tr>
				<TR>
					<TD>
						<TABLE class="admintable" align='center'  id='table_event_pictures' name='table_event_pictures' >
							<?php
							foreach( $this->item->pictures as $picture ) { ?>
								<TR>
									<TD align='left'>
										<textarea cols='50' rows='2' name='event_picture_info[]' id='event_picture_info'><?php echo $picture['event_picture_info']?></textarea>
									</TD>
									<td align='center'>
										<img class='img_picture_event' src='<?php echo JURI::root()."/".PICTURES_PATH.$picture["event_picture_path"]?>'/>
										<BR>
										<?php echo basename($picture['event_picture_path'])?>
										<input type='hidden' value='<?php echo $picture["event_picture_enable"]?>' name='event_picture_enable[]' id='event_picture_enable' >
										<input type='hidden' value='<?php echo $picture["event_picture_path"]?>' name='event_picture_path[]' id='event_picture_path'>
									</td>
									<td align='center'>
										<img class='btn_picture_delete' 
											src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
											onclick="
												if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_PICTURE',true)?>'))
													return; 
												var row = jQuery(this).parents('tr:first');
												var row_idx = row.prevAll().length;
												jQuery('#crt_pos').val(row_idx);
												jQuery('#crt_path').val('<?php echo $picture['event_picture_path']?>');
												jQuery('#btn_removefile').click();"/>
									</td>
									<td align='center'>
										<img class='btn_picture_status' 
											src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($picture['event_picture_enable'] ? 'checked' : 'unchecked').".gif"?>'
											onclick="
												var form = document.adminForm;
												var v_status = null;
												var pos = jQuery(this).closest('tr')[0].sectionRowIndex;

												if( form.elements['event_picture_enable[]'].length == null ) {
													v_status  = form.elements['event_picture_enable[]'];
												}
												else {
													v_status  = form.elements['event_picture_enable[]'][pos];
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
			

			<?php  if(isset($isProfile)) { ?>
				<div class="button-row">
					<div class="button-row">
						<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveEventCompanyInformation();">
								<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
						</button>
						<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
								<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
						</button>
					</div>
				</div>
				<div class="clear"></div>		
			<?php  } ?>
	<script  type="text/javascript">
		
		function saveEventCompanyInformation(){
			if(validateCmpForm())
				return false;
				
			jQuery("#task").val('managecompanyevent.save');
			var form = document.adminForm;
			form.submit();
		}
		
		function cancel(){
			jQuery("#task").val('managecompanyevent.cancel');
			var form = document.adminForm;
			form.submit();
		}
		
		function validateCmpForm(){
			var isError = jQuery("#item-form").validationEngine('validate');
			
			return !isError;
		}
		
	</script>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="edit_mode" id="edit_mode" value="" /> 
	<input type="hidden" name="recurring_id" id="recurring_id" value="<?php echo $this->item->recurring_id ?>" /> 
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
	

	<?php echo JHTML::_( 'form.token' ); ?>
	 
</form>
</div>

<div id="edit-event-dialog" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_EDIT_RECURRING_EVENT') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">
					<p>
						<?php echo JText::_('LNG_EDIT_RECURRING_EVENT_INFO') ?>
					</p>
					<fieldset>
						<div>
							<button type="button" class="ui-dir-button" onclick="editEvent()">
								<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_EDIT_ONLY_THIS_EVENT")?></span>
							</button>
							<?php echo JText::_('LNG_EDIT_ONLY_THIS_EVENT_INFO') ?>
						</div>
						<div>
							<button type="button" class="ui-dir-button" onclick="editAllFollowignEvents()">
								<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_EDIT_ALL_FOLLOWINGS_EVENT")?></span>
							</button>
							<?php echo JText::_('LNG_EDIT_ALL_FOLLOWINGS_EVENT_INFO') ?>
						</div>
						<div>
							<button type="button" class="ui-dir-button" onclick="editAllSeriesEvents()">
								<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_EDIT_ALL_SERIES_EVENTS")?></span>
							</button>
							<?php echo JText::_('LNG_EDIT_ALL_SERIES_EVENTS_INFO') ?>
						</div>
					</fieldset>			
					<div class="clearfix clear-left">
						<div class="button-row">
							<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
								<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
							</button>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>




<script>
	jQuery(document).ready(function(){
		jQuery("#item-form").validationEngine('attach');
		jQuery('.timepicker').timepicker({ 'timeFormat': '<?php echo $this->appSettings->time_format?>', 'minTime': '6:00am', });

		initializeAutocomplete();

		jQuery("#jrepeat1").click(function(){
			repeatNone();
		});
		
		jQuery("#jrepeat2").click(function(){
			repeatDaily();
		});
		
		jQuery("#jrepeat3").click(function(){
			repeatWeekly();
		});

		jQuery("#jrepeat4").click(function(){
			repeatMonthly();
		});

		jQuery("#jrepeat5").click(function(){
			repeatYearly();
		});

		jQuery("#endson1").click(function(){
			endsOnOccurances();
		});

		jQuery("#endson2").click(function(){
			endsOnDate();
		});
		
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
  'street_number': 'short_name',
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
      if(att=='street_number'){
    	  jQuery("#route").val(val);	
      }
      if(att=='route'){
    	  jQuery("#"+att).val(jQuery("#"+att).val()+" "+val);	
      }
      else{
      	jQuery("#"+att).val(val);
      }
	  
      
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

function repeatNone(){
	jQuery("#repeat-options").hide();
}

function repeatDaily(){
	console.debug("repeat daily");
	jQuery("#repeat-options").show();
	jQuery("#week-days-group").hide();
	jQuery("#monthly-repeat").hide();
}
function repeatWeekly(){
	jQuery("").hide();
	jQuery("#repeat-options").show();
	jQuery("#week-days-group").show();
	jQuery("#monthly-repeat").hide();	
}
function repeatMonthly(){
	jQuery("#repeat-options").show();
	jQuery("#week-days-group").hide();
	jQuery("#monthly-repeat").show();
}
function repeatYearly(){
	jQuery("#repeat-options").show();
	jQuery("#week-days-group").hide();
	jQuery("#monthly-repeat").hide();
}

function endsOnOccurances(){
	jQuery("#rend_date").prop('disabled', true);
	jQuery("#occurrences").prop('disabled', false);
}

function endsOnDate(){
	jQuery("#rend_date").prop('disabled', false);
	jQuery("#occurrences").prop('disabled', true);
}

function editEvent(){
	jQuery("#edit_mode").val(1);
	Joomla.submitform(jQuery("#task").val(), document.getElementById('item-form'));
	jQuery.unblockUI();
}

function editAllFollowignEvents(){
	jQuery("#edit_mode").val(2);
	console.debug(jQuery("#task").val());
	Joomla.submitform(jQuery("#task").val(), document.getElementById('item-form'));
	jQuery.unblockUI();
}

function editAllSeriesEvents(){
	jQuery("#edit_mode").val(3);
	Joomla.submitform(jQuery("#task").val(), document.getElementById('item-form'));
	jQuery.unblockUI();
}

window.onload = initialize;
</script>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>
