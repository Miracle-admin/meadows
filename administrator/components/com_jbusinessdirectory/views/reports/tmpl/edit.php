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
					<span class="error_msg" id="frmCompanyName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>

				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<textarea name="description" id="description" class="input_txt"  cols="75" rows="5"><?php echo $this->item->description ?></textarea>
					<div class="clear"></div>
					<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>
				
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="price"><?php echo JText::_('LNG_PRICE')?> </label> 
					<input type="text"
						name="price" id="price" class="input_txt"
						value="<?php echo $this->item->price ?>">
					<div class="clear"></div>
					<span class="error_msg" id="frmPrice_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>

				<div class="detail_box" style="display:none">
					<div  class="form-detail req"></div>
					<label for="specialPrice"><?php echo JText::_('LNG_SPECIAL_PRICE')?> </label> 
					<input type="text"
						name=special_price id="special_price" class="input_txt"
						value="<?php echo $this->item->special_price ?>">
					<div class="clear"></div>
					<span class="error_msg" id="frmspecialPrice_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>
				
				<div class="detail_box" style="display:none">
					<label for="startDate"><?php echo JText::_('LNG_SPECIAL_START_DATE')?> </label> 
					<?php echo JHTML::_('calendar', $this->item->special_from_date, 'special_from_date', 'special_from_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<div class="clear"></div>
					<span class="error_msg" id="frmStartDate_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>
				
				<div class="detail_box"style="display:none">
					<label for="endDate"><?php echo JText::_('LNG_SPECIAL_END_DATE');?> </label>
					<?php echo JHTML::_('calendar', $this->item->special_to_date, 'special_to_date', 'special_to_date', $appSetings->calendarFormat, array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10')); ?>
					<div class="clear"></div>
					<span class="error_msg" id="frmEndDate_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>
				
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="days"><?php echo JText::_('LNG_DAYS')?> </label> 
					<input type="text"	name="days" id="days" class="input_txt" value="<?php echo $this->item->days ?>">
					<div class="clear"></div>
					<span class="error_msg" id="frmDays_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
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
						<span class="error_msg" id="frmFeatures_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
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

			jQuery(".error_msg").each(function(){
				jQuery(this).hide();
			});
			
			if( !validateField( form.elements['name'], 'string', false, null ) ){
				jQuery("#frmCompanyName_error_msg").show();
				if(!isError)
					jQuery("#subject").focus();
				isError = true;
			}
				
			if( !validateField( form.elements['description'], 'string', false, null ) ){
				jQuery("#frmDescription_error_msg").show();
				if(!isError)
					jQuery("#description").focus();
				isError = true;
			}

			if( !validateField( form.elements['price'], 'numeric', false, null ) ){
				jQuery("#frmPrice_error_msg").show();
				if(!isError)
					jQuery("#price").focus();
				isError = true;
			}

			if( !validateField( form.elements['days'], 'numeric', false, null ) ){
				jQuery("#frmDays_error_msg").show();
				if(!isError)
					jQuery("#days").focus();
				isError = true;
			}
			/*
			if( !validateField( form.elements['specialPrice'], 'numeric', false, null ) ){
				jQuery("#frmspecialPrice_error_msg").show();
				if(!isError)
					jQuery("#specialPrice").focus();
				isError = true;
			}
			if( !validateField( form.elements['startDate'], 'date', false, null ) ){
				jQuery("#frmStartDate_error_msg").show();
				if(!isError)
					jQuery("#startDate").focus();
				isError = true;
			}
			if( !validateField( form.elements['endDate'], 'date', false, null ) ){
				jQuery("#frmEndDate_error_msg").show();
				if(!isError)
					jQuery("#endDate").focus();
				isError = true;
			}*/
			
			return isError;
		}
	</script>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
	<?php echo JHTML::_( 'form.token' ); ?>
	 
</form>
</div>
