
<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );


$user = JFactory::getUser();
if($user->id == 0){
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_('index.php?option=com_users&view=login'));
}

$isProfile = true;
//include(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'manageevents'.DS.'tmpl'.DS.'default.php');
?>
<script>
	var isProfile = true;
</script>
<style>
#header-box, #control-panel-link{
	display: none;
}
</style>

<h1 class="title">
	<?php echo JTEXT::_("LNG_EVENTS") ?>
</h1>

<div class="button-row right">
	<button type="button" class="ui-dir-button ui-dir-button-green" onclick="addDirEvent()">
			<span class="ui-button-text"><i class="dir-icon-plus-sign"></i> <?php echo JText::_("LNG_ADD_NEW_EVENT")?></span>
	</button>

	<a class="ui-dir-button ui-dir-button-grey" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=useroptions' )?>">
		<span class="ui-button-text"><i class="dir-icon-dashboard"></i> <?php echo JText::_("LNG_CONTROL_PANEL")?></span>
	</a>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyevents');?>" method="post" name="eventForm" id="adminForm">
	<div id="editcell">
		<TABLE class="dir-table">
			<thead>
				<tr>
					<th class="hidden-xs hidden-phone" width='1%'>#</th>
					<th width='13%' ><?php echo JText::_( 'LNG_NAME'); ?></th>
					<th class="hidden-xs hidden-phone" width='13%' ><?php echo JText::_( 'LNG_COMPANY'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' ><?php echo JText::_( 'LNG_TYPE'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' ><?php echo JText::_( 'LNG_LOCATION'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' ><?php echo JText::_( 'LNG_START_DATE'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' ><?php echo JText::_( 'LNG_END_DATE'); ?></th>
					<th class="hidden-xs hidden-phone"><?php echo JText::_( 'LNG_VIEW_NUMBER'); ?></th>
					<th width='10%' ><?php echo JText::_( 'LNG_STATE'); ?></th>
					<th class="hidden-xs hidden-phone" nowrap width='1%' ><?php echo JText::_('LNG_ID'); ?></th>
					<th></th>
				</tr>
			</thead>
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
					<TD class="center hidden-xs hidden-phone"><?php echo $nrcrt++?></TD>
					<TD align=left><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyevent.edit&'.JSession::getFormToken().'=1&id='. $event->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B><?php echo $event->name?>
						</B>
					</a>
					</TD>
					<td class="hidden-xs hidden-phone">
						<?php echo $event->companyName ?>
					</td>
					<td class="hidden-xs hidden-phone">
						<?php echo $event->type?>
					</td>
					<td class="hidden-xs hidden-phone">
						<?php echo $event->address.', '.$event->city ?>
					</td>
					<td class="hidden-xs hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($event->start_date) ?>
					</td>
					<td class="hidden-xs hidden-phone">
						<?php echo JBusinessUtil::getDateGeneralFormat($event->end_date) ?>
					</td>
					<td class="hidden-xs hidden-phone">
						<?php echo $event->view_count ?>
					</td>
					<td valign=top align=center>
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/images/".($event->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyevent.chageState&id='. $event->id )?> '
											"
							/>
					</td>
					
					<td class="hidden-xs hidden-phone">
						<?php echo $event->id?>
					</td>
					<td nowrap="nowrap">
						<a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyevent.edit&'.JSession::getFormToken().'=1&id='. $event->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"><?php echo JText::_('LNG_EDIT'); ?>
						</a>
						&nbsp;|&nbsp;
						<a href="javascript:deleteDirEvent(<?php echo $event->id ?>)"><?php echo JText::_('LNG_DELETE'); ?></a>
					</td>
				</TR>
			<?php
				$i++;
			}
			?>
			</tbody>
			<tfoot>
			    <tr>
			      <td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
			    </tr>
			 </tfoot>
		</TABLE>
	</div>
	<input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" id="task" value="" /> 
	 <input type="hidden" name="cid" id="id" value="" />
	 <input type="hidden" name="delete_mode" id="delete_mode" value="" />
	 <input type="hidden" name="Itemid" id="Itemid" value="163" />
	 <input type="hidden" name="companyId" id="companyId" value="<?php echo $this->companyId ?>" />
	 	
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>
<div class="clear"></div>

<div id="delete-event-dialog" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_DELETE_RECCURING_EVENT') ?></h3>
		  		<div class="dialogContentBody" id="dialogContentBody">
					<p>
						<?php echo JText::_('LNG_DELETE_RECCURING_EVENT_INFO') ?>
					</p>
					<fieldset>
						<div>
							<button type="button" class="ui-dir-button ui-dir-button" onclick="deleteEvent()">
								<span class="ui-button-text"> <?php echo JText::_("LNG_ONLY_THIS_EVENT")?></span>
							</button>
							<?php echo JText::_('LNG_DELETE_ONLY_THIS_EVENT_INFO') ?>
						</div>
						<div>
							<button type="button" class="ui-dir-button ui-dir-button" onclick="deleteAllFollowignEvents()">
								<span class="ui-button-text"> <?php echo JText::_("LNG_FOllOWINGS_EVENT")?></span>
							</button>
							<?php echo JText::_('LNG_DELETE_ALL_FOllOWINGS_EVENT_INFO') ?>
						</div>
						<div>
							<button type="button" class="ui-dir-button ui-dir-button" onclick="deleteAllSeriesEvents()">
								<span class="ui-button-text"> <?php echo JText::_("LNG_ALL_SERIES_EVENTS")?></span>
							</button>
							<?php echo JText::_('LNG_DELETE_ALL_SERIES_EVENTS_INFO') ?>
						</div>
					</fieldset>			
				</div>
		</div>
	</div>
</div>

<script>
	function editEvent(eventId){
		jQuery("#id").val(eventId);
		jQuery("#task").val("managecompanyevent.edit");
		jQuery("#adminForm").submit();
	}

	function addDirEvent(){
		jQuery("#id").val(0);
		jQuery("#task").val("managecompanyevent.add");
		jQuery("#adminForm").submit();
	}

	function deleteDirEvent(eventId){
		jQuery("#id").val(eventId);
		showDeleteDialog();
		/*
		if(confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_EVENTS_CONFIRM_DELETE', true);?>')){
			jQuery("#id").val(eventId);
			jQuery("#task").val("managecompanyevents.delete");
			jQuery("#eventForm").submit();
		}*/
	}

	function showDeleteDialog(){
		jQuery.blockUI({ message: jQuery('#delete-event-dialog'), css: {width: 'auto',top: '10%', left:"0", position:"absolute"} });
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
	} 

	function deleteEvent(){
		jQuery("#delete_mode").val(1);
		Joomla.submitform('managecompanyevents.delete');
		jQuery.unblockUI();
	}

	function deleteAllFollowignEvents(){
		jQuery("#delete_mode").val(2);
		Joomla.submitform('managecompanyevents.delete');
		jQuery.unblockUI();
	}
	
	function deleteAllSeriesEvents(){
		jQuery("#delete_mode").val(3);
		Joomla.submitform('managecompanyevents.delete');
		jQuery.unblockUI();
	}
</script>

