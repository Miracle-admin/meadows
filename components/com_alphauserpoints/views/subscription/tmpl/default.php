<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

 // no direct access
 $year =date("Y");
 $customer = $this->customer;
 $customerExists = count($customer)!=0?true:false;
?>

<div class="credit-card-wrapper">
  <form class="form-horizontal" id="credits-form" method="post" action="index.php">
    <fieldset>
      
      <!-- Form Name -->
      <div class="credit-card-page">
        <h4> Enter your Credit/Debit card details</h4>
        <div class="ctable-wrapper">
          <table class="ctable">
            <tr>
              <td>Plan:</td>
              <td><?php echo $this->planname; ?></td>
            </tr>
            <tr>
              <td>Price<b>:</b></td>
              <td>$<?php echo $this->ammount; ?></td>
            </tr>
            <tr>
              <td>Billing Frequency:</td>
              <td>every <?php echo $this->billingFrequency; ?>month(s)</td>
            </tr>
            <tr>
              <td>Billing Mode:</td>
              <td>Automatic {tip Your card would be automatically charged, <br> in the next billing cycle of your subscription. } <i class="fa fa-info-circle"></i>
 {/tip}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="credit-card-page two"> 
        <!-- old payment method -->
        <?php if($customerExists) {
$card = $this->customer['recent_creditcard']; 
?>
        <h4>Payment method</h4>
        <div class="ctable-wrapper">
          <table class="ctable">
            <tr>
              <td>Card Type:</td>
              <td><?php echo $card['cardType']; ?>&nbsp;&nbsp;<img style="width: 34px;" src="<?php echo $card['imageUrl'];?>"/></td>
            </tr>
            <tr>
              <td>Masked Number:</td>
              <td><?php echo $card['maskedNumber'];?></td>
            </tr>
            <tr>
              <td>Last Four:</td>
              <td><?php echo $card['last4'];?></td>
            </tr>
            <tr>
              <td>Expiration Date:</td>
              <td><?php echo $card['expirationDate']; ?></td>
            </tr>
            <tr>
              <td>Card Holder Name:</td>
              <td><?php echo $card['cardholderName'];?></td>
            </tr>
            <tr>
              <td>Country Of Issuance:</td>
              <td><?php echo $card['countryOfIssuance'];?></td>
            </tr>
            <tr>
              <td>Customer Location:</td>
              <td><?php echo $card['customerLocation'];?></td>
            </tr>
            <tr>
              <td>Debit</td>
              <td><?php echo $card['debit'];?></td>
            </tr>
            <tr>
              <td>Default Payment Method:</td>
              <td><img src="<?php echo JUri::root()."images/tick.png";?>"/></td>
            </tr>
            <tr>
              <td>Durbin Regulated:</td>
              <td><?php echo $card['durbinRegulated'];?></td>
            </tr>
            <tr>
              <td>Issuing Bank:</td>
              <td><?php echo $card['issuingBank'];?></td>
            </tr>
            <tr>
              <td>Payroll:</td>
              <td><?php echo $card['payroll'];?></td>
            </tr>
            <tr>
              <td>Prepaid:</td>
              <td><?php echo $card['prepaid'];?></td>
            </tr>

          </table>
          
          
          
          
        </div>
        <a id="add_new_pm" class="add-card-btn btn-success">Add new card</a>
      </div>
      <!--old pm-->
      
      <?php } ?>

  <?php /*?>      <label class="col-md-4 control-label required" for="amt">Ammount($)</label><?php */?>
        <div class="add-new-card-wrapper">
          <input  id="amt" name="amt" placeholder="$1000" disabled="disabled" class="form-control input-md" type="text" data-validation="number,cart_ammount" data-validation-error-msg="Invalid ammount of money." value="<?php echo $this->ammount; ?>" >
          <div class="tip">{tip Enter the amount to be deposited. } <i class="fa fa-info-circle"></i>{/tip}</div>
        </div>
        
        
        
        
        
        
        
      <div class="payment_method-wrap" style = "<?php echo $customerExists?"display:none;":"" ?>"> 
      
      
      <h4>Enter Card Information</h4>
      <div class="payment_method">
      
        <!-- Text input-->
        <div class="">
