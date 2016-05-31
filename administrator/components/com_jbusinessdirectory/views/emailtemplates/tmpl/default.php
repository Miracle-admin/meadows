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
		if (task != 'emailtemplates.delete' || confirm('<?php echo JText::_('ARE_YOU_SURE_YOU_WANT_TO_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=emailtemplates');?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped adminlist"  id="itemList">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width='20%' align='center'><?php echo JHtml::_('grid.sort', 'LNG_NAME', 'e.email_name', $listDirn, $listOrder); ?></th>
				<th width='20%' align='center'><?php echo JHtml::_('grid.sort', 'LNG_TYPE', 'e.email_type', $listDirn, $listOrder); ?></th>
				<th width='20%' align='center'><?php echo JHtml::_('grid.sort', 'LNG_SUBJECT', 'e.email_subject', $listDirn, $listOrder); ?></th>
				<th width='30%' align='center' class="hidden-phone"><?php echo JText::_('LNG_CONTENT'); ?></th>
				<th width='1%' align='center' class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_ID', 'e.email_id', $listDirn, $listOrder); ?></th>
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
			//if(0)4
			foreach( $this->items as $email)
			{
				?>
				<TR class="row<?php echo $i % 2; ?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $email->email_id); ?>
					</TD>
					<TD align=left>
						<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=emailtemplate.edit&email_id='. $email->email_id )?>'
							title = "<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"
						>
							<B><?php echo $email->email_name?></B>
						</a>	
					</TD>
					<TD align=center><?php echo $email->email_type?></TD>
					<TD align=center><?php echo $email->email_subject?></TD>
					<TD align=left class="hidden-phone"><?php echo $email->email_content?></TD>
					<!-- TD align=center>
						<img border= 1 
							src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($email->is_default==false? "unchecked.gif" : "checked.gif")?>" 
							onclick	=	"	
											<?php
											if( $email->is_default ==false )
											{
											?>
											document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=emailtemplates.state&email_id='. $email->email_id )?> '
											<?php
											}
											?>
										"
					</TD -->
					<td class="center hidden-phone">
						<span><?php echo (int) $email->email_id; ?></span>
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