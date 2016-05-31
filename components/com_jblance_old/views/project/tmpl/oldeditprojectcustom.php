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
 
 $devType=$this->devtype;
 $platform=$this->platform;
 $budget=$this->budget;
 $config 		  = JblanceHelper::getConfig();
 $currencysym 	  = $config->currencySymbol;
 $countries       = $this->countries;
 $plan            = $this->plan;
 //project upgrades
 $sellfeatured    =  $plan->sellfeatured;
 $sellurgent      =  $plan->sellurgent;
 $sellprivate     =  $plan->sellprivate;
 $sellsealed      =  $plan->sellsealed;
 $sellassisted    =  $plan->sellassisted;
 $sellnda         =  $plan->sellnda;
 //bonus
 $bonus           =  $plan->bonusFund;
 $planParams      =  json_decode($plan->params);
 //project upgrade prices
 $sellfeaturedFee    =  $planParams->buyFeePerFeaturedProject;
 $sellurgentFee      =  $planParams->buyFeePerUrgentProject;
 $sellprivateFee     =  $planParams->buyFeePerPrivateProject;
 $sellsealedFee      =  $planParams->buyFeePerSealedProject;
 $sellassistedFee    =  $planParams->buyFeePerAssistedProject;
 $sellndaFee         =  $planParams->buyFeePerNDAProject;


 
 
 ?>

<form id="editprojectcustom" action="" method="post" enctype="multipart/form-data">
      <div class="container-fluid sign_up_wrap">
         <div class="container">
            <div class="row">
               <h2>Create App Project</h2>
               <p> Here you can create your mobile app development project. Explain your project and we<br>
 will find the best mobile app developers for you to hire</p>
            </div>
         </div>
      </div>
      <!--banner--section--close-->
      <div class="container">
         <div class="row register_wrap">
            <h3> Project description</h3>
            <div class="input_sign_up_wrapper">
               <div class="input-group">
                  <input name="pj_title" type="text" class="" placeholder="Project name" aria-describedby="basic-addon1">
               </div>
               <div class="input-group">
                <textarea name="pz_desc" placeholder="Project Description" class="message_box_wrapper"></textarea>
               </div>
               <div class="upload_drag_wrapper">
             
			<div id="drop"><div class="upload_wrap_img"><img src="images/uploaddrag.png"/></div>
				

				<a>Drag to Upload</a>
				<input type="file" name="pz_file"  multiple />
			</div>

			

		
               </div>
                <div class="mess_wrapper">
              <div class="row">
               <div class="col-md-4  proposal_left_wrap">Open for proposals for</div>
                <div class="col-md-8 message_box_sign_up_wrap">

<input name="pj_expires" value="15" type="text">

