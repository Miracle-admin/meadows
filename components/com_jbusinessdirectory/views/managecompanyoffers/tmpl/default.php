
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
//include(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'manageoffers'.DS.'tmpl'.DS.'default.php');
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
	<?php echo JTEXT::_("LNG_SPECIAL_OFFERS") ?>
</h1>

<div class="button-row right">
	<button type="submit" class="ui-dir-button ui-dir-button-green" onclick="addOffer()">
			<span class="ui-button-text"><i class="dir-icon-plus-sign"></i> <?php echo JText::_("LNG_ADD_NEW_OFFER")?></span>
	</button>
	
	<a class="ui-dir-button ui-dir-button-grey" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=useroptions' )?>">
		<span class="ui-button-text"><i class="dir-icon-dashboard"></i> <?php echo JText::_("LNG_CONTROL_PANEL")?></span>
	</a>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffer');?>" method="post" name="offerForm" id="offerForm">
	<div id="editcell">
		<TABLE class="dir-table">
			<thead>
				<tr>
					<th class="center hidden-xs hidden-phone" width='1%'>#</th>
					<th width='40%' align=center><?php echo JText::_('LNG_SUBJECT'); ?>	</th>
					<th class="hidden-xs hidden-phone" width='10%' align=center><?php echo JText::_('LNG_PRICE'); ?>	</th>
					<th class="hidden-xs hidden-phone" width='10%' align=center><?php echo JText::_('LNG_SPECIAL_PRICE'); ?>	</th>
					<th width='10%' align=center><?php echo JText::_('LNG_START_DATE'); ?></th>
					<th width='10%' align=center><?php echo JText::_('LNG_END_DATE'); ?></th>
					<th width='1%' align=center><?php echo JText::_('LNG_STATE'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>

			<?php
			$nrcrt = 1;
			foreach( $this->items as $offer)
			{
				?>
				<TR class="row<?php echo $nrcrt%2?>"
					onmouseover="this.style.cursor='hand';this.style.cursor='pointer'"
					onmouseout="this.style.cursor='default'">
					<TD class="hidden-xs hidden-phone" align=center><?php echo $nrcrt++?></TD>
					<TD align=left>
						<a href='<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=managecompanyoffer.edit&id='.$offer->id )?>'
							title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> 
							<B><?php echo $offer->subject?></B>
						</a>
					</TD>
					<td class="hidden-xs hidden-phone">
						<?php echo $offer->price?>
					</td>
					<td class="hidden-xs hidden-phone">
						<?php echo $offer->specialPrice ?>
					</td>
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat( $offer->startDate); ?>
					</td>
					<td>
						<?php echo JBusinessUtil::getDateGeneralFormat( $offer->endDate); ?>
					</td>
					<td valign=top align=center>
							<img  
								src ="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/images/".($offer->state==0? "unchecked.gif" : "checked.gif")?>" 
								onclick	=	"	
												document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyoffer.chageState&id='. $offer->id )?> '
											"
							/>
					</td>
					<td>
						<a href="javascript:void(0);" onclick="editOffer(<?php echo $offer->id ?>)"><?php echo JText::_("LNG_EDIT")?></a>
							&nbsp;|&nbsp;
						<a href="javascript:void(0);" onclick="deleteOffer(<?php echo $offer->id ?>)"><?php echo JText::_("LNG_DELETE")?></a>
					</td>
				</TR>
			<?php
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
	 <input type="hidden" name="id" id="id" value="" />
	 <input type="hidden" name="Itemid" id="Itemid" value="163" />
	 <input type="hidden" name="companyId" id="companyId" value="<?php echo $this->companyId ?>" />
	 	
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>
<div class="clear"></div>
<script>
	function editOffer(offerId){
		jQuery("#id").val(offerId);
		jQuery("#task").val("managecompanyoffer.edit");
		jQuery("#offerForm").submit();
	}

	function addOffer(){
		jQuery("#id").val(0);
		jQuery("#task").val("managecompanyoffer.add");
		jQuery("#offerForm").submit();
	}

	function deleteOffer(offerId){
		if(confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_OFFERS_CONFIRM_DELETE', true);?>')){
			jQuery("#id").val(offerId);
			jQuery("#task").val("managecompanyoffers.delete");
			jQuery("#offerForm").submit();
		}
	}
</script>

