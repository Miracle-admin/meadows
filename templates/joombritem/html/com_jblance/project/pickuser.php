<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/project/tmpl/pickuser.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Pick user from the bidders (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/mooboomodal.js");
 $doc->addScript("components/com_jblance/js/jbmodal.js");

 $model 		= $this->getModel();
 $user 			= JFactory::getUser();
 $config 		= JblanceHelper::getConfig();
 
 $currencycode 	= $config->currencyCode;
 $dformat 		= $config->dateFormat;
 $checkFund 	= $config->checkfundPickuser;
 $showUsername 	= $config->showUsername;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 $curr_balance = JblanceHelper::getTotalFund($user->id);
 
 $link_deposit  = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
 $linkpdashboard=JRoute::_(JUri::root().'index.php?option=com_jblance&task=project.projectdashboard&id='.JRequest::getInt('id',0));
 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_YES');
 $app=JFactory::getApplication();
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
 if($step!=2)
 $app->redirect($linkpdashboard);
?>
<script>
<!--
	function checkBalance(){

		if(!$$('input[name="assigned_userid"]:checked')[0]){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_PICK_AN_USER_FROM_THE_LIST', true); ?>');
			return false;
		}
		
		var checkFund = parseInt('<?php echo $checkFund; ?>');

		if(checkFund){
			var balance = parseFloat('<?php echo $curr_balance; ?>');
			var assigned = $$('input[name="assigned_userid"]:checked')[0].get('value');
			var bidamt = $('bidamount_'+assigned).get('value');

			if(balance < bidamt){
				modalConfirm('<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_FUND'); ?>', '<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_BALANCE_PICK_USER'); ?>', '<?php echo $link_deposit; ?>');
				return false;
			}
		}
		return true;	
	}
//-->
</script>
<!--- temp css-->
<style type="text/css">
.steps ul li {
    float: left;
    text-align: center;
    width: 194px;
	 list-style: outside none none;
}

.active .point, .active .name-step ,.valid .point, .valid .name-step{
    background-color: #143a49 !important;
    color: #143a49 !important;
}
.steps ul li .point {
    background-color: #ccc;
    border-radius: 100px;
    display: inline-block;
    height: 20px;
    width: 20px;
}

.steps ul li a .name-step {
    background-color: #143a49;
    color: #143a49;
}
.steps ul li .name-step {
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
    left: 234px;
    position: absolute;
    top: 496px;
    width: 756px;
    z-index: -1;
}

.steps {
    clear: both;
    float: left;
    padding: 68px 0 25px;
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

.small-box.white-box {
   border: 1px solid;
    float: right;
    padding: 26px 10px;
}
.widget-project-owner {
   
    float: left;
    height: 165px;
    padding: 23px 24px;
    width: 715px;
}
.no_bids {
    color: red;
    float: left;
    font-weight: bold;
}
</style>
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

								<span class="light-grey">
		<div title="budget" class="hasTip" ><?php echo $budget; ?></div>
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
					</div>
<!--css-->
<?php echo $projectstatusBar; ?>
<?php if(count($this->rows)==0){echo"<div class='no_bids'>No any user has placed a bid on your project yet.</div>";}else{ ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm">
	<!--<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PICK_USER').' : '.$this->project->project_title; ?></div>-->
	<div class="well well-small pull-right span3 text-center font16">
		<b><?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE'); ?> : <?php echo JblanceHelper::formatCurrency($curr_balance); ?></b>
	</div>
	<div class="clearfix"></div>
	
	<?php
	for($i=0, $n=count($this->rows); $i < $n; $i++){
		$row = $this->rows[$i];
	?>
	<div class="row-fluid">
		<div class="span1" style="width:5px;">
			<?php if($row->status == '') : ?>
			<input type="radio" name="assigned_userid" id="assigned_userid_<?php echo $row->id; ?>" value="<?php echo $row->user_id; ?>"/>
			<?php endif; ?>
		</div>
		<div class="span1">
			<?php
			$attrib = 'width=56 height=56 class="img-polaroid"';
			$avatar = JblanceHelper::getThumbnail($row->user_id, $attrib);
			echo !empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar) : '&nbsp;'; ?>
		</div>
		<div class="span6">
			<h5 class="media-heading">
				<?php echo LinkHelper::GetProfileLink(intval($row->user_id), $this->escape($row->$nameOrUsername)); ?>
			</h5>
			<p class="font14"><?php echo ($row->details) ? $row->details : JText::_('COM_JBLANCE_DETAILS_NOT_PROVIDED'); ?></p>
			<p>
				<span title="<?php echo JText::_('COM_JBLANCE_BID_DATE'); ?>"><i class="icon-calendar"></i> <?php echo JHtml::_('date', $row->bid_date, $dformat); ?></span>
				<!-- Show attachment if found -->
				<?php
				if(!empty($row->attachment)) : ?>
					 | <span><?php echo LinkHelper::getDownloadLink('nda', $row->id, 'project.download'); ?></span>
				<?php	
				endif;
				?>
			</p>
		</div>
		<div class="span2">
			<?php $rate = JblanceHelper::getAvarageRate($row->user_id, true); ?>
		</div>
		<div class="span2">
			<div class="text-center">
				<span class="font20">
					<?php echo JblanceHelper::formatCurrency($row->amount, true, false, 0); ?>
					<input type="hidden" id="bidamount_<?php echo $row->user_id; ?>" value="<?php echo  $row->amount; ?>" />
				</span><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?><br>
				<span class="font12">
				<?php if($row->project_type == 'COM_JBLANCE_FIXED') : ?>
					<?php echo $row->delivery; ?> <?php echo JText::_('COM_JBLANCE_BID_DAYS'); ?>
					<?php elseif($row->project_type == 'COM_JBLANCE_HOURLY') : 
					$commitment = new JRegistry;
					$commitment->loadString($row->commitment);
					?>
					<?php echo $row->delivery; ?> <?php echo JText::_('COM_JBLANCE_HOURS_PER').' '.JText::_($commitment->get('interval')); ?>
					<?php endif; ?>
				</span><br>
				<?php if($row->status == 'COM_JBLANCE_ACCEPTED') : ?>
				<span class="label label-success"><?php echo JText::_($row->status); ?></span>
				<?php elseif($row->status == 'COM_JBLANCE_DENIED') : ?>
				<span class="label label-important"><?php echo JText::_($row->status); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="lineseparator"></div>
	<?php 
	}
	?>
	<div class="form-actions">
		<input type="submit" value="<?php echo JText::_('COM_JBLANCE_PICK_USER'); ?>" class="btn btn-primary" onclick="return checkBalance();" />
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.savepickuser" />	
	<input type="hidden" name="pid" value="<?php echo $row->project_id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php } ?>
			<div class="box-project-dashboard white-box">
	
	
	
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