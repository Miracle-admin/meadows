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
		if (task != 'reviews.delete' || confirm('<?php echo JText::_('COM_JBUSINESSDIRECTORY_REVIEW_CONFIRM_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=reviews');?>" method="post" name="adminForm" id="adminForm">
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
				<th class="">
					<?php echo JHtml::_('grid.sort', 'LNG_NAME', 'cr.name', $listDirn, $listOrder); ?>
				</th>
				<th  class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_SUBJECT', 'cr.subject', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JText::_('LNG_DESCRIPTION'); ?></th>
				<th class=""><?php echo JHtml::_('grid.sort', 'LNG_RATING', 'cr.rating', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_USER', 'u.username', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width="5%" class="nowrap"><?php echo JHtml::_('grid.sort', 'LNG_LIKE_COUNT', 'cr.likeCount', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width="5%" class="nowrap"><?php echo JHtml::_('grid.sort', 'LNG_DISLIKE_COUNT', 'cr.dislikeCount', $listDirn, $listOrder); ?></th>
				<th><?php echo JHtml::_('grid.sort', 'LNG_COMPANY', 'companyName', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_CREATION_DATE', 'cr.creationDate', $listDirn, $listOrder); ?></th>
				<th class=""><?php echo JHtml::_('grid.sort', 'LNG_STATE', 'cr.state', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_ID', 'cr.id', $listDirn, $listOrder); ?></th>
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
			foreach($this->items as $review)
			{

			?>
			<TR class="row<?php echo $nrcrt%2?>"
				onmouseover	=	"this.style.cursor='hand';this.style.cursor='pointer'"
				onmouseout	=	"this.style.cursor='default'"
			>
				<TD align=center class="hidden-phone"><?php echo $nrcrt++?></TD>
				<TD align=center class="hidden-phone">
						<?php echo JHtml::_('grid.id', $nrcrt, $review->id); ?>
				</TD>
				<td class="hidden-phone">
					<?php echo $review->name?>
				</td>
				<TD align=left  class="">
					
					<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=review.edit&id='. $review->id )?>'
						title		= 	"<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"
					>
						<B><?php echo $review->subject?></B>
					</a>	
					
				</TD>
				<td class="hidden-phone">
					<?php echo $review->description?>
				</td>
				<td class="">
					<?php echo $review->rating?>
				</td>
				<td class="hidden-phone">
					<?php echo $review->username?>
				</td>
				<td align=center class="hidden-phone">
					<?php echo $review->likeCount?>
				</td>
				<td align=center class="hidden-phone">
					<?php echo $review->dislikeCount?>
				</td>
				<td>
					<?php echo $review->companyName?>
				</td>
				<td class="hidden-phone" align=center>
					<?php echo JBusinessUtil::getDateGeneralFormatWithTime($review->creationDate)?>
				</td>
				<td valign=top align=center>
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($review->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=reviews.chageState&id='. $review->id )?> '
											"
							/>
				</td>
				<td class="hidden-phone">
					<?php echo $review->id?>
				</td>
			</TR>
			<?php
			}
			?>
			</tbody>
		</table>
	 
	
	 
	 <input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" value="" /> 
	 <input type="hidden" name="id" value="" />
	 <input type="hidden" name="boxchecked" value="0" />
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>