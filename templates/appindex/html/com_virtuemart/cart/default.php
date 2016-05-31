<?php
   /**
    *
    * Layout for the shopping cart
    *
    * @package    VirtueMart
    * @subpackage Cart
    * @author Max Milbers
    *
    * @link http://www.virtuemart.net
    * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
    * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
    * VirtueMart is free software. This version may have been modified pursuant
    * to the GNU General Public License, and as distributed it includes or
    * is derivative of works licensed under the GNU General Public License or
    * other free or open source software licenses.
    * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
    */
   // Check to ensure this file is included in Joomla!
   defined('_JEXEC') or die('Restricted access');
   '<input type="hidden" id="looged_val" value="' . $this->isloggedin . '">';
   JHTML::_('behavior.modal', 'a.modal');
   JHtml::_('behavior.formvalidation');
   vmJsApi::addJScript('vm.STisBT', "
   	jQuery(document).ready(function($) {
   
   		if ( $('#STsameAsBTjs').is(':checked') ) {
   			$('#output-shipto-display').hide();
   		} else {
   			$('#output-shipto-display').show();
   		}
   		$('#STsameAsBTjs').click(function(event) {
   			if($(this).is(':checked')){
   				$('#STsameAsBT').val('1') ;
   				$('#output-shipto-display').hide();
   			} else {
   				$('#STsameAsBT').val('0') ;
   				$('#output-shipto-display').show();
   			}
   			var form = jQuery('#checkoutFormSubmit');
   			document.checkoutForm.submit();
   		});
   	});
   ");
   
   vmJsApi::addJScript('vm.checkoutFormSubmit', '
   	jQuery(document).ready(function($) {
   		jQuery(this).vm2front("stopVmLoading");
   		jQuery("#checkoutFormSubmit").bind("click dblclick", function(e){
   			jQuery(this).vm2front("startVmLoading");
   			e.preventDefault();
   			jQuery(this).attr("disabled", "true");
   			jQuery(this).removeClass( "vm-button-correct" );
   			jQuery(this).addClass( "vm-button" );
   			jQuery(this).fadeIn( 400 );
   			var name = jQuery(this).attr("name");
   			$("#checkoutForm").append("<input name=\""+name+"\" value=\"1\" type=\"hidden\">");
   			$("#checkoutForm").submit();
   		});
   	});
   ');
   
   //if ($this->isloggedin == 0) {
   //    
   //    vmJsApi::addJScript('vm.checkoutFormSubmit', '
   //	jQuery(document).ready(function($) {
   //		jQuery("#myModal").modal();
   //	});
   //');
   //}
   $this->addCheckRequiredJs();
   ?>
<div class="mtb-40">
   <div class="cart-view">
      <div class="vm-cart-header-container">
         <div class="width50 floatleft vm-cart-header">
            <h1><?php echo vmText::_('COM_VIRTUEMART_CART_TITLE'); ?></h1>
            <div class="payments-signin-button" ></div>
         </div>
         <?php
            if (VmConfig::get('oncheckout_show_steps', 1) && $this->checkout_task === 'confirm') {
                echo '<div class="checkoutStep" id="checkoutStep4">' . vmText::_('COM_VIRTUEMART_USER_FORM_CART_STEP4') . '</div>';
            }
            ?>
         <div class="width50 floatleft right vm-continue-shopping">
            <?php
               // Continue Shopping Button
               if (!empty($this->continue_link_html)) {
                   echo $this->continue_link_html;
               }
               ?>
         </div>
         <div class="clear"></div>
      </div>
      <?php
         // echo shopFunctionsF::getLoginForm($this->cart, FALSE);
         // This displays the form to change the current shopper
         if ($this->allowChangeShopper) {
             echo $this->loadTemplate('shopperform');
         }
         
         
         $taskRoute = '';
         ?>
      <?php //echo shopFunctionsF::getLoginForm ($this->cart, FALSE);   ?>
      <form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
         <?php
            if (VmConfig::get('multixcart') == 'byselection') {
                if (!class_exists('ShopFunctions'))
                    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
                echo shopFunctions::renderVendorFullVendorList($this->cart->vendorId);
                ?>
         <input type="submit" name="updatecart" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="button"  style="margin-left: 10px;"/>
         <?php
            }
            // echo $this->loadTemplate('address');
            // This displays the pricelist MUST be done with tables, because it is also used for the emails
            echo $this->loadTemplate('pricelist');
            
            if (!empty($this->checkoutAdvertise)) {
                ?>
         <div id="checkout-advertise-box">
            <?php
               foreach ($this->checkoutAdvertise as $checkoutAdvertise) {
                   ?>
            <div class="checkout-advertise"> <?php echo $checkoutAdvertise; ?> </div>
            <?php
               }
               ?>
         </div>
         <?php
            }
            
            echo $this->loadTemplate('cartfields');
            ?>
         <div class="checkout-button-top">
            <?php
               echo $this->checkout_link_html;
               ?>
         </div>
         <?php // Continue and Checkout Button END   ?>
         <input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
         <input type='hidden' name='task' value='updatecart'/>
         <input type='hidden' name='option' value='com_virtuemart'/>
         <input type='hidden' name='view' value='cart'/>
      </form>
   </div>
   <!-- Modal -->
   <div  class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" style="min-height: 300px;">
               <div id="login_form_">
                  <form class="form-validate form-horizontal" method="post" action="/meadows-php/index.php?option=com_users&amp;task=user.login&amp;Itemid=206" id="login-appmeadows">
                     <fieldset class="col-md-12">
                        <div class="form-group row">
                           <label class="required" for="username" id="username-lbl"> Username<span class="star">&nbsp;*</span></label>
                           <input type="text" aria-required="true" required="" size="25" class="validate-username required" value="" id="username" name="username">
                        </div>
                        <div class="form-group row">
                           <label class="required" for="password" id="password-lbl"> Password<span class="star">&nbsp;*</span></label>
                           <input type="password" aria-required="true" required="" maxlength="99" size="25" class="validate-password required" value="" id="password" name="password">
                        </div>
                        <div class="form-group">
                           <div class="col-md-6 row">
                              <input type="checkbox" value="yes" class="inputbox" name="remember" id="remember">
                              <label>Remember me</label>
                           </div>
                           <div class="col-md-7 row text-right"><a href="/meadows-php/index.php?option=com_users&amp;view=reset&amp;Itemid=206"> Forgot your password?</a></div>
                        </div>
                        <div class="form-group mb-0">
                           <div class="">
                              <button class="login_sumbmit" type="submit"> Log in </button>
                           </div>
                        </div>
                        <input type="hidden" value="aW5kZXgucGhwP29wdGlvbj1jb21fdXNlcnMmdmlldz1wcm9maWxl" name="return">
                        <input type="hidden" value="1" name="96aadd54a150910252a5e8cc4a8be654">
                     </fieldset>
                  </form>
               </div>
               <?php //echo shopFunctionsF::getLoginForm($this->cart, FALSE); ?>
            </div>
            <?php /*?>      
            <div class="modal-footer">
               <button id="login_form_id" type="button" class="btn btn-primary">Login Form</button>
               <button id="registration_formid" type="button" class="btn btn-primary">Registration Form</button>
            </div>
            <?php */?>
         </div>
      </div>
   </div>
</div>
<script>
   jQuery(window).load(function ($) {
       var loogged = '<?php echo $this->isloggedin ?>';
       if (loogged == 0) {
           jQuery("#myModal").modal();
       }
   });
</script>
<style>
   .modal-dialog { width:470px; }
</style>
