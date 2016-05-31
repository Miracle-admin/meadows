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
		if (task != 'companies.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_COMPANIES_CONFIRM_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies');?>" method="post" name="adminForm" id="adminForm">
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
				<select name="filter_type_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_TYPE');?></option>
					<?php echo JHtml::_('select.options', $this->companyTypes, 'value', 'text', $this->state->get('filter.type_id'));?>
				</select>
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
				<th>
					<?php echo JHtml::_('grid.sort', 'LNG_NAME', 'bc.name', $listDirn, $listOrder); ?>
				</th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_LAST_MODIFIED', 'bc.modified', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_CITY', 'bc.city', $listDirn, $listOrder); ?></th>
				<?php if($this->appSettings->enable_packages){?>
					<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_PACKAGE', 'p.name', $listDirn, $listOrder); ?></th>
					<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_PACKAGE_STATUS', 'active', $listDirn, $listOrder); ?></th>
				<?php } ?>
				<th class="hidden-phone" width="5%"><?php echo JHtml::_('grid.sort', 'LNG_VIEW_NUMBER', 'bc.viewCount', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width="5%"><?php echo JHtml::_('grid.sort', 'LNG_WEBSITE_CLICKS', 'bc.websiteCount', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width="5%"><?php echo JHtml::_('grid.sort', 'LNG_CONTACT_NUMBER', 'bc.contactCount', $listDirn, $listOrder); ?></th>
				<th ><?php echo JHtml::_('grid.sort', 'LNG_STATE', 'bc.state', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone"><?php echo JHtml::_('grid.sort', 'LNG_FEATURED', 'bc.featured', $listDirn, $listOrder); ?></th>
				<th ><?php echo JHtml::_('grid.sort', 'LNG_APROVED', 'bc.approved', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" width="1%"><?php echo JHtml::_('grid.sort', 'LNG_ID', 'bc.id', $listDirn, $listOrder); ?></th>
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
			foreach( $this->items as $company)
			{
				?>
				<TR class="row<?php echo $i % 2; ?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD class="hidden-phone" align=center>
						<?php echo JHtml::_('grid.id', $i, $company->id); ?>
					</TD>
					<TD align=left><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=company.edit&id='. $company->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <?php echo $company->name?>
						
					</a> <span class="small"> (<?php echo JText::_("LNG_ALIAS")?>: <?php echo $company->alias?>) </span>
					</TD>
					<td class="hidden-phone">
						<?php echo $company->modified ?>
					</td>
					<td class="hidden-phone">
						<?php echo $company->city ?>
					</td>
					<?php if($this->appSettings->enable_packages){?>
						<td class="hidden-phone">
							<?php echo $company->packageName ?>
						</td>
						<td class="hidden-phone">
						
							<?php echo $company->active==1?JText::_("LNG_ACTIVE")." - ":"" ?>
							<?php echo $company->active==='0'?JText::_("LNG_EXPIRED")." - ":"" ?>
							
							<?php echo $company->paid==1?JText::_("LNG_PAID"):"" ?>
							<?php echo $company->paid==='0'?JText::_("LNG_NOT_PAID"):"" ?>
						</td>
					<?php } ?>
					<td class="center hidden-phone">
						<?php echo $company->viewCount ?>
					</td>
					<td class="center hidden-phone">
						<?php echo $company->websiteCount ?>
					</td>
					<td class="center hidden-phone">
						<?php echo $company->contactCount ?>
					</td>
					<td valign=top align=center>
							<img  
								src ="<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($company->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=company.changeState&id='. $company->id )?> '
											"
							/>
					</td>
					<td class="hidden-phone" valign=top align=center>
							<img  
								src ="<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($company->featured==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=company.changeFeaturedState&id='. $company->id )?> '
											"
							/>
					</td>
					<td>
						<?php  
								switch($company->approved){
									case COMPANY_STATUS_CLAIMED:
										echo JTEXT::_("LNG_NEEDS_CLAIM_APROVAL");
										break;
									case COMPANY_STATUS_CREATED:
										echo JTEXT::_("LNG_NEEDS_CREATION_APPROVAL");
										break;
									case COMPANY_STATUS_DISAPPROVED:
										echo JTEXT::_("LNG_DISAPPROVED");
										break;
									case COMPANY_STATUS_APPROVED:
										echo JTEXT::_("LNG_APPROVED");
										break;
								}

						?>
						&nbsp;
						<img  style="vertical-align: bottom"
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($company->approved==2? "checked.gif" : "unchecked.gif")?>" 
								onclick	="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=company.'.($company->approved==2?"disaprove":"aprove").'&id='. $company->id )?>'"
							/>
					</td>
					<td class="center hidden-phone">
						<span><?php echo (int) $company->id; ?></span>
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
	 <?php // Load the batch processing form. ?>
 	 <?php echo $this->loadTemplate('batch'); ?>
</form>
<?php echo $this->loadTemplate('export'); ?>
<?php echo $this->loadTemplate('import'); ?>