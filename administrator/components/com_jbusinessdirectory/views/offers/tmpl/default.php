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
		if (task != 'offers.delete' || confirm('<?php echo JText::_('ARE_YOU_SURE_YOU_WANT_TO_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=offers');?>" method="post" name="adminForm" id="adminForm">
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
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'co.subject', $listDirn, $listOrder); ?></th>
				<th width='23%' ><?php echo JHtml::_('grid.sort', 'LNG_COMPANY', 'bc.name', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_PUBLISH_START_DATE', 'co.publish_start_date', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_PUBLISH_END_DATE', 'co.publish_end_date', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_START_DATE', 'co.startDate', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_END_DATE', 'co.endDate', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_VIEW_NUMBER', 'co.viewCount', $listDirn, $listOrder); ?></th>
				<th width='10%' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_OFFER_OF_THE_DAY', 'co.offerOfTheDay', $listDirn, $listOrder); ?></th>
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
			foreach( $this->items as $offer)
			{
				?>
				<TR class="row<?php echo $i % 2; ?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $offer->id); ?>
					</TD>
					<TD align=left><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offer.edit&id='. $offer->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B><?php echo $offer->subject?>
						</B>
					</a><span class="small"> (<?php echo JText::_("LNG_ALIAS")?>: <?php echo $offer->alias?>) </span>
					</TD>
					<td>
						<?php echo $offer->companyName ?> 
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($offer->publish_start_date) ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($offer->publish_end_date) ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($offer->startDate) ?>
					</td>
					<td class="hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($offer->endDate) ?>
					</td>
					<td class="hidden-phone">
						<?php echo $offer->viewCount ?>
					</td>
					<td valign=top align=center class="hidden-phone">
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($offer->offerOfTheDay==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offer.changeStateOfferOfTheDay&id='. $offer->id )?> '
											"
							/>
					</td>
					<td valign=top align=center class="hidden-phone">
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($offer->featured==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offer.changeStateFeatured&id='. $offer->id )?> '
											"
							/>
					</td>
					<td valign=top align=center>
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($offer->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offer.chageState&id='. $offer->id )?> '
											"
							/>
					</td>
					<td nowrap="nowrap">
						<?php  
							switch($offer->approved){
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
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($offer->approved==1? "checked.gif" : "unchecked.gif")?>" 
								onclick	="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=offer.'.($offer->approved==1?"disaprove":"aprove").'&id='. $offer->id )?>'"
							/>
					</td>
					<td class="hidden-phone">
						<?php echo $offer->id?>
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
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>