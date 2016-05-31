<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	19 March 2012
 * @file name	:	views/admproject/tmpl/showsubscr.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of subscribers (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.modal');
 JHtml::_('formbehavior.chosen', 'select');
 JHtml::_('behavior.multiselect');
 
 $config 		= JblanceHelper::getConfig();
 $currencysym 	= $config->currencySymbol;
 $dformat 		= $config->dateFormat;
 
 ?>
 
<form action="<?php echo JRoute::_('index.php?option=com_jblance&view=admproject&layout=showsubscr'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<div id="filter-bar" class="btn-toolbar">
		<!--<div class="filter-search btn-group pull-left">
			<input type="text" name="sinv_num" id="sinv_num" placeholder="<?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>" class="input-small" value="<?php echo htmlspecialchars($this->lists['sinv_num']);?>" />
		</div>-->
		<div class="filter-search btn-group pull-left">
			<input type="text" name="suser_id" id="suser_id" placeholder="<?php echo JText::_('COM_JBLANCE_USERID'); ?>" class="input-small" value="<?php echo htmlspecialchars($this->lists['suser_id']);?>" />
		</div>
		<div class="filter-search btn-group pull-left">
			<input type="text" name="ssubscr_id" id="ssubscr_id" placeholder="<?php echo JText::_('COM_JBLANCE_SUBSCR_ID'); ?>" class="input-small" value="<?php echo htmlspecialchars($this->lists['ssubscr_id']);?>" />
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" onclick="this.form.submit();"><i class="icon-search"></i></button>
			<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('suser_id').value='';document.getElementById('ssubscr_id').value='';document.getElementById('sinv_num').value='';this.form.getElementById('subscr_status').value='';this.form.getElementById('subscr_plan').value='0';this.form.getElementById('ug_id').value='';this.form.submit();"><i class="icon-remove"></i></button>
		</div>
		<div class="filter-search btn-group pull-left">
			<?php echo $this->lists['ug_id']; ?>
		</div>
		<div class="filter-search btn-group pull-left">
			<?php echo $this->lists['subscr_plan']; ?>
		</div>
		<div class="filter-search btn-group pull-left">
			<?php echo $this->lists['subscr_status']; ?>
		</div>
	</div>

    <table class="table table-striped">
		<thead>
		    <tr>
			    <th width="10">
			    	<?php echo JText::_('#'); ?>
			    </th>
			    <th width="10">
			    	<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			    </th>
			    <th>
			    	<?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>
			    </th>
			    <th width="15%">
			    	<?php echo JText::_('COM_JBLANCE_SUBSCR_NAME'); ?>
			    </th>
			    <th width="10%">
			    	<?php echo JText::_('COM_JBLANCE_GATEWAY'); ?>
			    </th>
			    <th width="5%" class="nowrap center">
					<?php echo JText::_('COM_JBLANCE_DAYS_LEFT'); ?>
			    </th>
			    <th width="8%">
			    	<?php echo JText::_('COM_JBLANCE_STATUS'); ?>
			    </th>
			    <th width="10%">
			    	<?php echo JText::_('COM_JBLANCE_START'); ?>
			    </th>
			    <th width="10%">
			    	<?php echo JText::_('COM_JBLANCE_END'); ?>
			    </th>
			    <th width="5%" class="nowrap center">
			    	<?php echo JText::_('COM_JBLANCE_PRICE').' ('.$currencysym.')'; ?>
			    </th>
			    <th width="5%" class="nowrap center">
			    	<?php echo JText::_('Braintree customer'); ?>
			    </th>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'u.id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
		    </tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<div class="pagination pagination-centered clearfix">
						<div class="display-limit pull-right">
							<?php echo $this->pageNav->getLimitBox(); ?>
						</div>
						<?php echo $this->pageNav->getListFooter(); ?>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
	    <?php 
	   	for($i=0, $n=count($this->rows); $i < $n; $i++){
			$row = $this->rows[$i];
			$customer      =  JblanceHelper::getBtCustomer($row->uid,true);
			$card          =  $customer['recent_creditcard'];
			$subscription  =  $customer['recent_subscription'];
			//$transaction   =  $customer['transaction']; 
			$status        =  $subscription['status'] ;
			switch ($status) {
                       case "Expired":
					   $class="btn-danger";
                       break;
                       case "Pending":
					   $class="btn-warning";
                       break;
                       case "Past Due":
					   $class="btn-warning";
                       break;
					   case "Canceled":
					   $class="btn-danger";
					   break;
					   case "Active":
					   $class="btn-success";
					   
                       } 
			
	        $uurl = 'index.php?option=com_users&task=user.edit&id='.$row->uid;
	        $over = '';
			$link_edit	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=editsubscr&cid[]='.$row->id);
			$link_invoice	= JRoute::_('index.php?option=com_jblance&view=admproject&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=plan');
	    ?>
	        <tr>
		       	<td>
		       		<?php echo $this->pageNav->getRowOffset($i); ?>
		       	</td>
		        <td>
		       		<?php echo JHtml::_('grid.id', $i, $row->id); ?>
		        </td>
		        <td>
		        	<span class="small">[<?php echo $row->sid ?>]</span> <a href="<?php echo $link_edit; ?>"><?php echo $row->name ?></a>
		        </td>
		        <td>
		        	<span class="small">[<?php echo $row->uid ?>]</span> <a<?php echo $over ?>  href="<?php echo $uurl; ?>"><?php echo $row->uname ?></a>
		        </td>
		        <td class="nowrap center">
		        	<?php echo JblanceHelper::getGwayName($row->gateway); ?>
		        </td>
		        <td class="nowrap center">
		        	<?php echo $row->days; ?></td>
		        <td align="center">
		        	<span class="btn <?php echo $class;?>" ><?php echo $status; ?></span>
		        </td>
		        <td class="nowrap center">
		        	<?php echo $row->date_approval != "0000-00-00 00:00:00" ? JHtml::_('date', $row->date_approval, $dformat, true) : "&nbsp;"; ?>
		        </td>
		        <td class="nowrap center">
		        	<?php echo $row->date_expire != "0000-00-00 00:00:00" ? JHtml::_('date', $row->date_expire, $dformat, true) : "&nbsp;"; ?>
		        </td>
		        <td align="right">
		        	<?php echo JblanceHelper::formatCurrency($row->price, false); ?>
		        </td>
				 <td class="nowrap center">
					<!--<a rel="{handler: 'iframe', size: {x: 650, y: 450}}" href="<?php echo $link_invoice; ?>" class="modal"><?php echo $row->trans_id; ?></a>-->
					<?php  if(!empty($customer)) { ?>
					
					<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal-<?php echo $row->id; ?>">Braintree:Customer info</button>
					
					<div style="display:none;" id="myModal-<?php echo $row->id; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                    <img style="float:left;  width: 128px;" src="<?php echo JUri::root();?>images/braintree.png"/><button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3  style=" width: 88%; <?php echo $subscription['status']=="Active"? "color:#468847;":"color:#b94a48;"; ?>"><?php echo $row->name."(".$subscription['status'].")"; ?></h3>
                    </div>
                    <div class="modal-body">
					<div class="alert alert-success">
  <strong>The information below is based on a runtime response generated by braintree.This is the actual subscription info. of <?php echo $row->uname; ?>. It may differ from the data saved on the site.</strong> 
</div>
					<div><h4>Customer Info</h4></div>
                   <table class="table-striped table-hover bt-table bt-first" >
					<tr><td><b>Customer Id:</b></td><td class="td2"><?php echo $customer['id']; ?></td></tr>
					<tr><td><b>First Name</b></td><td class="td2"><?php echo $customer['firstName']; ?></td></tr>
					<tr><td><b>Email</b></td><td class="td2"><?php echo $customer['email']; ?></td></tr>
					<tr><td><b>Created At</b></td><td class="td2"><?php echo $customer['createdAt']; ?></td></tr>
					<tr><td><b>Updated At</b></td><td class="td2"><?php echo $customer['updatedAt']; ?></td></tr>
					</table>
					<div><h4><b>Current Subscription</b></h4></div>
					<table class="table-striped table-hover bt-table" >
					<tr><td><b>Balance</b></td><td><?php echo $subscription['balance']; ?></td></tr>
					<tr><td><b>Billing DayOf Month</b></td><td><?php echo $subscription['billingDayOfMonth']; ?></td></tr>
					<tr><td><b>Billing Period EndDate</b></td><td><?php echo $subscription['billingPeriodEndDate']; ?></td></tr>
					<tr><td><b>Billing Period Start Date</b></td><td><?php echo $subscription['billingPeriodStartDate']; ?></td></tr>
					<tr><td><b>Created At</b></td><td><?php echo $subscription['createdAt']; ?></td></tr>
					<tr><td><b>Updated At</b></td><td><?php echo $subscription['updatedAt']; ?></td></tr>
					<tr><td><b>Current Billing Cycle</b></td><td><?php echo $subscription['currentBillingCycle']; ?></td></tr>
					<tr><td><b>Days Past Due</b></td><td><?php echo $subscription['daysPastDue']; ?></td></tr>
					<tr><td><b>Failure Count</b></td><td><?php echo $subscription['failureCount']; ?></td></tr>
					<tr><td><b>First Billing Date</b></td><td><?php echo $subscription['firstBillingDate']; ?></td></tr>
					<tr><td><b>Id</b></td><td><?php echo $subscription['id']; ?></td></tr>
					<tr><td><b>Merchant Account Id</b></td><td><?php echo $subscription['merchantAccountId']; ?></td></tr>
					<tr><td><b>Never Expires</b></td><td><?php echo $subscription['neverExpires']; ?></td></tr>
					<tr><td><b>Next Bill Amount</b></td><td><?php echo $subscription['nextBillAmount']; ?></td></tr>
					<tr><td><b>Next Billing Period Amount</b></td><td><?php echo $subscription['nextBillingPeriodAmount']; ?></td></tr>
					<tr><td><b>Next Billing Date</b></td><td><?php echo $subscription['nextBillingDate']; ?></td></tr>
					<tr><td><b>Number Of Billing Cycles</b></td><td><?php echo $subscription['numberOfBillingCycles']; ?></td></tr>
					<tr><td><b>Paid Through Date</b></td><td><?php echo $subscription['paidThroughDate']; ?></td></tr>
					<tr><td><b>Payment Method Token</b></td><td><?php echo $subscription['paymentMethodToken']; ?></td></tr>
					<tr><td><b>Plan Id</b></td><td><?php echo $subscription['planId']; ?></td></tr>
					<tr><td><b>Price</b></td><td><?php echo $subscription['price']; ?></td></tr>
					<tr><td><b>Status</b></td><td><?php echo $subscription['status']; ?></td></tr>
					<tr><td><b>Trial Duration</b></td><td><?php echo $subscription['trialDuration']; ?></td></tr>
					<tr><td><b>trialDurationUnit</b></td><td><?php echo $subscription['trialDurationUnit']; ?></td></tr>
					<tr><td><b>Trial Period</b></td><td><?php echo $subscription['trialPeriod']; ?></td></tr>
					</table>
					<div><h4><b>Credit card Info</b></h4></div>
				    <table class="table-striped table-hover bt-table" >
					<tr><td><b>Bin</b></td><td class="td2"><?php echo $card['bin']; ?></td></tr>
					<tr><td><b>Expiration Month</b></td><td class="td2"><?php echo $card['expirationMonth']; ?></td></tr>
					<tr><td><b>Expiration Year</b></td><td class="td2"><?php echo $card['expirationYear']; ?></td></tr>
					<tr><td><b>Last4</b></td><td class="td2"><?php echo $card['last4']; ?></td></tr>
					<tr><td><b>Card Type</b></td><td class="td2"><?php echo $card['cardType']; ?><img src="<?php echo $card['imageUrl']; ?>"/></td></tr>
					<tr><td><b>Card Holder Name</b></td><td class="td2"><?php echo $card['cardholderName']; ?></td></tr>
					<tr><td><b>Commercial</td><td class="td2"><?php echo $card['commercial']; ?></td></tr>
					<tr><td><b>Country Of Issuance</b></td><td class="td2"><?php echo $card['countryOfIssuance']; ?></td></tr>
					<tr><td><b>Customer Id</b></td><td class="td2"><?php echo $card['customerId']; ?></td></tr>
					<tr><td><b>Customer Location</b></td><td class="td2"><?php echo $card['customerLocation']; ?></td></tr>
					<tr><td><b>Debit</b></td><td class="td2"><?php echo $card['debit']; ?></td></tr>
					<tr><td><b>Default</b></td><td class="td2"><?php echo $card['default']; ?></td></tr>
					<tr><td><b>Durbin Regulated</b></td><td class="td2"><?php echo $card['durbinRegulated']; ?></td></tr>
					<tr><td><b>Expired</b></td><td class="td2"><?php echo $card['expired']; ?></td></tr>
					<tr><td><b>Healthcare</b></td><td class="td2"><?php echo $card['healthcare']; ?></td></tr>
					<tr><td><b>Issuing Bank</b></td><td class="td2"><?php echo $card['issuingBank']; ?></td></tr>
					<tr><td><b>Payroll</b></td><td class="td2"><?php echo $card['payroll']; ?></td></tr>
					<tr><td><b>Prepaid</b></td><td class="td2"><?php echo $card['prepaid']; ?></td></tr>
					</table>
					<!--<div><h4><b>Last Transaction</b></h4></div>
					<table class="table-striped table-hover bt-table">
					<tr><td><b>Id</b></td><td class="td2"><?php echo $transaction['id']; ?></td></tr>
					<tr><td><b>Status</b></td><td class="td2"><?php echo $transaction['status']; ?></td></tr>
					<tr><td><b>Type</b></td><td class="td2"><?php echo $transaction['type']; ?></td></tr>
					<tr><td><b>Amount</b></td><td class="td2"><?php echo $transaction['amount']; ?></td></tr>
					<tr><td><b>Created At</b></td><td class="td2"><?php echo $transaction['createdAt']; ?></td></tr>
					</table>-->
					
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                    </div>
                    </div>
					<?php } else { ?>
					
					<button type="button" class="btn-danger btn-lg btn" data-toggle="modal" data-target="#myModalErr-<?php echo $row->id; ?>">Braintree:Customer error</button>
					
					<div style="display:none;" id="myModalErr-<?php echo $row->id; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
					<img style="float:left;  width: 128px;" src="<?php echo JUri::root();?>images/braintree.png"/><button type="button" class="close" data-dismiss="modal">&times;</button>
					<div><h4>Customer Not Found.</h4></div>
					</div>
					<div class="modal-body">
				<div class="alert alert-danger">
                <strong>Customer <?php echo $row->uname; ?> isnt available on braintree.<br>You can ignore this message if the subscription plan is just a tagged plan(Company(This plan should never be deleted),Regular etc.)<br>All other customers must be synced with braintree.</strong> 
                </div>	
					</div>
					<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
					</div>
					</div>
					</div>
					
					<?php } ?>
				</td>
			
				 <td class="nowrap center">
					<?php echo $row->id; ?>
				</td>
	        </tr>
	    <?php
	    }
	    ?>
		</tbody>	
    </table>

	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="admproject" />
	<input type="hidden" name="layout" value="showsubscr" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>
<script type="text/javascript">
function showAlert(uname)
{
alert(uname+ "doesnt exist on braintree.");
return false;
}

</script>
