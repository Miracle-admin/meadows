<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	23 March 2012
 * @file name	:	views/project/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Post / Edit project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $user=JFactory::getUser();
?>
<div class="mtb-40">



<form id="editprojectcustom" action="" method="post" enctype="multipart/form-data">
      <div class=" sign_up_wrap">
         <div class="container">
            <div class="row">
               <h2 class="jbl_h3title mb-10">Alerts</h2>
			   <div class="pricing_check_wrap"><span class="check_btn">
                  <input  name="notifyBidNewAcceptDeny" type="checkbox" <?php echo ($this->rows[0]->notifyBidNewAcceptDeny) == 1 ? 'checked=true' : ''; ?> value="1">
                  <label for="Check1">New Proposals for your Projects</label>
                  </span>                      
                </div>
				
				<div class="pricing_check_wrap"><span class="check_btn">
                  <input  name="notifyDeveloperRecommendation" type="checkbox" <?php echo ($this->rows[0]->notifyDeveloperRecommendation) == 1 ? 'checked=true':''; ?> value="1">
                  <label for="Check2">App Meadows Developer Recommendations</label>
                  </span>                      
                </div>
				
				<div class="pricing_check_wrap"><span class="check_btn">
                  <input  name="notifyNewMessage" type="checkbox" <?php echo ($this->rows[0]->notifyNewMessage) == 1 ? 'checked=true':'' ?>" value="1">
                  <label for="Check3">Project Comments Received</label>
                  </span>                      
                </div>
				
				<div class="pricing_check_wrap"><span class="check_btn">
                  <input  name="notifyPaymentTransaction" type="checkbox" <?php echo ($this->rows[0]->notifyPaymentTransaction) == 1 ? 'checked=true':'' ?>" value="1">
                  <label for="Check3">Payment Transactions</label>
                  </span>                      
                </div>
            </div>
         </div>
      </div>
      <!--banner--section--close-->
    <br>

		
		<input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
      <input type="hidden" value="com_jblance" name="option">
      <input type="hidden" value="project.savenotification" name="task">	  
	  <button type="submit" class="login_sumbmit">Save</button>
	  <?php echo JHtml::_('form.token'); ?>
	  </form>
      
      
    </div>  
	<script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/jquery.validate.js';?>"></script>
<script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/addmethods.js';?>"></script>
<script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/validate.js';?>"></script>

