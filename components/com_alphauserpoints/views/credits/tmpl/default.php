<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
$year = date("Y");
$cart = false;
$type = $this->type;
$ref = "";

?>

<div class="credit-card-wrapper">
<form class="form-horizontal" id="credits-form" method="post" action="index.php">
    <fieldset>
        
        <!-- Form Name -->


 <div class="credit-card-page"><h4>Enter your Credit/Debit card details</h4>
  <div class="payment_method mb">
<?php
if (!empty($this->products) && $type == "cart") {
    $document = JFactory::getDocument();
    $document->addScriptDeclaration('window.cartammount=' . $this->ammount);

    $cart = true;
    $totalProducts = count($this->products);
    ?>





            <div class="form-group">
                <label class="col-md-3 control-label " for="amt">Product(s)</label>  
                <div class="col-md-8 ">
                    <ul class="cart_products">
                        <li><?php echo count($this->products); ?></li>
    <?php
    $pd = 1;
    foreach ($this->products as $val) {

        $disp = $pd > 3 ? "display:none;" : "";
        ?>

                            <li style="<?php echo $disp; ?>"><div class="pthumbnail">
                                    <a href="<?php echo $val['link'] ?>"><?php echo empty($val['media']['file_url_thumb']) ? $val['media']['file_url'] : $val['media']['file_url_thumb']; ?><?php echo $val['order_item_name']; ?></a>
                                </div></li>


        <?php
        $pd++;
    }

    if ($pd > 3) {
        ?>
                            <li><a id ="view_more" href="#">View All</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>

                    <?php } ?>


        <div class="form-group">
            <label class="col-md-3 control-label required" for="amt">Ammount($)</label>  
            <div class="col-md-8">
                <input value="<?php echo $type == "cart" ? $this->ammount : ""; ?>" id="amt" name="amt" placeholder="$1000" class="form-control input-md" type="text" data-validation="number,cart_ammount" data-validation-error-msg="Invalid ammount of money." >
                <div class="tip">{tip Enter the amount to be deposited. } <i class="fa fa-info-circle"></i> {/tip}</div>
            </div>
        </div>


        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-3 control-label required" for="cdnum">Card Number</label>  
            <div class="col-md-8">
                <input value="371449635398431" id="cdnum" data-braintree-name="number"   placeholder="xxxxxxxxxxxxxxxx" class="form-control input-md" type="text" data-validation="creditcard,required" data-validation-error-msg="You did not enter valid Credit/Debit card number." data-braintree-name="number" data-validation-optional="false">
                <div class="tip">{tip Enter your card number. }  <i class="fa fa-info-circle"></i> {/tip}</div>
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
                <div class="tip">{tip Enter the expiration month of your card. } <i class="fa fa-info-circle"></i> {/tip}</div>
            </div>
        </div>

        <!-- Select Basic -->
        <div class="form-group">
            <label class="col-md-3 control-label required" for="exp_year">Expiration Year</label>
            <div class="col-md-8">
                <select data-braintree-name="expiration_year"   id="exp_year" name="exp_year" class="form-control expdt" data-validation="date" data-validation-format="yyyy" data-validation-error-msg="You have not given a correct year">
                    <option value="">Select Year</option>
<?php
for ($i = $year; $i <= 2050; $i++) {
    ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php
}
?>
                </select>
                <div class="tip">{tip Enter the expiration year of your card. } <i class="fa fa-info-circle"></i> {/tip}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label required"  for="cdcvv">Cvv number</label>  
            <div class="col-md-8">
                <input data-validation="number,length" data-validation-length="max4" data-validation-error-msg="Please enter a valid cvv number"  data-braintree-name="cvv" id="cdnum"  placeholder="" class="form-control input-md" type="password" value="1234" >
                <div class="tip">{tip <img src="<?php JUri::root() ?>images/cvvback.jpg"> } <i class="fa fa-info-circle"></i> {/tip}</div>
            </div>

        </div>
        
        
        
          <div class="form-group">
          <label class="col-md-3 control-label required"  for="cdcvv"></label>  
            <div class="col-md-8">
                <button id="" name="" class="btn btn-success">Process Transaction</button><img style="display:none;" id="loading_nonce" src="images/rolling.gif"/>

            </div>
        </div>
        
        </div>
      
        </div>
        
    </fieldset>
<?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="option" value="com_alphauserpoints">
    <input type="hidden" name="controller" value="credits">
    <input type="hidden" name="task" value="<?php echo!$cart ? "processPayment" : "processCartPayment"; ?>">

</form>

</div>

<?php
JHTML::script(Juri::base() . 'media/com_alphauserpoints/bt/bt.js');
JHtml::stylesheet(Juri::base() . 'media/com_jblance/jQuery-Form-Validator-master/form-validator/theme-default.min.css');
JHTML::script(Juri::base() . 'media/com_jblance/jQuery-Form-Validator-master/form-validator/jquery.form-validator.min.js');
?>
<script type="text/javascript">
//never modify the below script




    jQuery.formUtils.addValidator({
        name: 'cart_ammount',
        validatorFunction: function (value, $el, config, language, $form) {
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
        errorMessage: 'Insufficient ammount to proceed with this order',
        errorMessageKey: 'badEvenNumber'
    });


    jQuery.validate({
        modules: 'security,toggleDisabled',
        disabledFormFilter: 'form.credits-form',
        onSuccess: function (form) {
            window.form = form;
            jQuery("#loading_nonce").show();
            return false;
        }
    });

    braintree.setup('<?php echo $this->token; ?>', 'custom', {
        id: 'credits-form',
        onReady: function () {
        },
        onPaymentMethodReceived: function (obj) {
            var nonce = obj.nonce;
            jQuery("input[name='payment_method_nonce']").val(nonce);
            if (typeof window.form != "undefined")
            {
                var winform = window.form;
                window.form.off();
                jQuery("#loading_nonce").hide();
                window.form.submit();
            }

        }
    })
    jQuery(function () {
        jQuery("#view_more").on('click', function (e) {
            e.preventDefault();
            jQuery(".cart_products li").fadeIn(500);
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
</style>-->
