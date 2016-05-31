<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

 // no direct access
 $customer = $this->customer;
 $card = $this->card;
 $currDate = new DateTime('now');
 $subscriptions = $this->subscriptions;
 $currentSub = $subscriptions[0];
 $daysRemaining = $currDate->diff($currentSub->nextBillingDate);
 $in=$daysRemaining->format('%R%a days');
 $numDays =intval($in);
 $DayRem  = $numDays<=0?"<span class='bg-danger'>0</span>":"<span class='bg-success'>".$in."</span>";
 $planInfo = JblanceHelper::getBraintreePlan($currentSub->planId);
 JHtml::script(Juri::base() . '/media/system/js/bootbox.js');
 $year =date("Y");

?>

<div class="">
  <div class="">
    <div class="history-payment-wrapper row">
      <div class="col-md-4">
        <h3>Customer Info</h3>
        <div class="payment_method">
          <div class="">
            <div class="cust_info sub-column">
              <table class="">
                <tbody>
                  <tr>
                    <td><?php  echo JblanceHelper::getThumbnail($customer['id']); ?></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td><b>ID:</b></td>
                    <td><?php echo $customer['id']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Username:</b></td>
                    <td><?php echo $customer['firstName'];?></td>
                  </tr>
                  <tr>
                    <td><b>Email:</b></td>
                    <td><?php echo $customer['email'];?></td>
                  </tr>
                  <tr>
                    <td><b>Created On:</b></td>
                    <td><?php echo $customer['createdAt'];?></td>
                  </tr>
                  <tr>
                    <td><b>Updated On:</b></td>
                    <td><?php echo $customer['updatedAt'];?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <h3>Subscription Info</h3>
        <div class="payment_method ">
          <div class="">
            <div class="plan_info sub-column">
              <table class="">
                <tbody>
                  <tr>
                    <td><b>Plan Name:</b></td>
                    <td><?php echo $planInfo['name']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Plan Status:</b></td>
                    <td><?php echo $currentSub->status=="Active"?"<span class='btn btn-success'>".$currentSub->status."</span>":"<span class='btn btn-danger'>".$currentSub->status."</span>"; ?></td>
                  </tr>
                  <?php $inoperative=$currentSub->status!="Active"?"inoperative":""; ?>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Billing Frequency:</b></td>
                    <td>Every <?php echo $planInfo['billingFrequency']; ?> month(s)</td>
                  </tr>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Billing Period start date:</b></td>
                    <td><?php echo $currentSub->billingPeriodStartDate->format('j F Y h:i:s A'); ?></td>
                  </tr>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Billing period end date:</b></td>
                    <td><?php echo $currentSub->billingPeriodEndDate->format('j F Y h:i:s A'); ?></td>
                  </tr>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Next Billing Date:</b></td>
                    <td><?php echo $currentSub->nextBillingDate->format('j F Y h:i:s A'); ?></td>
                  </tr>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Next billing ammount:</b></td>
                    <td>$<?php echo $currentSub->nextBillingPeriodAmount; ?></td>
                  </tr>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Active upto:</b></td>
                    <td><?php echo $currentSub->nextBillingDate->format('j F Y h:i:s A'); ?></td>
                  </tr>
                  <tr class="<?php echo $inoperative; ?>">
                    <td><b>Days remaining:</b></td>
                    <td class="day-rem"><?php echo $DayRem; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center nopaddingleft"><a class="btn btn-success" target="_blank" href="<?php JUri::root(); ?>index.php?option=com_jblance&view=membership&layout=plans&Itemid=344">Upgrade</a>&nbsp;
                      <?php if($currentSub->status=="Active"){ ?>
                      <a id="dow_sub" class="btn btn-danger" target="_blank" href="<?php JUri::root(); ?>index.php?option=com_alphauserpoints&view=paymentmanagement&Itemid=448">Downgrade</a></td>
                    <?php } ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <h3>Payment method</h3>
        <div class="payment_method ">
          <div class=" ">
            <div class="plan_info sub-column">
              <table class="">
                <tbody>
                  <tr>
                    <td><b>Type:</b></td>
                    <td><?php echo $card['cardType']; ?><img style="width: 40px;margin:0 0 0 22px;" src="<?php echo $card['imageUrl']; ?>"></td>
                  </tr>
                  <tr>
                    <td><b>Expiration:</b></td>
                    <td><?php echo $card['expirationDate']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Masked Number:</b></td>
                    <td><?php echo $card['maskedNumber']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Last four:</b></td>
                    <td><?php echo $card['last4']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Customer Location:</b></td>
                    <td><?php echo $card['customerLocation']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Bank Name:</b></td>
                    <td><?php echo $card['issuingBank']; ?></td>
                  </tr>
                  <tr>
                    <td><b>Expired:</b></td>
                    <td><?php echo $card['expired']; ?></td>
                  <tr>
                    <td><b>Gateway:</b></td>
                    <td><img style="width:90px;" src="<?php echo JUri::root(); ?>/images/bt-transp.png"/></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center nopaddingleft"><button class="btn btn-success" data-toggle="modal" data-target="#card_update" >Add new card</button></td>
                  </tr>
                </tbody>
              </table>
              <!--addnew card-->
              <div style="display:none;" id="card_update" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header"> <strong>Enter your Credit/Debit card details</strong> </div>
                    <div class="modal-body">
                    <form class="form-horizontal" id="credit-form" method="post" action="index.php" autocomplete="off">
                      
                      <!--card number -->
                      <div class="form-group">
                        <label class="col-md-4 control-label required" for="cdnum">Card Number</label>
                        <div class="col-md-6">
                          <input  id="cdnum" data-braintree-name="number"   placeholder="xxxxxxxxxxxxxxxx" class="form-control input-md" type="text" data-validation="creditcard,required" data-validation-error-msg="You did not enter valid Credit/Debit card number." data-braintree-name="number" data-validation-optional="false">
                          <div class="tip">{tip Enter your card number. } <i class="fa fa-info-circle" aria-hidden="true"></i> {/tip}</div>
                        </div>
                      </div>
                      
                      <!--expiration month-->
                      
                      <div class="form-group">
                        <label class="col-md-4 control-label required" for="expirationMonth">Expiration Month</label>
                        <div class="col-md-6">
                          <select data-braintree-name="expiration_month" id="expirationMonth"  class="form-control expdt" data-validation="date" data-validation-format="mm" data-validation-error-msg="You have not given a correct month.">
                            <option value="">Select Month</option>
                            <option value="01">Jan</option>
                            <option value="02">Feb</option>
                            <option value="03">Mar</option>
                            <option value="04">Apr</option>
                            <option value="05">May</option>
                            <option value="06">Jun</option>
                            <option value="07">Jul</option>
                            <option value="08">Aug</option>
                            <option value="09">Sep</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                          </select>
                          <div class="tip">{tip Enter the expiration month of your card. }<i class="fa fa-info-circle" aria-hidden="true"></i> {/tip}</div>
                        </div>
                      </div>
                      <!--month--> 
                      
                      <!--expiration year-->
                      <div class="form-group">
                        <label class="col-md-4 control-label required" for="exp_year">Expiration Year</label>
                        <div class="col-md-6">
                          <select data-braintree-name="expiration_year"   id="exp_year" name="exp_year" class="form-control expdt" data-validation="date" data-validation-format="yyyy" data-validation-error-msg="You have not given a correct year">
                            <option value="">Select Year</option>
                            <?php
                    for($i=$year;$i<=2050;$i++)
	                 {
	 
	                 ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php 
	                 }

	                 ?>
                          </select>
                          <div class="tip">{tip Enter the expiration year of your card. }<i class="fa fa-info-circle" aria-hidden="true"></i> {/tip}</div>
                        </div>
                      </div>
                      <!-- year --> 
                      
                      <!-- cvv -->
                      <div class="form-group">
                        <label class="col-md-4 control-label required"  for="cdcvv">Cvv number</label>
                        <div class="col-md-6">
                          <input data-validation="number,length" data-validation-length="max4" data-validation-error-msg="Please enter a valid cvv number"  data-braintree-name="cvv" id="cdnum"  placeholder="" class="form-control input-md" type="password" value="" >
                          <div class="tip">{tip <img src="<?php JUri::root() ?>images/cvvback.jpg"> }<i class="fa fa-info-circle" aria-hidden="true"></i> {/tip}</div>
                        </div>
                      </div>
                      
                      <!--cvv-->
                      
                      </div>
                      <div class="modal-footer">
                        <div id="error" style="display:none;" class="alert alert-danger"> <strong>Error: </strong><span id="err_msg"></span> </div>
                        <div id="success" style="display:none;"  class="alert alert-success"> <strong>Success: </strong><span id="succ_msg"></span> </div>
                        <img style="display:none;" id="resp_loader" src="<?php echo JUri::root(); ?>images/rolling.gif"/>
                        <button id="add_card" type="submit" class="btn btn-success">Add</button>
                        <button id="mod_close" type="button" class="btn btn-danger" >Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row-history-wrapper">
    <div class="">
      <div class="sub_history">
        <h2>Subscription History</h2>
        <ul class="sub_history_list">
          <?php foreach($subscriptions as  $subk=>$subv)
		{ 
		//status history
		$statusHistory  =  $subv->statusHistory;
		$transactions   =  $subv->transactions;
		
		?>
          <li>
            <div class="sub_name">
              <h3><?php echo  $this->getPlanName($subv->planId);?></h3>
            </div>
            <!--  Sub details-->
            <div class="subs_small_details"> <strong>Subscription Details</strong>
              <table class="">
                <tr>
                  <td><b>Subscription Id:</b></td>
                  <td><?php echo $subv->id; ?></td>
                </tr>
                <tr>
                  <td><b>Plan:</b></td>
                  <td><?php echo  $this->getPlanName($subv->planId);?></td>
                </tr>
                <tr>
                  <td><b>billingPeriodStartDate:</b></td>
                  <td><?php echo $subv->billingPeriodStartDate->format('j F Y h:i:s A'); ?></td>
                </tr>
                <tr>
                  <td><b>billingPeriodEndDate:</b></td>
                  <td><?php echo $subv->billingPeriodEndDate->format('j F Y h:i:s A'); ?></td>
                </tr>
              </table>
            </div>
            <!-- sub details -->
            
            <div class="status_history"> <strong>Status History</strong>
              <table class=" table-bordered">
                <?php foreach($statusHistory as $sk =>$sv){ 
		$statClass = $sv->status=="Active"?"bg-success":"bg-danger";
		
		?>
                <tr>
                  <td class="<?php echo $statClass; ?>"><b><?php echo $sv->status; ?></b></td>
                  <td class="<?php echo $statClass; ?>" >On: <?php echo $sv->timestamp->format('j F Y h:i:s A'); ?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
            <div class="transactions_history"> <strong>Transactions History</strong>
              <table class="">
                <?php foreach($transactions as $tk =>$tv){
        $creditCard = $tv->creditCard;
		?>
                <tr>
                  <td><b>Id: </b></td>
                  <td><?php echo $tv->id; ?></td>
                </tr>
                <tr>
                  <td><b>Amount: </b></td>
                  <td>$<?php echo $tv->amount; ?></td>
                </tr>
                <tr>
                  <td><b>currency Iso Code</b>
                  <td><?php echo $tv->currencyIsoCode; ?></td>
                </tr>
                <tr>
                  <td><b>Created At: </b></td>
                  <td><?php echo $tv->createdAt->format('j F Y h:i:s A');; ?></td>
                </tr>
                <tr>
                  <td><b>Paid Through: </b></td>
                  <td><?php echo $creditCard['cardType']; ?><img class="cred_img" src="<?php echo $creditCard['imageUrl']; ?>"/></td>
                </tr>
                <tr>
                  <td><b>Card Last4: </b></td>
                  <td><?php echo $creditCard['last4']; ?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php 
JHTML::script(Juri::base().'media/com_alphauserpoints/bt/bt.js');
JHtml::stylesheet(Juri::base(). 'media/com_jblance/jQuery-Form-Validator-master/form-validator/theme-default.min.css');
JHTML::script(Juri::base(). 'media/com_jblance/jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js');
?>
<script type="text/javascript">

jQuery(function(){

//validate
jQuery.validate({
modules : 'security,toggleDisabled',
disabledFormFilter : 'form.credits-form',
onSuccess : function(form){
window.form=form;
jQuery("#add_card,#mod_close").attr("disabled","disabled");
return false;}

});

//nonce

braintree.setup('<?php echo $this->token; ?>', 'custom', {
id: 'credit-form',
onReady:function(){},
onPaymentMethodReceived:function(obj){
jQuery("#add_card,#mod_close").attr("disabled","disabled");
jQuery("#resp_loader").show();
var nonce = obj.nonce;

if(typeof window.form != "undefined")
{
var winform = window.form;
window.form.off();
jQuery.get( '<?php echo JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&task=gettoken&view=cardmanagement");?>', function( token ) {




jQuery.ajax(
{
method: "POST",
url:'<?php echo JRoute::_(JUri::root()."index.php?option=com_alphauserpoints&task=newcard&view=cardmanagement&");?>'+token+'=1', 

data:{"nonce":obj.nonce,"j":1},
error: function(xhr, error){notify("Unknown error occured,please try again later.","error");},
success:function(data){
var resp  = JSON.parse(data);
notify(resp.message,resp.type);

}


}
)




  
});







}

}
})



jQuery("#dow_sub").on("click",function(e)
{
e.preventDefault();

bootbox.confirm(
{
message:"<b>Are you sure , you want to downgrade your geek status.You would fallback to regular geek plan.</b>",
buttons: {
   
	confirm:{
         label: "Cancel",
    
         className: "btn-success",
      
         callback: function() {
		 
		 
		 }
          },
		  
	cancel: {   
      
         label: "Downgrade",
    
         className: "btn-danger",
      
         callback: function() {}
           }
	    },
	
   callback: function(result){ 
   if(!result)
   {
   window.location="<?php echo JRoute::_(JUri::root().'index.php?option=com_alphauserpoints&controller=subscription&task=cancelSubscription');?>";
   }
   
   },
   closeButton: false
});

})
jQuery("#mod_close").on("click",function(e){

e.preventDefault();
jQuery("#error").hide();
jQuery("#success").hide();
jQuery("#resp_loader").hide();
jQuery(":input").val("");
jQuery('#card_update').modal("hide");

})


//hide notices

jQuery("#add_card").on("click",function(){
jQuery("#error,#success").hide();
})


})
function notify(msg,type)
{

if(type=="error")
{
jQuery("#err_msg").text(msg);
jQuery("#error").show();

jQuery("#add_card,#mod_close").removeAttr("disabled");
}
if(type=="success")
{

jQuery("#succ_msg").text(msg);
jQuery("#success").show();
setTimeout(function(){jQuery('#card_update').modal("hide");location.reload(); },2000);
}
jQuery("#resp_loader").hide();
}
</script> 
<!--<style>
.sub-column {
   border: 1px solid;
    border-radius: 10px;
    height: 520px;
    padding: 17px;
    width: 115%;
	box-shadow: 0 10px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
td {
    padding: 7px;
}
.sub_history {
    border: 1px solid;
    border-radius: 7px;
    box-shadow: 0 10px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    margin: 32px 0 55px;
    padding: 10px;
    width: 104%;
}
.accordion-heading {
    background: #f9f9f9 none repeat scroll 0 0;
    border: 1px solid;
 
    margin: 7px 0 0;
    padding: 5px 0;
    text-align: center;
}
.accordion-body {
    border-bottom: 1px solid;
    border-left: 1px solid;
    border-right: 1px solid;
    padding: 10px;
}
.inoperative
{
    background: #fff!important;
    opacity: 0.3;
}
.sub_history li
{
border: 1px solid;
padding: 15px;
margin: 10px 0 10px 0;
}
.subs_small_details,.status_history
{
float: left;
margin: 8px 90px 0 0;
}
.sub_name 
{
padding: 4px 0;
}
.cred_img 
{
width: 31px;
margin: 0 0 0 12px;
}
</style>--> 