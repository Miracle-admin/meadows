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
	$id                = JRequest::getInt('id');
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
	
	$linkpdashboard=JRoute::_(JUri::root().'index.php?option=com_jblance&task=project.projectdashboard&id='.JRequest::getInt('id',0));
	if($step!=1)
	
	$app->redirect($linkpdashboard);
?>
<!--temporary css to be removed later on actual implementation-->

<style type="text/css">
.cont-project-dashboard .steps ul li {
    float: left;
    text-align: center;
    width: 194px;
	 list-style: outside none none;
}

.content.clearfix {
   
    margin: 0 0 0 130px;
    padding: 40px;
    width: 1174px;
}
.active .point, .active .name-step ,.valid .point, .valid .name-step{
    background-color: #143a49 !important;
    color: #143a49 !important;
}
.cont-project-dashboard .steps ul li .point {
    background-color: #ccc;
    border-radius: 100px;
    display: inline-block;
    height: 20px;
    width: 20px;
}

.cont-project-dashboard .steps ul li a .point, .cont-project-dashboard .steps ul li a .name-step {
    background-color: #143a49;
    color: #143a49;
}
.cont-project-dashboard .steps ul li .name-step {
    background-color: transparent !important;
    color: #ccc;
    display: block;
    font-size: 15px;
    font-weight: 600;
}
.line {
    border: 1px solid;
    color: #e2e2e2;
    height: 0;
    left: 277px;
    position: absolute;
    top: 458px;
    width: 756px;
    z-index: -1;
}
.widget-project-owner {
   
    float: left;
    height: 165px;
    padding: 23px 24px;
    width: 715px;
}

.small-box.white-box {
   border: 1px solid;
    float: right;
    padding: 26px 10px;
}
.steps {
    clear: both;
    float: left;
    padding: 34px 0;
    width: 100%;
}
.upselling-projects li
{
list-style:none;
}
.btn.green.with-icon {
    background: green none repeat scroll 0 0;
    border: 1px solid green;
    color: #fff;
}

.fright {
    clear: both;
}

.btn.green.fright {
    background: green none repeat scroll 0 0;
    border: 1px solid green;
    color: white;
}
.box-project-dashboard.white-box {
    float: left;
}
</style>
<div class="content clearfix">

<div class="cont-project-dashboard">
	<div class="head">
					<div class="white-box widget-project fleft widget-project-owner">
						<div class="big-plat">
			<a title="Android App Projects" href="http://www.appfutura.com/android-projects" class="android"><span></span></a>
		</div>
		<div class="title cut">
					 <span><?php echo $title; ?>
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
					</span>
			</div>
	<span class="description">
								<span class="" id="long-description-2593"><?php echo $description; ?></span>
			</span>

								<span class="label light-grey">
		<div title="budget"  ><?php echo $budget; ?></div>
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
	
				<div class="small-box white-box">
						<div class="deposited">
									<p>Do you need help screening candidates?</p>
					<a class="btn green with-icon" href="<?php echo JRoute::_(JUri::base()."index.php?option=com_jblance&task=project.upgradeproject&assisted=1&id=".JRequest::getInt('id').'&'.JSession::getFormToken()."=1"); ?>">Make your project Assisted</a>
					<p>If you need help, contact <a href="mailto:projects@appmeadows.com">projects@appmeadows.com</a></p>
							</div>
		    		</div>
					
					<?php } ?>
		<!--load directly from the model-->
		
		<?php echo $projectstatusBar; ?>
		<!--load-->
			<div class="fleft">
		
			<div class="box-project-dashboard white-box">
	<?php if(!$emailvalid){ ?>
	
	<h1>Validate your account</h1>
	<p>Please validate your email account by clicking on the link on the email we sent you. You will find your AppMeadows login information there.</p>
	<p>If you have not received any email click <a href="<?php echo JRoute::_(JUri::root()."index.php?option=com_jblance&task=project.resendregistrationmail&id=".JRequest::getInt('id').'&'.JSession::getFormToken()."=1"); ?>">here</a> or contact us at <a href="mailto:projects@appmeadows.com">projects@appmeadows.com</a>.</p>
	
	<?php } else{?>
	
	<h1>Validating your project</h1>
	<p>Please wait while we validate your project.An email notification will be sent to you.</p>
	
	<?php } ?>
	
	
	<?php if(count($upgrades)!=$upgradesPurchased){?>
	<h1>Get the most of your project with one of the following upsellings</h1>
	<br>
		<form action="<?php echo JUri::base()."index.php?option=com_jblance&task=project.upgradeproject";?>" method="POST">
    <div class="upsellings-project" id="upsellings-project-no-name-yet-201">
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
                  <td>
                    <input type="checkbox" id="project-urgent" value="1" name="urgent">
                    <label class="labelCheckbox" for="project-urgent"></label>
                  </td>
                  <td width="100"><span class="labels labels ">Urgent</span></td>
                  <td style="width:80%;"><b>Get your project validated instantly</b> and let developers know this project needs to start soon.</td>
                  <td><span class="price">$5</span></td>
                </tr>
				
              </tbody></table>          
            </li>
			<?php } ?>
			
			<?php if($upgrades['private']==0){
			//private
			?>
			
                                              <li class="grey-box">
              <table width="100%" cellspacing="3" cellpadding="3" border="0">
                <tbody><tr>
                  <td></td>
                  <td>
                    <input type="checkbox" id="project-private" value="1" name="private">
                    <label class="labelCheckbox" for="project-private"></label>
                  </td>
                  <td width="100"><span class="labels labels ">Private</span></td>
                  <td style="width:80%;">Your identity will remain unkown and <b>the project won't be found by any search engine</b></td>
                  <td><span class="price">$15</span></td>
                </tr>
              </tbody></table>          
            </li>
               <?php } ?>                               
			   
			   <?php if($upgrades['featured']==0){
			   //featured
			   ?>
			   <li class="grey-box">
              <table width="100%" cellspacing="3" cellpadding="3" border="0">
                <tbody><tr>
                  <td></td>
                  <td>
                    <input type="checkbox" id="project-featured" value="1" name="featured">
                    <label class="labelCheckbox" for="project-featured"></label>
                  </td>
                  <td width="100"><span class="labels labels ">Featured</span></td>
                  <td style="width:80%;"><b>Make your project stand out</b>, get higher quality proposals and let the developers know you have a serious project in mind.</td>
                  <td><span class="price">$20</span></td>
                </tr>
              </tbody></table>          
            </li>
			<?php } ?>
			
                                              
					<?php if($upgrades['assisted']==0){
					//assisted
					?>						  
											  <li class="grey-box">
              <table width="100%" cellspacing="3" cellpadding="3" border="0">
                <tbody><tr>
                  <td></td>
                  <td>
                    <input type="checkbox" id="project-assisted" value="1" name="assisted">
                    <label class="labelCheckbox" for="project-assisted"></label>
                  </td>
                  <td width="100"><span class="labels labels ">Assisted</span></td>
                  <td style="width:80%;">Let us work for you. Our team will get in touch with you to understand your  needs and <b>recommend the best candidates for your project</b>.</td>
                  <td><span class="price">$50</span></td>
                </tr>
              </tbody></table>          
            </li>
			
			<?php } ?>
                              </ul>
      </div>
      <div class="fright">
        <input type="submit" class="btn green fright" value="Checkout" onclick="if(jQuery('input[type=checkbox]:checked').length==0) return false;" style="margin-right: 15px !important;">
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