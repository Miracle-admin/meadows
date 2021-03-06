<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2015
 * @file name	:	views/admconfig/tmpl/editlocation.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Locations(jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.tooltip');
 JHtml::_('behavior.framework', true);
 JHtml::_('behavior.formvalidation');
 JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
<!--
Joomla.submitbutton = function(task){
	 if (task == 'admconfig.cancelplanbenefits' || document.formvalidator.isValid(document.id('editplanbenefits-form'))) {
	 	Joomla.submitform(task, document.getElementById('editplanbenefits-form'));
	 }
	 else {
	 	alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
	 }
}

//-->
</script>
<form action="index.php" method="post" id="editplanbenefits-form" name="adminForm" class="form-validate form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'locationinfo')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'locationinfo', JText::_('COM_JBLANCE_DETAILS', true)); ?>
	<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
	    		<label class="control-label hasTip" title="Title" for="title"><?php echo JText::_('Benefit Name'); ?>:</label>
				<div class="controls">
					<input class="input-xlarge input-large-text required" type="text" name="title" id="title" value="<?php echo $this->row->title; ?>" />
				</div>
	  		</div>
			<div class="control-group">
	    		<label class="control-label" for="parent_id"><?php echo JText::_('COM_JBLANCE_PARENT_ITEM'); ?>:</label>
				<div class="controls">
					<?php 
					$select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
					$attribs = "class='input-xlarge' size='15'";
					$locationTree = $select->getSelectPlanbenefitsTree('parent_id', $this->row->parent_id, $this->row->id);
					echo $locationTree;
					?>
				</div>
	  		</div>
			
				<div class="control-group">
	    		<label class="control-label hasTip" title="Enter a description about this plan benefit." for="ben_desc"><?php echo JText::_('Description'); ?>:</label>
				<div class="controls">
			    <textarea name='ben_desc'><?php echo $this->row->ben_desc; ?></textarea>
				</div>
	  		</div>
			
			<div class="control-group">
	    		<label class="control-label hasTip" title="Select the content type of this plan benefit, check mark(✔) / custom ." for="ben_type"><?php echo JText::_('Benefit content type'); ?>:</label>
			<div class="controls">
			<select size="1" class="input-medium required advancedSelect chzn-done" name="ben_type" id="ben_type" aria-required="true" required="required" style="display: none;">
	       <option <?php echo $this->row->ben_type=="checkmark" ?"selected='selected'":''; ?> value="checkmark">Checkmark</option>
	        <option <?php echo $this->row->ben_type=="custom" ?"selected='selected'":''; ?> value="custom">Customizable text</option>
	        </select>
			</div>
	  		</div>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>	<!-- end of location info tab -->
	
	<?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'options', JText::_('JOPTIONS', true)); ?>
	<?php 
	$params = new JRegistry;
	$params->loadString($this->row->params);
	?>
	<!--<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
	    		<label class="control-label" for="paramsLatitude"><?php echo JText::_('COM_JBLANCE_LATITUDE'); ?>:</label>
				<div class="controls">
					<input class="input-small" type="text" name="params[latitude]" id="paramsLatitude" value="<?php echo $params->get('latitude', 0); ?>" />
				</div>
	  		</div>
			<div class="control-group">
	    		<label class="control-label" for="paramsLongitude"><?php echo JText::_('COM_JBLANCE_LONGITUDE'); ?>:</label>
				<div class="controls">
					<input class="input-small" type="text" name="params[longitude]" id="paramsLongitude" value="<?php echo $params->get('longitude', 0); ?>" />
				</div>
	  		</div>
	  	</div>
	</div>-->
	<?php //echo JHtml::_('bootstrap.endTab'); ?>	<!-- end of options tab -->
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="savelocation" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="lft" />
	<input type="hidden" name="rgt" />
	<input type="hidden" name="level" />
    <?php echo JHtml::_('form.token'); ?>
</form>