<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/project/tmpl/pickuser.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Pick user from the bidders (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");

 $model 		= $this->getModel();
 $user 			= JFactory::getUser();
 $config 		= JblanceHelper::getConfig();
 
 $currencycode 	= $config->currencyCode;
 $dformat 		= $config->dateFormat;
 $checkFund 	= $config->checkfundPickuser;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 $curr_balance = JblanceHelper::getTotalFund($user->id);
 
 $link_deposit  = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
?>
<script>
<!--
	function checkBalance(){

		if(!$$('input[name="assigned_userid"]:checked')[0]){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_PICK_AN_USER_FROM_THE_LIST', true); ?>');
			return false;
		}
		
		var checkFund = parseInt('<?php echo $checkFund; ?>');

		if(checkFund){
			var balance = parseFloat('<?php echo $curr_balance; ?>');
			var assigned = $$('input[name="assigned_userid"]:checked')[0].get('value');
			var bidamt = $('bidamount_'+assigned).get('value');

			if(balance < bidamt){
				modalConfirm('<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_FUND'); ?>', '<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_BALANCE_PICK_USER'); ?>', '<?php echo $link_deposit; ?>');
				return false;
			}
		}
		return true;	
	}
//-->
</script>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PICK_USER').' : '.$this->project->project_title; ?></div>
	<div class="well well-small pull-right span3 text-center font16">
		<b><?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE'); ?> : <?php echo JblanceHelper::formatCurrency($curr_balance); ?></b>
	</div>
	<div class="clearfix"></div>
	
	<?php
	for($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];
	?>
	<div class="row-fluid">
		<div class="span1" style="width:5px;">
			<?php if($row->status == '') : ?>
			<input type="radio" name="assigned_userid" id="assigned_userid_<?php echo $row->id; ?>" value="<?php echo $row->user_id; ?>"/>
			<?php endif; ?>
		</div>
		<div class="span1">
			<?php
			$attrib = 'width=56 height=56 class="img-polaroid"';
			$avatar = JblanceHelper::getThumbnail($row->user_id, $attrib);
			echo !empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar) : '&nbsp;'; ?>
		</div>
		<div class="span6">
			<h5 class="media-heading">
				<?php echo LinkHelper::GetProfileLink(intval($row->user_id), $this->escape($row->$nameOrUsername)); ?>
			</h5>
			<p class="font14"><?php echo ($row->details) ? $row->details : JText::_('COM_JBLANCE_DETAILS_NOT_PROVIDED'); ?></p>
			<p>
				<span title="<?php echo JText::_('COM_JBLANCE_BID_DATE'); ?>"><i class="icon-calendar"></i> <?php echo JHtml::_('date', $row->bid_date, $dformat); ?></span>
				<!-- Show attachment if found -->
				<?php
				if(!empty($row->attachment)) : ?>
					 | <span><?php echo LinkHelper::getDownloadLink('nda', $row->id, 'project.download'); ?></span>
				<?php	
				endif;
				?>
			</p>
		</div>
		<div class="span2">
			<?php $rate = JblanceHelper::getAvarageRate($row->user_id, true); ?>
		</div>
		<div class="span2">
			<div class="text-center">
				<span class="font20">
					<?php echo JblanceHelper::formatCurrency($row->amount, true, false, 0); ?>
					<input type="hidden" id="bidamount_<?php echo $row->user_id; ?>" value="<?php echo  $row->amount; ?>" />
				</span><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?><br>
				<span class="font12">
				<?php if($row->project_type == 'COM_JBLANCE_FIXED') : ?>
					<?php echo $row->delivery; ?> <?php echo JText::_('COM_JBLANCE_BID_DAYS'); ?>
					<?php elseif($row->project_type == 'COM_JBLANCE_HOURLY') : 
					$commitment = new JRegistry;
					$commitment->loadString($row->commitment);
					?>
					<?php echo $row->delivery; ?> <?php echo JText::_('COM_JBLANCE_HOURS_PER').' '.JText::_($commitment->get('interval')); ?>
					<?php endif; ?>
				</span><br>
				<?php if($row->status == 'COM_JBLANCE_ACCEPTED') : ?>
				<span class="label label-success"><?php echo JText::_($row->status); ?></span>
				<?php elseif($row->status == 'COM_JBLANCE_DENIED') : ?>
				<span class="label label-important"><?php echo JText::_($row->status); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php 
	}
	?>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_PICK_USER'); ?>" class="btn btn-primary" onclick="return checkBalance();" />
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.savepickuser" />	
	<input type="hidden" name="id" value="<?php echo $row->project_id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>