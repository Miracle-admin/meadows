<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/showmybid.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	List of projects posted by the user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHtml::_('behavior.tooltip');
 JHtml::_('behavior.modal', 'a.jb-modal');
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");
 
 $model 				= $this->getModel();
 $user					= JFactory::getUser();
 $config 				= JblanceHelper::getConfig();
 $projhelp 				= JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $sealProjectBids	  	= $config->sealProjectBids;
 
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
 
 $link_deposit  = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_MY_BIDS'); ?></div>
	
	<?php if(empty($this->rows)) : ?>
	<div class="alert alert-info">
  		<?php echo JText::_('COM_JBLANCE_NO_BIDS_YET'); ?>
	</div>
	<?php else : ?>
	<?php
	
	for($i=0; $i < count($this->rows); $i++){
		$row 			  = $this->rows[$i];
		$link_accept_bid  = JRoute::_('index.php?option=com_jblance&task=project.acceptbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_deny_bid	  = JRoute::_('index.php?option=com_jblance&task=project.denybid&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_retract_bid = JRoute::_('index.php?option=com_jblance&task=project.retractbid&id='.$row->id.'&'.JSession::getFormToken().'=1');
		$link_edit_bid    = JRoute::_('index.php?option=com_jblance&view=project&layout=placebid&projectId='.$row->proj_id);
		$link_invoice 	  = JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->proj_id.'&tmpl=component&print=1&type=project&usertype=freelancer');
		$link_pay_comp	  = JRoute::_('index.php?option=com_jblance&task=project.paymentcomplete&id='.$row->proj_id.'&'.JSession::getFormToken().'=1');
		$link_progress 	  = JRoute::_('index.php?option=com_jblance&view=project&layout=projectprogress&id='.$row->id.'&pid='.$row->project_id);	// id is the bid id and NOT project id
	?>
	<div class="row-fluid">
		<div class="span5">
			<h4>
				<?php echo LinkHelper::getProjectLink($row->proj_id, $row->project_title); ?>
				<ul class="promotions" style="margin-top: 5px;">
					<?php if($row->is_featured) : ?>
					<li data-promotion="featured"><?php echo JText::_('COM_JBLANCE_FEATURED'); ?></li>
					<?php endif; ?>
					<?php if($row->is_private) : ?>
		  			<li data-promotion="private"><?php echo JText::_('COM_JBLANCE_PRIVATE'); ?></li>
		  			<?php endif; ?>
					<?php if($row->is_urgent) : ?>
		  			<li data-promotion="urgent"><?php echo JText::_('COM_JBLANCE_URGENT'); ?></li>
		  			<?php endif; ?>
		  			<?php if($sealProjectBids || $row->is_sealed) : ?>
					<li data-promotion="sealed"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></li>
					<?php endif; ?>
					<?php if($row->is_nda) : ?>
					<li data-promotion="nda"><?php echo JText::_('COM_JBLANCE_NDA'); ?></li>
					<?php endif; ?>
				</ul>
			</h4>
			<div class="action-bar">
			<?php
				if($row->assigned_userid == $user->id){
					if($row->status == ''){ ?>
						<!-- check if the user has enough fund and check fund is enabled to accept the bid -->
						<div class="alert">
							<strong><?php echo JText::_('COM_JBLANCE_BID_WON'); ?></strong><br>
							<a href="<?php echo $link_accept_bid; ?>" class="btn btn-success btn-small"><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>
							
							<!--<a href="javascript:void(0);" class="btn btn-success btn-small" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_ACCEPT'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_ACCEPT_BID_NO_FEE', true); ?>', '<?php echo $link_accept_bid; ?>');" ><?php echo JText::_('COM_JBLANCE_ACCEPT'); ?></a>-->
							
							<?php echo JText::_('COM_JBLANCE_OR'); ?> 
							
							<a href="javascript:void(0);" class="btn btn-danger btn-small" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_DENY'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_DENY_BID'); ?>', '<?php echo $link_deny_bid; ?>');" ><?php echo JText::_('COM_JBLANCE_DENY'); ?></a> ?
						</div>
				<?php	
					}
					elseif($row->status == 'COM_JBLANCE_ACCEPTED'){
						//get id rate first
						$rate =  $model->getRate($row->project_id, $row->publisher_userid);
						if($rate->quality_clarity == 0){
							$link_rate = JRoute::_('index.php?option=com_jblance&view=project&layout=rateuser&id='.$rate->id); ?>
							<a href="<?php echo $link_rate; ?>" class="btn btn-primary btn-small"><?php echo JText::_('COM_JBLANCE_RATE_BUYER'); ?></a>
				<?php			
						}
					}
				}
				elseif($row->proj_status == 'COM_JBLANCE_OPEN') { ?>
					<a href="javascript:void(0);" class="btn btn-danger btn-small" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_RETRACT_BID'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_RETRACT_BID'); ?>', '<?php echo $link_retract_bid; ?>');" ><?php echo JText::_('COM_JBLANCE_RETRACT_BID'); ?></a>
					<a href="<?php echo $link_edit_bid; ?>" class="btn btn-primary btn-small"><?php echo JText::_('COM_JBLANCE_EDIT_BID'); ?></a>
				<?php
				}
				?>
				<!-- show the print invoice if the commission is > 0 and status is accepted -->
				<?php if(($row->lancer_commission > 0) && ($row->status == 'COM_JBLANCE_ACCEPTED')){ ?>
					<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $link_invoice; ?>" class="jb-modal btn btn-primary btn-small"><?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?></a>
				<?php } ?>
			</div>
		</div>
		<div class="span3">
			<?php echo JText::_('COM_JBLANCE_STATUS'); ?> : <?php echo $model->getLabelProjectStatus($row->proj_status); ?><br>
			<?php echo JText::_('COM_JBLANCE_BIDS'); ?> : <span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($row->amount, true, false, 0); ?></span><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?><br>
			<?php if(!empty($row->status)){?><?php echo JText::_('COM_JBLANCE_BID_STATUS'); ?> : <span class="label label-success"><?php echo JText::_($row->status); ?></span><br><?php } ?>
		</div>
		<div class="span4 text-center">
			<?php if($row->status == 'COM_JBLANCE_ACCEPTED'){ ?>
			<span class="label label-success"><?php echo (!empty($row->p_status)) ? JText::_($row->p_status) : JText::_('COM_JBLANCE_NOT_YET_STARTED'); ?></span><div class="sp10">&nbsp;</div>
			<div class="progress progress-success progress-striped" title="<?php echo JText::_('COM_JBLANCE_PROGRESS').' : '.$row->p_percent.'%'; ?>" style="margin: 0px auto; float: none; width:50%">
				<div class="bar" style="width: <?php echo $row->p_percent; ?>%"></div>
			</div><div class="sp10">&nbsp;</div>
			<a href="<?php echo $link_progress; ?>" class="btn btn-primary btn-small"><?php echo JText::_('COM_JBLANCE_UPDATE_PROGRESS'); ?></a><div class="sp10">&nbsp;</div>
			
			<div class="small payment-status">
				<?php if($enableEscrowPayment) { ?>
					<?php 
					if($row->status == 'COM_JBLANCE_ACCEPTED' && $row->project_type == 'COM_JBLANCE_FIXED'){
						$perc = ($row->paid_amt/$row->amount)*100; $perc = round($perc, 2).'%'; ?>
				<div class="progress progress-success progress-striped" title="<?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS').' : '.$perc; ?>" style="margin: 0px auto; float: none; width:50%">
					<div class="bar" style="width: <?php echo $perc; ?>"></div>
				</div>
					<?php 	
					}
					elseif($row->status == 'COM_JBLANCE_ACCEPTED' && $row->project_type == 'COM_JBLANCE_HOURLY'){ 
						if($row->paid_status == 'COM_JBLANCE_PYMT_PARTIAL'){ ?>
							<a href="javascript:void(0);" class="btn btn-primary btn-small" onclick="javascript:modalConfirm('<?php echo JText::_('COM_JBLANCE_PAYMENT_COMPLETE'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_PAYMENT_COMPLETE'); ?>', '<?php echo $link_pay_comp; ?>');" ><?php echo JText::_('COM_JBLANCE_PAYMENT_COMPLETE'); ?></a>
					<?php 
						}
						if($row->paid_status == 'COM_JBLANCE_PYMT_COMPLETE'){ ?>
						<div class="progress progress-success progress-striped" title="<?php echo JText::_('COM_JBLANCE_PAYMENT_STATUS').' : '.'100%'; ?>" style="margin: 0px auto; float: none; width:50%">
							<div class="bar" style="width: <?php echo '100%'; ?>"></div>
						</div>
						<?php 
						}
						?>
					<?php 
					}
					?>
				<?php } ?>
			</div>
			<?php } ?>

		
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php 
	}
	?>
	<?php endif; ?>
</form>