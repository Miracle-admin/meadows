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

$user		= JFactory::getUser();
$canOrder	= true;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'p.ordering';


?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task != 'companies.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_PAYMENT_PROCESSORS_CONFIRM_DELETE', true,true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=paymentprocessors');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
	
			
			<div class="filter-search btn-group pull-left fltlft">
				<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL',true); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC',true); ?>" />
				<?php if(!JBusinessUtil::isJoomla3()) {?>
					<button class="btn" type="submit">Search</button>
					<button onclick="document.id('filter_search').value='';this.form.submit();" type="button">Clear</button>
				<?php } ?>
			</div>
			<?php if(JBusinessUtil::isJoomla3()) {?>
				<div class="btn-group pull-left hidden-phone">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT',true); ?>"><i class="icon-search"></i></button>
					<button class="btn hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR',true); ?>"><i class="icon-remove"></i></button>
				</div>
			<?php } ?>
			
			
			<div class="filter-select pull-right fltrt btn-group">
				<select name="filter_status_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATE',true);?></option>
					<?php echo JHtml::_('select.options', $this->statuses, 'value', 'text', $this->state->get('filter.status_id'));?>
				</select>
			</div>
		</div>
	</div>

	<div class="clr clearfix"> </div>
	
	<table class="table table-striped adminlist"  id="itemList">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL',true); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'p.name', $listDirn, $listOrder); ?></th>
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_TYPE', 'p.type', $listDirn, $listOrder); ?></th>
				<th width='30%' class="hidden-phone"><?php echo JText::_('LNG_CONFIGURATION',true); ?></th>	
				<th nowrap="nowrap" width="25%" class="nowrap hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'p.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'paymentprocessors.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_DISPLAY_FRONT', 'p.displayfront', $listDirn, $listOrder); ?></th>
				<th width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_STATUS', 'p.status', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='1%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_ID', 'p.id', $listDirn, $listOrder); ?></th>
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

			
			<?php
			$nrcrt = 1;
			$i=0;
			
			$count = count($this->items);
			foreach( $this->items as $item)
			{
				$ordering  = ($listOrder == 'p.ordering');
				$canCreate  = true;
				$canEdit    = true;
				$canChange  = true;
				?>
				<TR class="row<?php echo $nrcrt%2?>"
				onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
				onmouseout	=	"this.style.cursor='default'"
			>
				<TD align=center class="hidden-phone"><?php echo $nrcrt++?></TD>
				<TD align=center class="hidden-phone">
						<?php echo JHtml::_('grid.id', $nrcrt, $item->id); ?>
				</TD>
					<TD align=left>
						<a
							href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=paymentprocessor.edit&id='. $item->id )?>'
							title="<?php echo JText::_('LNG_CLICK_TO_EDIT',true); ?>"> 
							<B><?php echo $item->name?></B>
						</a>
					</TD>
				
					<td>
						<?php echo $item->type ?>
					</td>
					<td class="hidden-phone">
						<table>
						<tr>
								<td>
									<?php echo JText::_('LNG_MODE',true);?>
								</td>
								<td>
									<?php echo $item->mode ?>
								</td>
							</tr>
						<?php foreach($item->processorFields as $field){?>
							<tr>
								<td>
									<?php echo JText::_("LNG_".strtoupper($field->column_name),true);?>
								</td>
								<td>
									<?php echo $field->column_value ?>
								</td>
							</tr>
							
						<?php } ?>
							
						</table>
					</td>
										
					<td class="order hidden-phone">
						<?php if ($canChange) : ?>
							<div class="input-prepend">
							<?php if ($saveOrder) :?>
								<?php if ($listDirn == 'asc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'paymentprocessors.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'paymentprocessors.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, true, 'paymentprocessors.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
									<span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $count, true, 'paymentprocessors.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
						 	<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="width-20 text-area-order" />
						 </div>
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					
					<td class="center hidden-phone">
							<img src ="<?php echo JURI::base() ."components/com_jbusinessdirectory/assets/img/".($item->displayfront==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=paymentprocessors.changeFrontState&id='. $item->id )?>'"
							/>
					</td>
				
					<td align=center>
							<img src ="<?php echo JURI::base() ."components/com_jbusinessdirectory/assets/img/".($item->status==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=paymentprocessors.changeState&id='. $item->id )?>'"
							/>
					</td>
				
					<td class="hidden-phone">
						<?php echo $item->id?>
					</td>
				</TR>
			<?php
				$i++;
			}
			?>
			</tbody>
		</table>
	 
	 <input type="hidden" name="task" value="" /> 
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>