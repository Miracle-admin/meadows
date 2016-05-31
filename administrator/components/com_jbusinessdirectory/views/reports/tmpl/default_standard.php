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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task != 'companies.delete' || confirm('<?php echo JText::_("COM_JBUSINESS_DIRECTORY_OFFERS_CONFIRM_DELETE", true);?>')) {
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=reports');?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped adminlist" id="itemList">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th nowrap="nowrap" width='1%' ><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'cp.name', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='63%' ><?php echo JHtml::_('grid.sort', 'LNG_DESCRIPTION', 'cp.description', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JText::_('LNG_ACTION') ?></th>
				<th nowrap="nowrap" width='1%' ><?php echo JHtml::_('grid.sort', 'LNG_ID', 'cp.id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $nrcrt = 1; $i=0;
			foreach($this->reports as $item) { ?>
				<TR class="row<?php echo $i % 2; ?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD class="center hidden-phone"><?php echo JHtml::_('grid.id', $i, $item->id); ?></TD>
					<TD align="left">
						<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=report.edit&id='. $item->id )?>' title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> 
							<B><?php echo $item->name?></B>
						</a>
					</TD>
					<TD align="center"><?php echo $item->description; ?></TD>
					<td>
						<a class="ui-dir-button" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=reports&task=reports.generateReport&reportId='. $item->id )?>">
							<span class="ui-button-text"><?php echo JText::_("LNG_GENERATE_REPORT")?></span>
						</a>
					</td>
					<TD align=center><?php echo $item->id; ?></TD>
				</TR>
			<?php $i++; } ?>
		</tbody>
	</table>
	
	<input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" /> 
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php if(isset($this->report)) { ?> <input type="hidden" name="reportId" value="<?php echo $this->report->report->id; ?>" /> <?php } ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br/><br/><br/>
<?php if(isset($this->report)) { ?>
	<table class="report">
		<thead>
			<tr>
				<?php foreach ($this->report->headers as $header){ ?>
					<th><?php echo JText::_($this->params[$header]) ?></th>
				<?php } ?>
	
				<?php foreach ($this->report->customHeaders as $header){ ?>
					<th><?php echo $header ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>	
			<?php foreach ($this->report->data as $data) { ?>
				<tr>
					<?php foreach ($this->report->headers as $header) { ?>
						<td>
							<?php 
								$param = str_replace("cp.", "", $header);
								echo $data->$param;
							?>
						</td>
					<?php } ?>
						
					<?php foreach ($this->report->customHeaders as $header) { ?>
						<td><?php echo !empty($data->customAttributes[$header])?$data->customAttributes[$header]->value:""; ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } ?>