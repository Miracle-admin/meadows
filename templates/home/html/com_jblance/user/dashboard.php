<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/user/tmpl/dashboard.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Displays the user Dashboard (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);
 $doc = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 
 $model 				= $this->getModel();
 $user					= JFactory::getUser();
 $config 				= JblanceHelper::getConfig();
 $showFeedsDashboard 	= $config->showFeedsDashboard;
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $enableWithdrawFund 	= $config->enableWithdrawFund;

 JText::script('COM_JBLANCE_CLOSE');
 
 $link_edit_profile = JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile');
 $link_portfolio	= JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio');
 $link_messages		= JRoute::_('index.php?option=com_jblance&view=message&layout=inbox');
 //old link $link_post_project = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject');
 $link_post_project = JRoute::_(' index.php?option=com_jblance&view=project&layout=editprojectcustom&Itemid=337');
  //old link $link_list_project = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
 $link_list_project = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject&Itemid=197');
 //$link_search_proj  = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 $link_my_project 	= JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject');
 $link_my_bid 		= JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid');
 $link_my_services 	= JRoute::_('index.php?option=com_jblance&view=service&layout=myservice');
 $link_service_bght	= JRoute::_('index.php?option=com_jblance&view=service&layout=servicebought');
 $link_deposit		= JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund');
 $link_withdraw		= JRoute::_('index.php?option=com_jblance&view=membership&layout=withdrawfund');
 $link_escrow		= JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow');
 $link_transaction	= JRoute::_('index.php?option=com_jblance&view=membership&layout=transaction');
 $link_managepay	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay');
 $link_subscr_hist	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory');
 $link_buy_subscr	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd');
 
 JblanceHelper::setJoomBriToken();
 
if(!JBLANCE_FREE_MODE){
	if(!$user->guest){
		$planStatus = JblanceHelper::planStatus($user->id);
		
		if($planStatus == '1'){ ?>

<div class="jbbox-warning"> <?php echo JText::sprintf('COM_JBLANCE_USER_SUBSCRIPTION_EXPIRED', $link_buy_subscr); ?> </div>
<?php }
	elseif($planStatus == '2'){ ?>
<div class="jbbox-info"> <?php echo JText::sprintf('COM_JBLANCE_USER_DONT_HAVE_ACTIVE_PLAN', $link_subscr_hist); ?> </div>
<?php }
	}
} ?>

<div class="edit-profile-dashboard-wrap">

<?php /*?><div class="jbl_h3title"><?php echo JText::_($this->userInfo->name).' '.JText::_('COM_JBLANCE_DASHBOARD'); ?></div><?php */?>

