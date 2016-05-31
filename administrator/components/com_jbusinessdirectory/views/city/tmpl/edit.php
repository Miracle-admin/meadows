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

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=city');?>" method="post" name="adminForm" id="item-form">

	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_CITY'); ?></legend>
		
		<TABLE class="admintable"  border=0>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_NAME'); ?> :</TD>
				<TD nowrap width=1% align=left>
					<input 
						type		= "text"
						name		= "name"
						id			= "name"
						value		= '<?php echo $this->item->name?>'
						size		= 32
						maxlength	= 128
						AUTOCOMPLETE=OFF
					/>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
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
