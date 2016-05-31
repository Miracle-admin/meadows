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

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task != 'orders.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_ORDERS_CONFIRM_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=orders');?>" method="post" name="adminForm" id="adminForm">
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
			
			
			<div class="filter-select pull-right fltrt btn-group">
				<select name="filter_state_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATE');?></option>
					<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->state->get('filter.state_id'));?>
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
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_ORDER_ID', 'inv.order_id', $listDirn, $listOrder); ?></th>
				<th width='13%' ><?php echo JHtml::_('grid.sort', 'LNG_COMPANY', 'c.name', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_AMOUNT', 'inv.amount', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_CREATED', 'inv.created', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_START_DATE', 'inv.start_date', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_USER', 'inv.user_name', $listDirn, $listOrder); ?></th>
				<th width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_STATE', 'inv.state', $listDirn, $listOrder); ?></th>
				<th  class="hidden-phone" width='10%'> <?php echo JText::_("LNG_EXPIRATION_NOTIFICATION")?></th>
				<th class="hidden-phone" nowrap="nowrap" width='1%' ><?php echo JHtml::_('grid.sort', 'LNG_ID', 'inv.id', $listDirn, $listOrder); ?></th>
				<th></th>
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
			
			//if(0)
			foreach( $this->items as $item)
			{
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
							href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=order.edit&id='. $item->id )?>'
							title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> 
							<B><?php echo $item->order_id?></B>
						</a>
					</TD>
				
					<td>
						<?php echo $item->companyName ?>
					</td>
				
					<td class="hidden-phone">
						<?php echo $item->amount ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($item->created) ?>
					</td>
				
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat($item->start_date) ?>
					</td>
					
					<td class="hidden-phone">
						<?php echo $item->user_name ?>
					</td>
					
					<td nowrap="nowrap">
						<?php 
							 switch ($item->state){
							 	case 0:
							 		echo JText::_("LNG_NOT_PAID");
							 		break;
							 	case 1:
							 		echo JText::_("LNG_PAID");
							 		break;
							 }
						?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormatWithTime($item->expiration_email_date)?>
					</td>
					<td class="hidden-phone">
						<?php echo $item->id?>
					</td>
					<td class="hidden-phone">	
						<a href="javascript:showInvoice(<?php echo  $item->id ?>)"><?php echo JText::_("LNG_VIEW") ?></a>
					</td>
				</TR>
			<?php
				$i++;
			}
			?>
			</tbody>
		</table>
	 
	 <input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" value="" /> 
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>

<div id="invoice" class="invoice" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent" style="padding:10px;">
			<iframe id="invoiceIfr" height="500" src="" scrolling="no">
			
			</iframe>
		</div>
	</div>
</div>

<script>
function showInvoice(invoice){
	var baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=order&layout=invoice&tmpl=component',false); ?>";
	baseUrl = baseUrl + "&id="+invoice;
	jQuery("#invoiceIfr").attr("src",baseUrl);
	jQuery.blockUI({ message: jQuery('#invoice'), css: {width: '700px', top: '5%', position: 'absolute', left:"0"} });
	jQuery('.blockOverlay').click(jQuery.unblockUI); 
	jQuery('.blockUI.blockMsg').center();
}
</script>