<div class="row ">
  <div class="dashnord-lf-mnu col-md-3" >
    <ul class=" nav-list">
      <li class="hding">
        <h4><?php echo JText::_('COM_JBLANCE_PROFILE'); ?></h4>
      </li>
      <li> <a href="<?php echo $link_edit_profile; ?>"><i class="icon-pencil"></i> <?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?> </a> </li>
      <li>
        <?php 
				$avatars = JblanceHelper::getAvatarIntegration();
				$link_edit_picture = $avatars->getEditURL();
				?>
        <a href="<?php echo $link_edit_picture; ?>"><i class="icon-picture"></i> <?php echo JText::_('COM_JBLANCE_EDIT_PICTURE'); ?> </a> </li>
      <?php if($this->dbElements['allowAddPortfolio']) : ?>
      <li> <a href="<?php echo $link_portfolio; ?>"><i class="icon-book"></i> <?php echo JText::_('COM_JBLANCE_PORTFOLIO'); ?> </a> </li>
      <?php endif; ?>
      <li> <a href="<?php echo $link_messages; ?>"><i class="icon-envelope"></i> <?php echo JText::_('COM_JBLANCE_PRIVATE_MESSAGES'); ?> </a> </li>
      <li class="">
        <h4><?php echo JText::_('COM_JBLANCE_PROJECTS'); ?></h4>
      </li>
      <?php if($this->dbElements['allowPostProjects']) : ?>
      <li> <a href="<?php echo $link_post_project; ?>"><i class="icon-plus"></i> <?php echo JText::_('COM_JBLANCE_POST_NEW_PROJECT'); ?> </a> </li>
      <li> <a href="<?php echo $link_my_project; ?>"><i class="icon-th-list"></i> <?php echo JText::_('COM_JBLANCE_MY_PROJECTS'); ?> </a> </li>
      <li> <a href="<?php echo $link_service_bght; ?>"><i class="icon-certificate"></i> <?php echo JText::_('COM_JBLANCE_SERVICES_BOUGHT'); ?> </a> </li>
      <?php endif; ?>
      <?php if($this->dbElements['allowBidProjects']) : ?>
      <li> <a href="<?php echo $link_list_project; ?>"><i class="icon-th-list"></i> <?php echo JText::_('COM_JBLANCE_LATEST_PROJECTS'); ?> </a> </li>
      <?php /*?><li> <a href="<?php echo $link_search_proj; ?>"><i class="icon-search"></i> <?php echo JText::_('COM_JBLANCE_SEARCH_PROJECTS'); ?> </a> </li><?php */?>
      <li> <a href="<?php echo $link_my_bid; ?>"><i class="icon-tags"></i> <?php echo JText::_('COM_JBLANCE_MY_BIDS'); ?> </a> </li>
      <li> <a href="<?php echo $link_my_services; ?>"><i class="icon-align-justify"></i> <?php echo JText::_('COM_JBLANCE_MY_SERVICES'); ?> </a> </li>
      <?php endif; ?>
      <?php 
				if(!JBLANCE_FREE_MODE) :
			?>
      <li class="">
        <h4><?php echo JText::_('COM_JBLANCE_BILLING_AND_FINANCE'); ?></h4>
      </li>
      <li> <a href="<?php echo $link_deposit; ?>"><i class="icon-circle-arrow-right"></i> <?php echo JText::_('COM_JBLANCE_DEPOSIT_FUNDS'); ?> </a> </li>
      <!-- check if withdraw fund is enabled -->
      <?php if($enableWithdrawFund) : ?>
      <li> <a href="<?php echo $link_withdraw; ?>"><i class="icon-circle-arrow-left"></i> <?php echo JText::_('COM_JBLANCE_WITHDRAW_FUNDS'); ?> </a> </li>
      <?php endif; ?>
      <!-- check if escrow payment is enabled -->
      <?php if($enableEscrowPayment) : ?>
      <li> <a href="<?php echo $link_escrow; ?>"><i class="icon-refresh"></i> <?php echo JText::_('COM_JBLANCE_ESCROW_PAYMENT'); ?> </a> </li>
      <?php endif; ?>
      <li> <a href="<?php echo $link_transaction; ?>"><i class="icon-tasks"></i> <?php echo JText::_('COM_JBLANCE_TRANSACTION_HISTORY'); ?> </a> </li>
      <li> <a href="<?php echo $link_managepay; ?>"><i class="icon-list-alt"></i> <?php echo JText::_('COM_JBLANCE_MANAGE_PAYMENTS'); ?> </a> </li>
      <li> <a href="<?php echo $link_subscr_hist; ?>"><i class="icon-asterisk"></i> <?php echo JText::_('COM_JBLANCE_MY_SUBSCRS'); ?> </a> </li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="col-md-9 dashnord-lrt-pnl"> 
    <!-- pending tasks section -->
    <h3><?php echo JText::_('COM_JBLANCE_TASKS_PENDING'); ?></h3>
    <?php 
		if(!empty($this->pendings)){
			foreach($this->pendings as $pending){
		?>
    <ul class="unstyled">
      <li><i class="icon-warning-sign"></i> <?php echo $pending; ?></li>
    </ul>
    <?php		
			}
		}
		else { ?>
    <div class="alert alert-info"><?php echo JText::_('COM_JBLANCE_NO_TASK_PENDING_YOUR_ACTION'); ?></div>
    <?php	
		}
		?>
    <div class="lineseparator"></div>
    <!-- news feed section -->
    <?php if($showFeedsDashboard) : ?>
    <h3><?php echo JText::_('COM_JBLANCE_NEWS_FEED'); ?></h3>
    <?php
		$n=count($this->feeds);
		if($n == 0){ ?>
    <div class="alert alert-info"><?php echo JText::_('COM_JBLANCE_NO_NEWSFEEDS_OR_POSTS'); ?></div>
    <?php 
		}
		for($i=0, $n=count($this->feeds); $i < $n; $i++) {
			$feed = $this->feeds[$i]; ?>
    <div class="media jb-borderbtm-dot" id="jbl_feed_item_<?php echo $feed->id; ?>"> <?php echo $feed->logo; ?>
      <div class="media-body"> <?php echo $feed->title; ?>
        <p> <i class="icon-calendar"></i> <?php echo $feed->daysago; ?> | <span id="feed_hide_<?php echo $feed->id; ?>" class="help-inline">
          <?php if($feed->isMine) : ?>
          <a class="alink" onclick="processFeed('<?php echo $user->id; ?>' , '<?php echo $feed->id; ?>', 'remove');" href="javascript:void(0);"> <i class="icon-remove"></i> <?php echo JText::_('COM_JBLANCE_REMOVE'); ?> </a>
          <?php endif; ?>
          <a class="alink" onclick="processFeed('<?php echo $user->id; ?>' , '<?php echo $feed->id; ?>', 'hide');" href="javascript:void(0);"> <i class="icon-eye-close"></i> <?php echo JText::_('COM_JBLANCE_HIDE'); ?> </a> </span> </p>
      </div>
    </div>
    <?php
		}
		?>
    <?php endif; ?>
  </div>
</div>

</div>