</div></div>
<p class="days_mess_wrap">days (from 15 to 60 days)</p>
               </div>
               
               <div class="country_select_wrapper">
			   <!--generate platform-->
                  <div class="styled-select">
				  <select name="pj_platform">
				  <option value="">Select Platform</option>
				  <?php foreach ( $platform as $value){ ?>
                  <option value="<?php echo $value[0];?>"><?php echo $value[1]; ?></option>
					 <?php } ?>
                  </select></div>
				</div>
				
				
              
			   
			   
               <div class="country_select_wrapper">
                  <div class="styled-select">
                  <select name="pj_budget">
                     <option value="">Budget range</option>
					 <?php foreach ( $budget as $value){  ?>
                     <option value="<?php echo $value->budgetmin.','.$value->budgetmax; ?>"><?php echo $currencysym.$value->budgetmin.' - '.$currencysym.$value->budgetmax; ?></option>
                     
					 <?php } ?>
                  </select></div>
               </div>
			   
			   
               <div class="country_select_wrapper">
			   <!-- generate the developer type dropdown-->
                  <div class="styled-select">
				  
				   <select name="dev_type">
				   <option value="">What kind of professional are you looking for</option>
				  <?php foreach($devType as $value){ ?>
                       <option value="<?php echo $value[0];?>"><?php echo $value[1]; ?></option>
					 <?php } ?>
                  </select></div>
               </div>
            </div>
         </div>
      </div>
      <!--register--section--close-->
      <div class="container devloper_detail_wrapper">
         <div class="row">
            <h2>User information</h2>
            <p> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
            <div class="input_sign_up_wrapper">
               <div class="input-group">
                  <input type="text" name="u_name" class="" placeholder="Name" aria-describedby="basic-addon1">
               </div>
               <div class="input-group">
                  <input type="text" class="" name="u_email" placeholder="Email" aria-describedby="basic-addon1">
               </div>
               
            </div>
            
            <div class="input_sign_up_wrapper">
                    <h3>Country</h3>
               <div class="country_select_wrapper">
                  <div class="styled-select">
                  <select name="pj_count" id="country_select">
                    <option value="">--Select country--</option>
					<?php
                     foreach($countries as $value){?>
                     <option value="<?php echo $value->id;?>"><?php echo $value->title;?></option>
					<?php }?>
                  </select><span style="display:none;" id="loading_loc"><img src="images/loading-loc.gif"/></span></div>
				  
               </div>
               
			    <div class="country_select_wrapper">
                  <div class="styled-select">
                  <select name="pj_count" id="state_select">
                    <option value="">--Select state--</option>
					
                  </select><span style="display:none;" id="loading_state"><img src="images/loading-loc.gif"/></span></div>
				  
               </div>
			   
			    <div class="country_select_wrapper">
                  <div class="styled-select">
                  <select name="pj_count" id="city_select">
                    <option value="">--Select city--</option>
				
                  </select></div>
               </div>
               
        
               
            </div>
         </div>
      </div>
      <!--details--section--close-->
      <div class="container developer_acc_wrapper">
         <div class="row">
            <h2> Featured Options</h2>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
            <div class="row">
			<?php if($sellurgent){ ?>
			<!--urgent-->
               <div class="col-md-3">
                  <div class="acc_package_wrap">
                     <div class="pricing_check_wrap"><span class="check_btn">
                  <input id="Check16" name="purch_urgent" type="checkbox" value="Item 1">
                  <label for="Check16"></label>
                  </span>
                      
                     </div>
                     <h3>Urgent</h3>
                     <p><strong>Get your project validated<br>
 instantly and</strong> let developers<br>
 know this project needs to start<br>
 soon.
                     </p>
                    <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym.$sellurgentFee;?></a></div>
                  </div>
               </div>
			   <?php } ?>
			   <!--urgent end-->
			   
			   <!--private-->
			   <?php if($sellprivate ){ ?>
               <div class="col-md-3">
                  <div class="acc_package_wrap">
                     <div class="pricing_check_wrap"><span class="check_btn">
                  <input id="Check17" name="purch_private" type="checkbox" value="Item 1">
                  <label for="Check17"></label>
                  </span>
                      
                     </div>
                     <h3>Private</h3>
                     <p><strong>Get your project validated<br>
 instantly and</strong> let developers<br>
 know this project needs to start<br>
 soon.
                     </p>
                   <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym.$sellprivateFee;?></a></div>
                  </div>
               </div>
			   <?php } ?>
			   <!--private end-->
			   
			   <!--featured start-->
			   <?php if($sellfeatured){ ?>
               <div class="col-md-3">
                  <div class="acc_package_wrap">
                     <div class="pricing_check_wrap"><span class="check_btn">
                  <input id="Check18" name="purch_featured" type="checkbox" value="Item 1">
                  <label for="Check18"></label>
                  </span>
                      
                     </div>
                     <h3>Featured </h3>
                   <p><strong>Get your project validated<br>
 instantly and</strong> let developers<br>
 know this project needs to start<br>
 soon.
                     </p>
                     <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym.$sellfeaturedFee;?></a></div>
                  </div>
               </div>
			   <?php } ?>
			   <!--featured end-->
			   
			   <!--assisted start-->
			   <?php if($sellassisted){ ?>
               <div class="col-md-3">
                  <div class="acc_package_wrap">
                     <div class="pricing_check_wrap"><span class="check_btn">
                  <input id="Check19" name="purch_assisted" type="checkbox" value="Item 1">
                  <label for="Check19"></label>
                  </span>
                      
                     </div>
                     <h3>Assisted</h3>
                    <p><strong>Get your project validated<br>
 instantly and</strong> let developers<br>
 know this project needs to start<br>
 soon.
                     </p>
                     <div class="featured_price_wrapper"> <a href="#"><?php echo $currencysym.$sellassistedFee;?></a></div>
                  </div>
               </div>
			   <?php } ?>
			   <!--assisted end-->
            </div>
         </div>
      </div>
      <div class="container field_wrapper">
         <div class="row">
            <div class="col-md-10 accept_terms_wrapper accept_wrap">
            <span class="check_btn">
                  <input id="Check21" name="term_cond" type="checkbox" value="Item 1">
                  <label for="Check21"></label><span class="receive_alert_wrapper">I accept the terms and conditions</span>
                  </span>
               
            </div>
            <div class="col-md-2">
               <p>* Mandatory field</p>
            </div>
            <input type="submit" class="create_profile_wrapper" value="Create Your Profile"/>
         </div>
      </div>

	  </form>
	  <script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/jquery.validate.js';?>"></script>
<script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/addmethods.js';?>"></script>
<script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/validate.js';?>"></script>
<script type="text/javascript" src="<?php echo JUri::root().'components/com_jblance/views/project/assets/countries.js';?>"></script>