<?php /*?>          <label class="col-md-4 control-label required" for="cdnum">Card Number</label><?php */?>
          <div class="form-group">
            <input value="371449635398431" id="cdnum" data-braintree-name="number"   placeholder="xxxxxxxxxxxxxxxx" class="form-control input-md" type="text" data-validation="creditcard,required" data-validation-error-msg="You did not enter valid Credit/Debit card number." data-braintree-name="number" data-validation-optional="false">
            <div class="tip">{tip Enter your card number. } {/tip}</div>
          </div>
        </div>
        
        <!-- Select Basic -->
        <div class="form-group">
          <label class="col-md-3 control-label required" for="expirationMonth">Expiration Month</label>
          <div class="col-md-8">
            <select data-braintree-name="expiration_month" id="expirationMonth"  class="form-control expdt" data-validation="date" data-validation-format="mm" data-validation-error-msg="You have not given a correct month.">
              <option value="">Select Month</option>
              <option selected value="01">Jan</option>
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
            <div class="tip">{tip Enter the expiration month of your card. } <i class="fa fa-info-circle"></i>{/tip}</div>
          </div>
        </div>
        
        <!-- Select Basic -->
        <div class="form-group">
          <label class="col-md-3 control-label required" for="exp_year">Expiration Year</label>
          <div class="col-md-8">
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
            <div class="tip">{tip Enter the expiration year of your card. } <i class="fa fa-info-circle"></i>{/tip}</div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label required"  for="cdcvv">Cvv number</label>
          <div class="col-md-8">
            <input data-validation="number,length" data-validation-length="max4" data-validation-error-msg="Please enter a valid cvv number"  data-braintree-name="cvv" id="cdnum"  placeholder="" class="form-control input-md" type="password" value="1234" >
            <div class="tip">{tip <img src="<?php JUri::root() ?>images/cvvback.jpg"> } <i class="fa fa-info-circle"></i>{/tip}</div>
            <?php if($customerExists){  ?>
            <i>This card will be considered as your default payment method from now.</i>
            <?php } ?>
          </div>
        </div>
        
        </div>
        
      </div>
      <div class="form-group">

        <div class="col-md-8 process-btn">
          <button id="" name="" class="btn btn-success">Process Transaction</button>
          <img style="display:none;" id="loading_nonce" src="images/rolling.gif"/> </div>
      </div>
    </fieldset>
    <?php echo JHtml::_('form.token');  ?>
    <input type="hidden" name="option" value="com_alphauserpoints">
    <input type="hidden" name="controller" value="subscription">
    <input type="hidden" name="task" value="processSubscription">
    <input type="hidden" name="subid" value="<?php echo $this->subid;?>">
    <input type="hidden" name="pm" value="0" id="pm">
  </form>
</div>
<?php 
JHTML::script(Juri::base().'media/com_alphauserpoints/bt/bt.js');
JHtml::stylesheet(Juri::base(). 'media/com_jblance/jQuery-Form-Validator-master/form-validator/theme-default.min.css');
JHTML::script(Juri::base(). 'media/com_jblance/jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js');
?>
<script type="text/javascript">
//never modify the below script




jQuery.formUtils.addValidator({
  name : 'cart_ammount',
  validatorFunction : function(value, $el, config, language, $form) {
  if (typeof window.cartammount != "undefined") {
  if (parseInt(value, 10) < window.cartammount)
  {
  return false;
  }
  else
  {
 
  return true;
  }
}
else
{

return true;
}
   
  },
  errorMessage : 'Insufficient ammount to proceed with this order',
  errorMessageKey: 'badEvenNumber'
});


jQuery.validate({
modules : 'security,toggleDisabled',
disabledFormFilter : 'form.credits-form',
onSuccess : function(form){
window.form=form;
 jQuery("#loading_nonce").show();
return false;}
});

braintree.setup('<?php echo $this->token; ?>', 'custom', {
id: 'credits-form',
onReady:function(){},
onPaymentMethodReceived:function(obj){
var nonce = obj.nonce;
jQuery("input[name='payment_method_nonce']").val(nonce);
if(typeof window.form != "undefined")
{
var winform = window.form;
window.form.off();
jQuery("#loading_nonce").hide();
window.form.submit();
}

}
})
jQuery(function(){
jQuery("#view_more").on('click',function(e){
e.preventDefault();
jQuery(".cart_products li").fadeIn(500);
})
jQuery("#add_new_pm").on("click",function(e){
  
e.preventDefault();
  jQuery(".payment_method-wrap").fadeToggle(800,function(){
    var sel = jQuery("#pm");
    var currval=parseInt(sel.val());
    var fmctrl = jQuery(".form-control").not("#amt");
    if(currval==0)
    {
	jQuery("#add_new_pm").text("Use old card"); 
    
      sel.val(1);
      fmctrl.removeAttr("disabled","disabled");
    }
    else
    {
     jQuery("#add_new_pm").text("Add new card"); 
    sel.val(0);
      fmctrl.attr("disabled","disabled");
    }
  
  });
})
})
</script> 
<!--<style>
.form-horizontal {
     border: 2px solid #ccc;
    border-radius: 5px;
    margin: 150px 312px 0;
    width: 800px;
}

.tip {
    bottom: 30px;
    float: right;
    left: 80px;
    position: relative;
}
.control-label.required:after {
  content:"*";
  color:red;
}
.pthumbnail img {
    width: 99px;
}
.cart_products li
{
list-style: outside none none;
 padding: 10px 29px;
}
.cart_products
{
border: 1px solid #ccc;
border-radius: 4px;
}
.plan_info {

    margin: 0 0 17px 275px;
    width: 255px;
}
.plan_attrib {
    line-height: 23px;
    list-style: outside none none;
}
.plan_info td
{
 padding: 8px;
}
table {
    width: 383px;
}
.nn_tooltips-link.hover.isimg > img {
    padding: 0 0 1px 26px;
}
</style>--> 