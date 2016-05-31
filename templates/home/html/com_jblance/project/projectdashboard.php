<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	05 March 2015
 * @file name	:	views/project/tmpl/projectprogress.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Project progress page (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.tooltip');
    $app               = JFactory::getApplication();
	$id                = JRequest::getInt('pid');
    $title             = $this->title;                   
	$description       = $this->description;            
	$remaining         = $this->remaining;               
	$bids              = $this->bids;                    
	$location          = $this->location;                
	$budget            = $this->budget;  
    $approved          = $this->approved;	
	$category          = $this->category ;
	$upgrades          = $this->upgrades;
	$projectstatusBar  = $this->projectStatusBar;
	$upgradesPurchased = $this->upgradespurchased;
	$user              = JFactory::getUser();
    $emailvalid        = $user->emailvalid=="" ? true : false;
	$step              = $this->step;
	
	$linkpdashboard=JRoute::_(JUri::root().'index.php?option=com_jblance&task=project.projectdashboard&pid='.JRequest::getInt('pid',0));
	if($step!=1)
	
	$app->redirect($linkpdashboard);
?>
<!--temporary css to be removed later on actual implementation-->

<div class=" project-d-title v-p-hding">
  <div class="container">
    <h1><?php echo $title; ?></h1>
    <h5><?php echo $budget; ?></h5>
  </div>
</div>
<div class="clearfix">
  <div class="cont-project-dashboard">
    <div class="head">
      <div class="validate-top-row">
        <div class="col-md-8"> 
          <!-- <div class="big-plat"> <a title="Android App Projects" href="http://www.appfutura.com/android-projects" class="android"><span></span></a> </div>-->
          <div class="title"> <span>
            <ul class="project_featured">
              <?php if($upgrades['urgent']==1){ ?>
              <li><span class="urgent_project">Urgent</span></li>
              <?php } ?>
              <?php if($upgrades['private']==1){?>
              <li><span class="sealded_project">Private</span></li>
              <?php } ?>
              <?php if($upgrades['featured']==1){?>
              <li><span class="featured_project">Featured</span></li>
              <?php } ?>
              <?php if($upgrades['assisted']==1){?>
              <li><span class="privtae_project">Assisted</span></li>
              <?php } ?>
              <?php if($upgrades['sealed']==1){?>
              <li><span class="nda_project">Sealed</span></li>
              <?php } ?>
            </ul>
            </span> </div>
          <span class="description"> <span class="" id="long-description-2593"><?php echo $description; ?></span> </span> <span class="label light-grey">
          <div title="budget"  ></div>
          </span>
          <div class="clearfix"></div>
          <div class="dev-type"><?php echo $category[0]." / ".$approved." / ".$remaining." / ".$bids;?></div>
          <div class="geo">
            <div itemprop="addressCountry" title="location" ><?php echo $location[0].' , '. $location[1]; ?></div>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php if($upgrades['assisted']==0){
					//assisted
					?>
        <div class=" col-md-4">
          <div class="deposited box-border-ct">
            <p>Do you need help screening candidates?</p>
            <a class="btna" href="<?php echo JRoute::_(JUri::base()."index.php?option=com_jblance&task=project.upgradeproject&assisted=1&pid=".JRequest::getInt('pid').'&'.JSession::getFormToken()."=1"); ?>">Make your project Assisted</a> <span>If you need help, contact <a href="mailto:projects@appmeadows.com">projects@appmeadows.com</a></span> </div>
        </div>
        <?php } ?>
      </div>
      <!--load directly from the model-->
      
      <div class="v-status-bar"><?php echo $projectstatusBar; ?> </div>
      
      <!--load-->
      <div class="validate-wrapper ">
        <div class="">
          <?php if(!$emailvalid){ ?>
          <h4>Validate your account</h4>
          <p>Please validate your email account by clicking on the link on the email we sent you. You will find your AppMeadows login information there.<br>
            If you have not received any email click <a href="<?php echo JRoute::_(JUri::root()."index.php?option=com_jblance&task=project.resendregistrationmail&id=".JRequest::getInt('id').'&'.JSession::getFormToken()."=1"); ?>">here</a> or contact us at <a href="mailto:projects@appmeadows.com">projects@appmeadows.com</a>.</p>
          <?php } else{?>
          <h4>Validating your project</h4>
          <p>Please wait while we validate your project.An email notification will be sent to you.</p>
          <?php } ?>
          <?php if(count($upgrades)!=$upgradesPurchased){?>
          <br>
          <h4>Get the most of your project with one of the following upsellings</h4>
          <form action="<?php echo JUri::base()."index.php?option=com_jblance&task=project.upgradeproject";?>" method="POST">
            <div class="upsellings-project-plan" id="upsellings-project-no-name-yet-201">
              <?php /* ?>
                <div style="float:left;">
                <ul class="upselling-projects">
                  <?php if($upgrades['urgent']==0){ 
		//urgent
		?>
                  <li class="grey-box">
                    <table width="100%" cellspacing="3" cellpadding="3" border="0">
                      <tbody>
                        <tr>
                          <td></td>
                          <td><input type="checkbox" id="project-urgent" value="1" name="urgent">
                            <span class="labelCheckbox " for="project-urgent"></span></td>
                          <td><span class="labels labels ">Urgent</span></td>
                          <td>Get your project validated instantly and let developers know this project needs to start soon.</td>
                          <td><span class="price">$5</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </li>
                  <?php } ?>
                  <?php if($upgrades['private']==0){
			//private
			?>
                  <li class="grey-box">
                    <table width="100%" cellspacing="3" cellpadding="3" border="0">
                      <tbody>
                        <tr>
                          <td></td>
                          <td><input type="checkbox" id="project-private" value="1" name="private">
                            <span class="labelCheckbox" for="project-private"></span></td>
                          <td><span class="labels labels ">Private</span></td>
                          <td>Your identity will remain unkown and the project won't be found by any search engine</td>
                          <td><span class="price">$15</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </li>
                  <?php } ?>
                  <?php if($upgrades['featured']==0){
			   //featured
			   ?>
                  <li class="grey-box">
                    <table width="100%" cellspacing="3" cellpadding="3" border="0">
                      <tbody>
                        <tr>
                          <td></td>
                          <td><input type="checkbox" id="project-featured" value="1" name="featured">
                            <span class="labelCheckbox" for="project-featured"></span></td>
                          <td><span class="labels labels ">Featured</span></td>
                          <td>Make your project stand out, get higher quality proposals and let the developers know you have a serious project in mind.</td>
                          <td><span class="price">$20</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </li>
                  <?php } ?>
                  <?php if($upgrades['assisted']==0){
					//assisted
					?>
                  <li class="grey-box">
                    <table width="100%" cellspacing="3" cellpadding="3" border="0">
                      <tbody>
                        <tr>
                          <td></td>
                          <td><input type="checkbox" id="project-assisted" value="1" name="assisted">
                            <span class="labelCheckbox" for="project-assisted"></span></td>
                          <td><span class="labels labels ">Assisted</span></td>
                          <td>Let us work for you. Our team will get in touch with you to understand your  needs and recommend the best candidates for your project.</td>
                          <td><span class="price">$50</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </li>
                  <?php } ?>
                </ul>
              </div>
                <?php */ ?>
              <div class="fright">
                <input type="submit" class="signup-brn" value="Checkout" onclick="if(jQuery('input[type=checkbox]:checked').length==0) return false;">
              </div>
            </div>
            <input type="hidden" name='id' value="<?php echo JRequest::getInt('id'); ?>">
            <?php echo JHtml::_('form.token'); ?>
          </form>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
