<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$user = JFactory::getUser();
?>
<style>
.row2 {
	background-color: #e4e4e4;
}
</style>

<h2><?php echo JText::_('COM_BRAINTREEHOOKS_BRAINTREEHOOKS_VIEW_HOOKS_TITLE'); ?></h2>
<form action="<?php JRoute::_('index.php?option=com_mythings&view=mythings'); ?>" method="post" name="adminForm" id="adminForm">
	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<div>
		<p>
			<?php if ($user->authorise("core.create", "com_braintreehooks")) : ?>
				<button type="button" class="btn btn-success" onclick="Joomla.submitform('edithooks.add')"><?php echo JText::_('JNEW') ?></button>
			<?php endif; ?>
			<?php if (($user->authorise("core.edit", "com_braintreehooks")) && isset($this->items[0])) : ?>
				<button type="button" class="btn" onclick="Joomla.submitform('edithooks.edit')"><?php echo JText::_('JEDIT') ?></button>
			<?php endif; ?>
			<?php if ($user->authorise("core.delete", "com_braintreehooks") && isset($this->items[0])) : ?>
				<button type="button" class="btn btn-error" onclick="Joomla.submitform('hooks.delete')"><?php echo JText::_('JDELETE') ?></button>
			<?php endif; ?>
		</p>
	</div>
	<table class="category table table-striped table-bordered table-hover">	
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th id="itemlist_header_title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.webhook_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_BRAINTREEHOOKS_BRAINTREEHOOKS_FIELD_LAST_TRIGGERED_LABEL'), 'a.last_triggered', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_BRAINTREEHOOKS_BRAINTREEHOOKS_FIELD_ACTIVE_LABEL'), 'a.active', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('grid.sort', JText::_('COM_BRAINTREEHOOKS_BRAINTREEHOOKS_FIELD_TRIGGER_COUNT_LABEL'), 'a.trigger_count', $listDirn, $listOrder) ?>
				</th>
				<?php if ($this->user->authorise('core.edit') || $this->user->authorise('core.edit.own')) : ?>
				<th id="itemlist_header_edit"><?php echo JText::_('COM_BRAINTREEHOOKS_EDIT_ITEM'); ?></th>
				<?php endif; ?>
			</tr>
		</thead>		
		<tbody>
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $this->user->authorise('core.edit',       'com_braintreehooks');
		$canEditOwn	= $this->user->authorise('core.edit.own',   'com_braintreehooks');
		$canDelete	= $this->user->authorise('core.delete',       'com_braintreehooks');
		$canCheckin	= $this->user->authorise('core.manage',     'com_checkin');
		$canChange	= $this->user->authorise('core.edit.state', 'com_braintreehooks') && $canCheckin;
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				<td headers="itemlist_header_title" class="list-title">
					<?php if (isset($item->access) && in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_braintreehooks&view=edithooks&id=" . $item->id); ?>">
							<?php echo $this->escape($item->webhook_name); ?>
						</a>
					<?php else: ?>
						<?php echo $this->escape($item->webhook_name); ?>
					<?php endif; ?>
				</td>
				<td style="width:50%"><?php echo $this->escape($item->last_triggered); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->active); ?></td>
				<td style="width:50%"><?php echo $this->escape($item->trigger_count); ?></td>
				<?php if ($this->user->authorise("core.edit") || $this->user->authorise("core.edit.own")) : ?>
				<td headers="itemlist_header_edit" class="list-edit">
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_("index.php?option=com_braintreehooks&task=edithooks.edit&id=" . $item->id); ?>"><i class="icon-edit"></i> <?php echo JText::_("JGLOBAL_EDIT"); ?></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
		<?php endforeach ?>
		</tbody>		
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>	
	</table>
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<!-- Sortierkriterien -->
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>