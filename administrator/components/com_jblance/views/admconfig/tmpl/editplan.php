<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 March 2012
 * @file name	:	views/admconfig/tmpl/editplan.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of Plans(jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHtml::_('behavior.formvalidation');
 JHtml::_('formbehavior.chosen', 'select');
 JHtml::_('bootstrap.tooltip');
  
 //$editor = JFactory::getEditor();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class selectHelper
 
 $config = JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $parents=$this->parents;
 $parentchild=$this->parentchild;
 $cid= JRequest::getVar('cid');
 $cid = $cid[0];
 $banned = array(13,5);
 $class = "";
 if(!in_array($cid,$banned))
 {
 $class="required";
 }

 ?>
<script type="text/javascript">
<!--
	Joomla.submitbutton = function(task){
		if (task == 'admconfig.cancelplan' || document.formvalidator.isValid(document.id('editplan-form'))) {
			Joomla.submitform(task, document.getElementById('editplan-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'));?>');
		}
	}
//-->
</script>
<form action="index.php" method="post" id="editplan-form" name="adminForm" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span6">
			<fieldset>
				<legend><?php echo JText::_('COM_JBLANCE_PLAN_SETTINGS'); ?></legend>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_PLAN_NAME_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="name" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>:</label>
					<div class="controls">
						<input type="text" class="input-xlarge input-large-text required" name="name" id="name" value="<?php echo $this->row->name; ?>" />
					</div>
		  		</div>
				
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('Please enter the id of corresponding plan on braintree.')); ?>
		    		<label class="control-label hasTooltip" for="pidbt" title="<?php echo $tip; ?>"><?php echo JText::_('Plan id on Braintree'); ?>:</label>
					<div class="controls">
						<input type="text" class="input-xlarge input-large-text <?php echo $class; ?>" name="pidbt" id="pidbt" value="<?php echo $this->row->pidbt; ?>" />
						
						<?php if($this->row->pidbt!=""){ 
						$btPlan = JblanceHelper::getBraintreePlan($this->row->pidbt);
						?>
						
						
						<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">View Braintree Configurations</button>

                        <!-- Modal -->
                        <div id="myModal" style="display:none;" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <img width="150" style="float:left;"  src="<?php echo JUri::root(); ?>images/braintree.png"/>
						<h3 style="text-align: center;"><?php echo $btPlan['name'];?></h3>
                        </div>
                        <div class="modal-body">
						<table class="table table-striped">
						<tbody>
                        <tr><td><b>Id:</b></td><td><?php echo $btPlan['id']; ?></tr>
						<tr><td><b>Merchant Id:</b></td><td><?php echo $btPlan['merchantId']; ?></td></tr>
						<tr><td><b>Billing Day Of Month:</b></td><td><?php echo $btPlan['billingDayOfMonth']; ?></td></tr>
						<tr><td><b>Billing Frequency:</b></td><td><?php echo $btPlan['billingFrequency']; ?> Month(s)</td></tr>
						<tr><td><b>Currency Iso Code:</b></td><td><?php echo $btPlan['currencyIsoCode']; ?></td></tr>
						<tr><td><b>Description:</b></td><td><?php echo $btPlan['description']; ?></td></tr>
						<tr><td><b>Name:</b></td><td><?php echo $btPlan['name']; ?></td></tr>
						<tr><td><b>Number Of Billing Cycles:</b></td><td><?php echo $btPlan['numberOfBillingCycles']; ?></b></td></tr>
						<tr><td><b>Price:</b></td><td>$<?php echo $btPlan['price']; ?></td></tr>
						<tr><td><b>Trial Duration:</b></td><td><?php echo $btPlan['trialDuration']; ?></td></tr>
						<tr><td><b>Trial Duration Unit:</b></td><td><?php echo $btPlan['trialDurationUnit']; ?></b></td></tr>
						<tr><td><b>Trial Period:</b></td><td><?php echo $btPlan['trialPeriod']; ?></td></tr>
						<tr><td><b>Created At:</b></td><td><?php echo $btPlan['createdAt']; ?></td></tr>
						<tr><td><b>Updated At:</b></td><td><?php echo $btPlan['updatedAt']; ?></td></tr>
						<tr><td><b>AddOns:</b></td><td><?php //echo $btPlan['addOns']; ?></td></tr>
						<tr><td><b>Discounts:</b></td><td><?php //echo $btPlan['discounts']; ?></td></tr>
						<tr><td><b>Plans:</b></td><td><?php //echo $btPlan['plans']; ?></td></tr>

						</tbody>
                        </table>
                        </div>
                        <div class="modal-footer">
						<div style="float:left;"><i>Response generated by braintree payment gateway.</i></div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div>

                        </div>
                        </div>
						
						
						
						
						
						<?php } ?>
					</div>
		  		</div>
				
				
				<div class="control-group">
		    		<label class="control-label" for="ug_id"><?php echo JText::_('COM_JBLANCE_USER_GROUP'); ?>:</label>
					<div class="controls">
						<?php
						$attribs = 'class="input-large required" size="1"';
		          		$group = $select->getSelectUserGroups('ug_id', $this->row->ug_id, 'COM_JBLANCE_SELECT_USERGROUP', $attribs, '');
				    	echo  $group; ?>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('Duration of plan.This field is automatically populated as per the response returned from braintree.')); ?>
		    		<label class="control-label hasTooltip" for="days" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_DURATION'); ?>:</label>
					<div class="controls controls-row">
						<input type="text" class="input-mini " disabled="disabled" name="days" id="days" value="<?php echo $this->row->days; ?>" />
						<?php $dur = $model->getSelectDuration('days_type', $this->row->days_type, 0, '');
					    echo  $dur; ?>
					</div>
		  		</div>
				
				<!--<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_LIMIT_TIMES_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="time_limit" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_LIMIT'); ?>:</label>
					<div class="controls">
						<div class="input-append">
							<input type="text" class="input-mini required" id="time_limit" name="time_limit" value="<?php echo $this->row->time_limit; ?>" />
							<span class="add-on"><?php echo JText::_('COM_JBLANCE_TIMES'); ?></span>
						</div>
					</div>
		  		</div>	-->  		
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('Price of plan.This field is automatically populated as per the response returned from braintree.')); ?>
		    		<label class="control-label hasTooltip" for="price" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_PRICE'); ?>:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small " id="price" disabled="disabled" name="price" value="<?php echo $this->row->price; ?>" />
						</div>

					</div>
		  		</div>
				<!--<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_NEXT_TIME_DISCOUNT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="discount" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_NEXT_TIME_DISCOUNT'); ?>:</label>
					<div class="controls">
						<div class="input-append">
							<input type="text" class="input-mini required" name="discount" id="discount" value="<?php echo $this->row->discount; ?>" />
							<span class="add-on">%</span>
						</div>
					</div>
		  		</div>-->
				<div class="control-group">
		    		<label class="control-label" for="published"><?php echo JText::_('JPUBLISHED'); ?>:</label>
					<div class="controls">
						<?php $published = $select->YesNoBool('published', $this->row->published);
						echo  $published; ?>
					</div>
		  		</div>
				<div class="control-group">
		    		<label class="control-label" for="invisible"><?php echo JText::_('COM_JBLANCE_INVISIBLE'); ?>:</label>
					<div class="controls">
						<?php $invisible = $select->YesNoBool('invisible', $this->row->invisible);
						echo  $invisible; ?>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_ALERT_ADMIN_ON_SUBSCRIBE_EVENT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="alert_admin" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_ALERT_ADMIN_ON_SUBSCRIBE_EVENT'); ?>:</label>
					<div class="controls">
						<?php $alert_admin = $select->YesNoBool('alert_admin', $this->row->alert_admin);
						echo  $alert_admin; ?>
					</div>
		  		</div>
				<!--plan heading-->
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('Heading that displays in plan listing page')); ?>
		    		<label class="control-label hasTooltip" for="name" title="<?php echo $tip; ?>"><?php echo JText::_('Heading'); ?>:</label>
					<div class="controls">
						<input type="text" class="input-xlarge input-large-text required" name="params[heading]" id="name" value="<?php echo $this->params['heading']; ?>" />
					</div>
		  		</div>
				
				<!--plan short desc-->
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('Short description about this plan')); ?>
		    		<label class="control-label hasTooltip" for="name" title="<?php echo $tip; ?>"><?php echo JText::_('Small description'); ?>:</label>
					<div class="controls">
						<textarea name="params[short_desc]"><?php echo $this->params['short_desc']; ?></textarea>
					</div>
		  		</div>
			</fieldset>
		    <fieldset class="form-vertical">
				<legend><?php echo JText::_( 'COM_JBLANCE_DESCRIPTION' ); ?></legend>
				<div class="control-group" style="display: none;">
		    		<label class="control-label" for="description"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</label>
					<div class="controls">
						<textarea name="description" id="description" rows="3" cols="30"><?php echo $this->row->description; ?></textarea>
					</div>
		  		</div>
		  		<div class="control-group">
		    		<label class="control-label" for="finish_msg"><?php echo JText::_('COM_JBLANCE_FINAL_MESSAGE'); ?>:</label>
					<div class="controls">
						<textarea name="finish_msg" id="finish_msg" rows="3" cols="30"><?php echo $this->row->finish_msg; ?></textarea>
					</div>
		  		</div>
			</fieldset>		
		</div>
		<div class="span6">
			<fieldset>
				<legend><?php echo JText::_('COM_JBLANCE_FUND_SETTINGS'); ?></legend>
			<?php echo JHtml::_('bootstrap.startAccordion', 'credit-slider', array('active' => 'credit-general')); ?>
			
			<?php echo JHtml::_('bootstrap.addSlide', 'credit-slider', JText::_('COM_JBLANCE_GENERAL'), 'credit-general'); ?>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_BONUS_FUND_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="bonusFund" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_BONUS_FUND'); ?>:</label>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="bonusFund" id="bonusFund" value="<?php echo $this->row->bonusFund; ?>" />
						</div>
					</div>
		  		</div>			
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_PORTFOLIO_ITEMS_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="portfolioCount" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_PORTFOLIO_ITEMS'); ?>:</label>
					<div class="controls">
						<?php 
						$val = isset($this->params['portfolioCount']) ? $this->params['portfolioCount'] : 0; ?>
						<input type="text" class="input-mini required" name="params[portfolioCount]" id="portfolioCount" value="<?php echo $val; ?>" />
					</div>
		  		</div>			
			<?php echo JHtml::_('bootstrap.endSlide'); ?> <!-- end of general slide -->
			
			<!--  section for buyer type of user group -->
			<?php echo JHtml::_('bootstrap.addSlide', 'credit-slider', JText::_('COM_JBLANCE_FUND_SETTINGS_USERS_POSTING_PROJECTS'), 'fund-buyer'); ?>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeeAmtPerProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php 
						$val = isset($this->params['buyFeeAmtPerProject']) ? $this->params['buyFeeAmtPerProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeeAmtPerProject]" id="buyFeeAmtPerProject" value="<?php echo $val; ?>" />
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_PROJECT_FEE_IN_PERCENT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePercentPerProject" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_PROJECT_FEE_IN_PERCENT'); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePercentPerProject']) ? $this->params['buyFeePercentPerProject'] : 0; ?>
						<div class="input-append">
							<input type="text" class="input-small required" name="params[buyFeePercentPerProject]" id="buyFeePercentPerProject" value="<?php echo $val; ?>" />
							<span class="add-on">%</span>
						</div>
					</div>
		  		</div>	
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_CHARGE_PER_PROJECT_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyChargePerProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_CHARGE_PER_PROJECT_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyChargePerProject']) ? $this->params['buyChargePerProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyChargePerProject]" id="buyChargePerProject" value="<?php echo $val; ?>" />
						</div>
					</div>
		  		</div>
				<!-- i have to pick from here-->
			
					<fieldset style="border-bottom: 1px solid #e5e5e5; margin: 0px 0px 15px;">
                              <legend style="font-size: 14px; font-weight: bold; color: rgb(0, 136, 204);" ><?php echo JText::_('COM_JBLANCE_UPGRADE_PROJECT')?></legend>
   
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_FEATURED_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePerFeaturedProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_FEATURED_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePerFeaturedProject']) ? $this->params['buyFeePerFeaturedProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeePerFeaturedProject]" id="buyFeePerFeaturedProject" value="<?php echo $val; ?>" />
						</div>
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buyFeePerFeaturedProject" title="<?php echo JText::_('COM_JBLANCE_SELL_UPGRADE_TIP');?>"><?php echo JText::_('COM_JBLANCE_SELL_UPGRADE');?></label>
						<?php $sellFeatured = $select->YesNoBool('sellfeatured', $this->row->sellfeatured);
						         echo  $sellFeatured; ?>
						
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_URGENT_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePerUrgentProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_URGENT_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePerUrgentProject']) ? $this->params['buyFeePerUrgentProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeePerUrgentProject]" id="buyFeePerUrgentProject" value="<?php echo $val; ?>" />
						</div>
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buyFeeSellurgentProject" title="<?php echo JText::_('COM_JBLANCE_SELL_UPGRADE_TIP');?>"><?php echo JText::_('COM_JBLANCE_SELL_UPGRADE');?></label>
						<?php $sellUrgent = $select->YesNoBool('sellurgent', $this->row->sellurgent);
						         echo  $sellUrgent; ?>
						
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_PRIVATE_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePerPrivateProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_PRIVATE_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePerPrivateProject']) ? $this->params['buyFeePerPrivateProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeePerPrivateProject]" id="buyFeePerPrivateProject" value="<?php echo $val; ?>" />
						</div>
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buySellPrivateProject" title="<?php echo JText::_('COM_JBLANCE_SELL_UPGRADE_TIP');?>"><?php echo JText::_('COM_JBLANCE_SELL_UPGRADE');?></label>
						<?php $sellPrivate = $select->YesNoBool('sellprivate', $this->row->sellprivate);
						         echo  $sellPrivate; ?>
								 </div>
		  		</div>
				</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_SEALED_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePerSealedProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_SEALED_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePerSealedProject']) ? $this->params['buyFeePerSealedProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeePerSealedProject]" id="buyFeePerSealedProject" value="<?php echo $val; ?>" />
						</div>
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buyFeeSellSealedProject" title="<?php echo JText::_('COM_JBLANCE_SELL_UPGRADE_TIP');?>"><?php echo JText::_('COM_JBLANCE_SELL_UPGRADE');?></label>
						<?php $sellSealed = $select->YesNoBool('sellsealed', $this->row->sellsealed);
						         echo  $sellSealed; ?>
						
						</div>
					</div>
		  		</div>
				
				
				<!--Added new upgrade-->
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_ASSISTED_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePerAssistedProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_ASSISTED_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePerAssistedProject']) ? $this->params['buyFeePerAssistedProject'] : 0; ?>
						
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeePerAssistedProject]" id="buyFeePerAssistedProject" value="<?php echo $val; ?>" />
						</div>
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buyFeePerAssistedProject" title="<?php echo JText::_('COM_JBLANCE_SELL_UPGRADE_TIP');?>"><?php echo JText::_('COM_JBLANCE_SELL_UPGRADE');?></label>
						<?php $sellAssisted = $select->YesNoBool('sellassisted', $this->row->sellassisted);
						         echo  $sellAssisted; ?>
						
						</div>
					</div>
		  		</div>
				<!--end--> 
				
				
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_NDA_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyFeePerNDAProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_NDA_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyFeePerNDAProject']) ? $this->params['buyFeePerNDAProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[buyFeePerNDAProject]" id="buyFeePerNDAProject" value="<?php echo $val; ?>" />
						</div>
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buyFeePerNdaProject" title="<?php echo JText::_('COM_JBLANCE_SELL_UPGRADE_TIP');?>"><?php echo JText::_('COM_JBLANCE_SELL_UPGRADE');?></label>
						<?php $sellNda = $select->YesNoBool('sellnda', $this->row->sellnda);
						         echo  $sellNda; ?>
						
						</div>
					</div>
		  		</div>
				
				<div class="control-group">
					
		    		
						<div class="input-prepend">
						<label class="control-label hasTooltip" for="buyFeePerNdaProject" title="<?php echo JText::_('yes for charging project upgrades through deposited funds , No for charging project upgrades via payment gateway .This option doesn\'t work with new registrations.');?>"><?php echo JText::_('Payment mode(Deposited funds)');?></label>
						<?php $sellNda = $select->YesNoBool('paymentmode', $this->row->paymentmode);
						         echo  $sellNda; ?>
						
						</div>
					</div>
		  		</div>
				
				
				
				</fieldset>
				
				<!--to here-->
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_PROJECTS_ALLOWED_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="buyProjectCount" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_PROJECTS_ALLOWED'); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['buyProjectCount']) ? $this->params['buyProjectCount'] : 0; ?>
						<input type="text" class="input-small required" name="params[buyProjectCount]" id="buyProjectCount" value="<?php echo $val; ?>" />
					</div>
		  		</div>
				<!-- Bipin Thakur's custom plan benefits go here-->
				
				<?php echo JHtml::_('bootstrap.startAccordion', 'slide-group-id'); ?>
				
				<?php echo JHtml::_('bootstrap.addSlide', 'slide-group-id', JText::_($this->row->name.' Plan benefits(Freelancer)'), 'slide1_id'); ?> 
               
			   <?php echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-J31-Group', array('active' => 'tab1_j31_id'));?> 
			   
			   
			   <?php
               $i=1;			   
			   foreach($parents as $valueP)
               {?>
			 <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tab'.$i.'_j31_id', JText::_($valueP->title)); 
			 foreach($parentchild[$valueP->id] as $value)
			 {
			 $valAccess=$this->benefitAccess($value->id);
			 $tipTitle = JHtml::tooltipText($value->ben_desc);  
			 $permEnable='params['.$value->lft.'-'.$value->ben_type.'-'.$valueP->id.'-'.$value->id.']';
			 $enableBen=$select->YesNoBool($permEnable, $valAccess);
			 $displayableCont=$valAccess;
			 $colour=$value->published==0?"#F9D1CF":"#F5F7DD";
			 $unpb=$value->published==0?"This item is not visible in the frontend":"";
			 ?>
			 <fieldset style="border: 1px solid #E5E5E5;padding: 15px;border-radius: 6px;background-color: <?php echo $colour; ?>; margin: 0 0 11px 0; ">
			 <div style="margin: 0  0 8px 0;" class="control"><span  class="hasTooltip" style="color: #08C;font-weight: bold; cursor: pointer;" title="<?php echo $tipTitle; ?>"><?php echo $value->title; ?></span></div>
			 
			<?php if($value->ben_type=="custom"){ ?>
			 <div class="control">
			<label  class="control-label hasTooltip" style="color:#08C;font-weight:bold;" for="buyFeePerAssistedProject" title="<?php echo JText::_('Enter the content that will be displayed in the front end listing replacing checkmark(✔) symbol for this field.');?>"><?php echo JText::_('Enter content:');?></label>
						
			<input  type="text" name="params[<?php echo $value->lft.'-'.$value->ben_type.'-'.$valueP->id.'-'.$value->id;?>]" value="<?php echo $displayableCont;  ?>" />
						
			</div>
			<?php }else{ ?>
			<div class="control">
			<label  class="control-label hasTooltip" style="color:#08C;font-weight:bold;" for="buyFeePerAssistedProject" title="<?php echo JText::_('Enable/Disable this benefit for '. $this->row->name.' plan');?>"><?php echo JText::_('Enabled');?></label>
						
			<?php   echo  $enableBen; ?>
						
			</div>
			<?php } ?>
			<div style="font-size: 11px;color: #BD362F;float: right;font-weight: bold;"><?php echo $unpb; ?></div>
			</fieldset>
			
			  
             <?php } echo JHtml::_('bootstrap.endTab');?>
			   <?php $i++;}			   
			   ?>
				
				
				
				
				<?php echo JHtml::_('bootstrap.endTabSet');?>
				
			    <?php echo JHtml::_('bootstrap.endSlide'); ?>
				<?php echo JHtml::_('bootstrap.endAccordion'); ?>
				<!--upto this-->
			<?php echo JHtml::_('bootstrap.endSlide'); ?> <!-- end of fund-buyer slide -->
			
			<!--  section for freelancer type of user group -->
			<?php echo JHtml::_('bootstrap.addSlide', 'credit-slider', JText::_('COM_JBLANCE_FUND_SETTINGS_USERS_SEEKING_PROJECTS'), 'fund-freelancer'); ?>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_FREELANCER_PROJECT_FEE_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="flFeeAmtPerProject" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_PROJECT_FEE_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['flFeeAmtPerProject']) ? $this->params['flFeeAmtPerProject'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[flFeeAmtPerProject]" id="flFeeAmtPerProject" value="<?php echo $val; ?>" />
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_FREELANCER_PROJECT_FEE_IN_PERCENT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="flFeePercentPerProject" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_PROJECT_FEE_IN_PERCENT'); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['flFeePercentPerProject']) ? $this->params['flFeePercentPerProject'] : 0; ?>
						<div class="input-append">
							<input type="text" class="input-small required" name="params[flFeePercentPerProject]" id="flFeePercentPerProject" value="<?php echo $val; ?>" />
							<span class="add-on">%</span>
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_CHARGE_PER_BID_IN_AMT_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="flChargePerBid" title="<?php echo $tip; ?>"><?php echo JText::sprintf('COM_JBLANCE_CHARGE_PER_BID_IN_AMT', $currencysym); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['flChargePerBid']) ? $this->params['flChargePerBid'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[flChargePerBid]" id="flChargePerBid" value="<?php echo $val; ?>" />
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_BIDS_ALLOWED_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="flBidCount" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_BIDS_ALLOWED'); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['flBidCount']) ? $this->params['flBidCount'] : 0; ?>
						<input type="text" class="input-mini required" name="params[flBidCount]" id="flBidCount" value="<?php echo $val; ?>" />
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_CHARGE_PER_SERVICE_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="flChargePerService" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_CHARGE_PER_SERVICE'); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['flChargePerService']) ? $this->params['flChargePerService'] : 0; ?>
						<div class="input-prepend">
							<span class="add-on"><?php echo $currencysym; ?></span>
							<input type="text" class="input-small required" name="params[flChargePerService]" id="flChargePerService" value="<?php echo $val; ?>" />
						</div>
					</div>
		  		</div>
				<div class="control-group">
					<?php $tip = JHtml::tooltipText(JText::_('COM_JBLANCE_SERVICE_FEE_EXAMPLE')); ?>
		    		<label class="control-label hasTooltip" for="flFeePercentPerProject" title="<?php echo $tip; ?>"><?php echo JText::_('COM_JBLANCE_SERVICE_FEE'); ?>:</label>
					<div class="controls">
						<?php $val = isset($this->params['flFeePercentPerService']) ? $this->params['flFeePercentPerService'] : 0; ?>
						<div class="input-append">
							<input type="text" class="input-small required" name="params[flFeePercentPerService]" id="flFeePercentPerService" value="<?php echo $val; ?>" />
							<span class="add-on">%</span>
						</div>
					</div>
		  		</div>
			<?php echo JHtml::_('bootstrap.endSlide'); ?> <!-- end of fund-freelancer slide -->
			
			<?php echo JHtml::_('bootstrap.endAccordion'); ?>
			</fieldset>		
		</div>
	</div>
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>