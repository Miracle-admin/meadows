<?php 
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= true;
$saveOrder	= $listOrder == 'ct.ordering';

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task != 'companytypes.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_TYPES_CONFIRM_DELETE', true);?>')) {
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companytypes');?>" method="post" name="adminForm" id="adminForm">
	
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left fltlft">
				<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
				<?php if(!JBusinessUtil::isJoomla3()) {?>
					<button class="btn" type="submit">Search</button>
					<button onclick="document.id('filter_search').value='';this.form.submit();" type="button">Clear</button>
				<?php } ?>
			</div>
			<?php if(JBusinessUtil::isJoomla3()) {?>
				<div class="btn-group pull-left hidden-phone">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button class="btn hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="clr clearfix"> </div>

	<table class="table table-striped adminlist"  id="itemList">
		<thead>
			<tr>
				<th class="hidden-phone" width="1%" >#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'ct.name', $listDirn, $listOrder); ?></th>
				<th width="10%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ct.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'companytypes.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th width="5%" class="nowrap center hidden-phone"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'ct.id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php $count = count($this->items); ?>
			<?php $nrcrt = 1;
			foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'ct.ordering');
				$canCreate = true;
				$canEdit   = true;
				$canChange = true;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td align='center' class="hidden-phone"><?php echo $nrcrt++?></td>
					<td class="center hidden-phone"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td>
						<?php if ($canEdit) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=companytype.edit&id='.$item->id);?>"><?php echo $this->escape($item->name); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->name); ?>
						<?php endif; ?>
					</td>
					<td class="order hidden-phone">
						<?php if ($canChange) : ?>
							<div class="input-prepend">
								<?php if ($saveOrder) :?>
									<?php if ($listDirn == 'asc') : ?>
										<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'companytypes.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
										<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'companytypes.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
									<?php elseif ($listDirn == 'desc') : ?>
										<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'companytypes.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
										<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'companytypes.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
									<?php endif; ?>
								<?php endif; ?>
								<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
							 	<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
							 </div>
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td class="center hidden-phone"><?php echo (int) $item->id; ?></td>
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