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
		if (task == 'order.cancel' || !validateCmpForm())
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
	<fieldset class="adminform">
		<legend> <?php echo JText::_('LNG_EDIT_ORDER'); ?></legend>

		<TABLE class="admintable" align="left" >
			<tr>
				<td class="key"><?php echo JText::_('LNG_ORDER_ID'); ?></td>
				<td><?php echo $this->item->order_id ?></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_COMPANY'); ?></td>
				<td><?php echo $this->item->companyName ?></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<TD class="key"><?php echo JText::_('LNG_PACKAGE'); ?></TD>
				<td><?php echo $this->item->packageName ?></td>
				<TD>&nbsp;</TD>
			</tr>	
			<tr>
				<td class="key"><?php echo JText::_('LNG_AMOUNT'); ?></td>
				<td><input type="text" class="validate[required] text-input" name="amount" id="amount" size="20" value="<?php echo $this->item->amount ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr style="display:none"> 
				<td class="key"><?php echo JText::_('LNG_DISCOUNT_AMOUNT'); ?></td>
				<td><input type="text" class="validate[required] text-input" name="discount_amount" id="discount_amount" size="20" value="<?php echo $this->item->discount_amount ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_PAYMENT_DATE'); ?></td>
				<td><?php echo JHTML::_('calendar', $this->item->paid_at, 'paid_at', 'paid_at', $this->appSettings->calendarFormat, array('class'=>'inputbox validate[required] text-input', 'size'=>'10',  'maxlength'=>'10')); ?></td>				
				<TD>&nbsp;</TD>
			</tr>
			
			<tr>
				<td class="key"><?php echo JText::_('LNG_TRANSACTION_ID'); ?></td>
				<td><input type="text" name="transaction_id" id=""transaction_id"" size="20" value="<?php echo $this->item->transaction_id ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			
			<tr>
				<td class="key"><?php echo JText::_('LNG_STATE'); ?></td>
				<td>
					<select name="state" id="state" class="inputbox input-medium">
						<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->item->state);?>
					</select>
				</td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_START_DATE'); ?></td>
				<td><?php echo JHTML::_('calendar', $this->item->start_date, 'start_date', 'start_date', $this->appSettings->calendarFormat, array('class'=>'inputbox  validate[required]', 'size'=>'10',  'maxlength'=>'10')); ?></td>				
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_CREATION_DATE'); ?></td>
				<td><?php echo $this->item->created ?></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_ID'); ?></td>
				<td><?php echo $this->item->id ?></td>
				<TD>&nbsp;</TD>
			</tr>	
			
		</TABLE>
	</fieldset>
	
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>
<script>
	jQuery(document).ready(function(){
		jQuery("#item-form").validationEngine('attach');
	});

	function validateCmpForm(){
		var isError = jQuery("#item-form").validationEngine('validate');
		return !isError;
	}
</script>