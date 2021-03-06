<?php
/**
 * @package    JBusinessDirectory
 * @subpackage com_jbusinessdirectory
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_EDIT_REVIEW_ABUSE'); ?></legend>
		<TABLE class="admintable" align="left">
			<tr>
				<td class="key"><?php echo JText::_('LNG_REVIEW_NAME'); ?></td>
				<td><?php echo $this->item->subject ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_EMAIL'); ?></td>
				<td><?php echo $this->item->email ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<TD class="key"><?php echo JText::_('LNG_DESCRIPTION'); ?></TD>
				<td><?php echo $this->item->description ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_STATE'); ?></td>
				<td>
					<select name="state" class="inputbox input-medium">
						<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATE');?></option>
						<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->item->state);?>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_ID'); ?></td>
				<td><?php echo $this->item->id ?></td>
				<td>&nbsp;</td>
			</tr>	
		</TABLE>
	</fieldset>
	
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>