<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_EDIT_REVIEW'); ?></legend>

		<TABLE class="admintable" align="left" >
			<tr>
				<td class="key"><?php echo JText::_('LNG_NAME'); ?></td>
				<td><input type="text" name="name" id="name" size="50" value="<?php echo $this->item->name ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_SUBJECT'); ?></td>
				<td><input type="text" name="subject" id="subject" size="50" value="<?php echo $this->item->subject ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<TD class="key"><?php echo JText::_('LNG_DESCRIPTION'); ?></TD>
				<td><textarea  name="description" id="description" style="width:600px;height:120px" cols="80" rows="10"><?php echo $this->item->description ?></textarea>
				<TD>&nbsp;</TD>
			</tr>	
			<tr>
				<td class="key"><?php echo JText::_('LNG_LIKE_COUNT'); ?></td>
				<td><input type="text" name="likeCount" id="subject" size="50" value="<?php echo $this->item->likeCount ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_DISLIKE_COUNT'); ?></td>
				<td><input type="text" name="dislikeCount" id="subject" size="50" value="<?php echo $this->item->dislikeCount ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
				<tr>
				<td class="key"><?php echo JText::_('LNG_RATING'); ?></td>
				<td><input type="text" name="rating" id="rating" size="50" value="<?php echo $this->item->rating ?>"></td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_STATE'); ?></td>
				<td>
					<select name="state" class="inputbox input-medium">
						<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATE');?></option>
						<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->item->state);?>
					</select>
				</td>
				<TD>&nbsp;</TD>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('LNG_CREATION_DATE'); ?></td>
				<td><?php echo $this->item->creationDate ?></td>
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