<?php 
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= true;
$saveOrder	= $listOrder == 'c.ordering';

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task != 'countries.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_COUNTRIES_CONFIRM_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=countries');?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped adminlist"  id="itemList">
		<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="15%" class="center">
						<?php echo JHtml::_('grid.sort', 'LNG_NAME', 'c.country_name', $listDirn, $listOrder); ?>
					</th>
					<th width="5%">
						<?php echo JText::_('LNG_LOGO'); ?>
					</th>
					<th  class="center hidden-phone">
						<?php echo JText::_('LNG_DESCRIPTION'); ?>
					</th>
					<th width="5%">
						<?php echo JText::_('JGRID_HEADING_ID'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="15">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php $count = count($this->items); ?>
			<?php foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'c.ordering');
				$canCreate = true;
				$canEdit   = true;
				$canChange = true;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td>
						<?php if ($canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=country.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->country_name); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->country_name); ?>
						<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo !empty($item->logo)?"<img style='height:50px' src='".JURI::root().PICTURES_PATH.$item->logo."'/>":""; ?>
					</td>
					<td class="hidden-phone">
						<?php echo $item->description; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			
			</tbody>
		</table>
	 
	 <input type="hidden" name="task" value="" /> 
	 <input type="hidden" name="id" value="" />
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>