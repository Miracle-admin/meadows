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
		if(task == 'events.delete'){
			//showDeleteDialog();
		}

		 if (task != 'events.delete' || confirm('<?php echo JText::_('ARE_YOU_SURE_YOU_WANT_TO_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=events');?>" method="post" name="adminForm" id="adminForm">
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
				<select name="filter_status_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATUS');?></option>
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
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'co.name', $listDirn, $listOrder); ?></th>
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_COMPANY', 'bc.name', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_TYPE', 'co.type', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_LOCATION', 'co.location', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_START_DATE', 'co.start_date', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_END_DATE', 'co.end_date', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_VIEW_NUMBER', 'co.view_count', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_FEATURED', 'co.featured', $listDirn, $listOrder); ?></th>
				<th width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_STATE', 'co.state', $listDirn, $listOrder); ?></th>
				<th width='10%' ><?php echo JHtml::_('grid.sort', 'LNG_APROVED', 'co.approved', $listDirn, $listOrder); ?></th>
				<th nowrap width='1%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_ID', 'co.id', $listDirn, $listOrder); ?></th>
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
			foreach( $this->items as $event)
			{
				?>
				<TR class="row<?php echo $i % 2; ?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $event->id); ?>
					</TD>
					<TD align=left><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=event.edit&id='. $event->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B><?php echo $event->name?>
						</B> 
					</a>  <span class="small"> (<?php echo JText::_("LNG_ALIAS")?>: <?php echo $event->alias?>) </span>
					</TD>
					<td>
						<?php echo $event->companyName ?>
					</td>
					<td class="hidden-phone" align="center">
						<?php echo $event->eventType?>
					</td>
					<td class="hidden-phone">
						<?php echo $event->address, $event->city ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($event->start_date) ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($event->end_date) ?>
					</td>
					<td class="hidden-phone">
						<?php echo $event->view_count ?>
					</td>
					<td valign=top align=center class="hidden-phone">
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($event->featured==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=event.changeStateFeatured&id='. $event->id )?> '
											"
							/>
					</td>
					<td align=center>
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($event->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=event.chageState&id='. $event->id )?> '
											"
							/>
					</td>
					<td nowrap="nowrap">
						<?php  
							switch($event->approved){
								case 0:
									echo JTEXT::_("LNG_NEEDS_APPROVAL");
									break;
								case -1:
									echo JTEXT::_("LNG_DISAPPROVED");
									break;
								case 1:
									echo JTEXT::_("LNG_APPROVED");
									break;
							}
						?>
						&nbsp;
						<img  style="vertical-align: bottom"
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($event->approved==1? "checked.gif" : "unchecked.gif")?>" 
								onclick	="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=event.'.($event->approved==1?"disaprove":"aprove").'&id='. $event->id )?>'"
							/>
					</td>
					<td class="hidden-phone">
						<?php echo $event->id?>
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
	 <input type="hidden" name="companyId" value="" />
	 <input type="hidden" name="delete_mode" id="delete_mode" value="" />
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>

<div id="delete-event-dialog" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_DELETE_RECURRING_EVENT') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">
					<p>
						<?php echo JText::_('LNG_DELETE_RECURRING_EVENT_INFO') ?>
					</p>
					<fieldset>
						<div>
							<button type="button" class="ui-dir-button" onclick="deleteEvent()">
								<span class="ui-button-text"> <?php echo JText::_("LNG_DELETE_ONLY_THIS_EVENT")?></span>
							</button>
							<?php echo JText::_('LNG_DELETE_ONLY_THIS_EVENT_INFO') ?>
						</div>
						<div>
							<button type="button" class="ui-dir-button" onclick="deleteAllFollowignEvents()">
								<span class="ui-button-text"> <?php echo JText::_("LNG_DELETE_ALL_FOLLOWINGS_EVENT")?></span>
							</button>
							<?php echo JText::_('LNG_DELETE_ALL_FOLLOWINGS_EVENT_INFO') ?>
						</div>
						<div>
							<button type="button" class="ui-dir-button" onclick="deleteAllSeriesEvents()">
								<span class="ui-button-text"> <?php echo JText::_("LNG_DELETE_ALL_SERIES_EVENTS")?></span>
							</button>
							<?php echo JText::_('LNG_DELETE_ALL_SERIES_EVENTS_INFO') ?>
						</div>
						<div class="clearfix clear-left">
						<div class="button-row">
							<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
								<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
							</button>
						</div>
					</div>
					</fieldset>			
				</div>
		</div>
	</div>
</div>

<script>
	function showDeleteDialog(){
		jQuery.blockUI({ message: jQuery('#delete-event-dialog'), css: {width: 'auto',top: '10%', left:"0", position:"absolute"} });
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
	} 

	function deleteEvent(){
		jQuery("#delete_mode").val(1);
		Joomla.submitform('events.delete');
		jQuery.unblockUI();
	}

	function deleteAllFollowignEvents(){
		jQuery("#delete_mode").val(2);
		Joomla.submitform('events.delete');
		jQuery.unblockUI();
	}
	
	function deleteAllSeriesEvents(){
		jQuery("#delete_mode").val(3);
		Joomla.submitform('events.delete');
		jQuery.unblockUI();
	}
</script>