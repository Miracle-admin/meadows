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
		
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<?php 
$appSetings = JBusinessUtil::getInstance()->getApplicationSettings();
$user = JFactory::getUser(); 
?>

<?php  if(isset($isProfile)) { ?>
	<div class="buttons">
	
		<span class="button">
			<button onClick="saveCompanyInformation();return false;" value="<?php echo JText::_('LNG_SAVE'); ?>"><?php echo JText::_('LNG_SAVE'); ?></button>
		</span>

		<span class="button gray">
			<button onClick="cancel()" value="<?php echo JText::_('LNG_CANCEL'); ?>"><?php echo JText::_('LNG_CANCEL'); ?></button>
		</span>
	</div>
	<div class="clear"></div>		
<?php  } ?>

<div class="category-form-container">	
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed">

			<h2> <?php echo JText::_('LNG_PACKAGE_DETAILS');?></h2>
			<p><?php echo JText::_('LNG_DISPLAY_INFO_TXT');?></p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_NAME')?> </label> 
					<input type="text"
						name="name" id="name" class="input_txt" value="<?php echo $this->item->name ?>">
					<div class="clear"></div>
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
								
								echo "<textarea id='description'.$lng' name='description_$lng' class='input_txt' cols='75' rows='10' maxLength='200'>$langContent</textarea>";
								echo "<div class='clear'></div>";
							}
							
							echo JHtml::_('tabs.end');
						}else {
						?>
							<textarea name="description" id="description" class="input_txt validate[required]"  cols="75" rows="5"  maxLength="200"
								 onkeyup="calculateLenght();"><?php echo $this->item->description ?></textarea>
							
						<?php 
						}
						?>
						
				</div>
				
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label title="This field is automatically populated by via braintree." for="price"><?php echo JText::_('LNG_PRICE')?> </label> 
					<input type="text" 
						name="price" id="price" class="input_txt"
						value="<?php echo $this->item->price ?>">
					<div class="clear"></div>
				</div>

				<div class="detail_box" style="display:none">
					<div  class="form-detail req"></div>
					<label for="specialPrice"><?php echo JText::_('LNG_SPECIAL_PRICE')?> </label> 
					<input type="text"
						name=special_price id="special_price" class="input_txt"
						value="<?php echo $this->item->special_price ?>">
					<div class="clear"></div>
				</div>
				
				<div class="detail_box" style="display:none">
					<label for="startDate"><?php echo JText::_('LNG_SPECIAL_START_DATE')?> </label> 
					<?php echo JHTML::_('calendar', $this->item->special_from_date, 'special_from_date', 'special_from_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<div class="clear"></div>
				</div>
				
				<div class="detail_box"style="display:none">
					<label for="endDate"><?php echo JText::_('LNG_SPECIAL_END_DATE');?> </label>
					<?php echo JHTML::_('calendar', $this->item->special_to_date, 'special_to_date', 'special_to_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<div class="clear"></div>
				</div>
				
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="max_pictures-lbl" for="max_pictures"><?php echo JText::_('LNG_MAX_PICTURES')?> </label> 
					<input type="text"	name="max_pictures" id="max_pictures" class="input_txt"	value="<?php echo $this->item->max_pictures ?>">
					<div class="clear"></div>
				</div>
				
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="max_videos-lbl" for="max_videos"><?php echo JText::_('LNG_MAX_VIDEOS')?> </label> 
					<input type="text"	name="max_videos" id="max_videos" class="input_txt"	value="<?php echo $this->item->max_videos ?>">
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="max_attachments-lbl" for="max_attachments"><?php echo JText::_('LNG_MAX_ATTACHMENTS')?> </label> 
					<input type="text"	name="max_attachments" id="max_attachments" class="input_txt" value="<?php echo $this->item->max_attachments ?>">
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="max_categories-lbl" for="max_categories"><?php echo JText::_('LNG_MAX_CATEGORIES')?> </label> 
					<input type="text"	name="max_categories" id="max_categories" class="input_txt"	value="<?php echo $this->item->max_categories ?>">
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label title="This field is automatically populated by via braintree." for="days"><?php echo JText::_('LNG_TIME_PERIOD')?> </label> 
					
					<select  class="input-small" name="time_unit">
						<option value="D" <?php echo  $this->item->time_unit=="D"?"selected":"" ?>><?php echo JText::_('LNG_DAYS')?></option>
						<option value="W" <?php echo  $this->item->time_unit=="W"?"selected":"" ?>><?php echo JText::_('LNG_WEEKS')?></option>
						<option value="M" <?php echo  $this->item->time_unit=="M"?"selected":"" ?>><?php echo JText::_('LNG_MONTHS')?></option>
						<option value="Y" <?php echo  $this->item->time_unit=="Y"?"selected":"" ?>><?php echo JText::_('LNG_YEARS')?></option>
					</select>
					
					<input  class="input-small" type="text"	name="time_amount" id="time_amount" class="input_txt" value="<?php echo $this->item->time_amount ?>">
					
					(<?php echo $this->item->days ?> <?php echo JText::_('LNG_DAYS')?>)
					
					<div class="clear"></div>
				</div>
			</div>
			</fieldset>
			
			<fieldset class="boxed">
				<h2> <?php echo JText::_('LNG_PACKAGE_FEATURES');?></h2>
				<p> <?php echo JText::_('LNG_PACKAGE_FEATURES_INFORMATION_TEXT');?>.</p>
				<div class="form-box">
					<div class="detail_box">
						<div  class="form-detail req"></div>
						<label for="subject"><?php echo JText::_('LNG_FEATURES')?> </label> 
						
						<select id="features" class="multiselect" multiple="multiple" name="features[]" size="10">
					    <?php
						    foreach($this->features as $key=>$feature){
						    	if(in_array($key, $this->selectedFeatures)>0)
						    		$selected = "selected='selected'";
						    	else
						    		$selected = "";
						    	echo "<option value='$key' $selected>$feature</option>";
						    }
						    
						    foreach($this->customFeatures as $feature){
						    	if(in_array($feature->code, $this->selectedFeatures)>0)
						    		$selected = "selected='selected'";
						    	else
						    		$selected = "";
						    	echo "<option value='$feature->code' $selected>$feature->name</option>";
						    }
					    ?>
					     </select>
					
						<div class="clear"></div>
					</div>
				</div>
					
			</fieldset>
			

	<script  type="text/javascript">
			jQuery(document).ready(function(){
		
				jQuery(".multiselect").multiselect();

				jQuery("#price").change(function(){
					if(jQuery(this).val() == 0 ){
						jQuery("#days").val(0);
					}
				});
			});
		function validatePackageForm(){
			
			var form = document.adminForm;
			var isError = false;

		
			
			return isError;
		}
	</script>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
	<?php echo JHTML::_( 'form.token' ); ?>
	 
</form>
</div>
