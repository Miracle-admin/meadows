<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'edithooks.cancel' || document.formvalidator.isValid(document.id('edithooks-form')))
		{
			Joomla.submitform(task, document.getElementById('edithooks-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_braintreehooks&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="edithooks-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('webhook_name'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('webhook_name'); ?></div>
		</div>
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Edithooks', $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('last_triggered'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('last_triggered'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('active'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('active'); ?></div>
			</div>
<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('active'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('logs'); ?></div>
			</div>				
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('trigger_count'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('trigger_count'); ?></div>
			</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>