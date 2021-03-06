<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/listproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);

 $model 		  = $this->getModel();
 $user			  = JFactory::getUser();
 $now 		 	  = JFactory::getDate();
 $config 		  = JblanceHelper::getConfig();
 $currencycode 	  = $config->currencyCode;
 $dformat 		  = $config->dateFormat;
 $showUsername 	  = $config->showUsername;
 $sealProjectBids = $config->sealProjectBids;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';

 $action	= JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
 $link_search	= JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 
 $projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $userHelper = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
?>

<div class="container">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-8">
      <form action="<?php echo $action; ?>" method="post" name="userform">
    <?php /*?>    <a href="<?php echo $link_search; ?>" class="pull-right btn btn-primary"><?php echo JText::_('COM_JBLANCE_SEARCH_PROJECTS'); ?></a><?php */?>
      <!--  <div class="sp10">&nbsp;</div>-->
        <div class="jbl_h3title"><?php echo $this->escape($this->params->get('page_heading', JText::_('COM_JBLANCE_LIST_OF_PROJECTS'))); ?></div>
        <?php
	for ($i=0, $x=count($this->rows); $i < $x; $i++){
		$row = $this->rows[$i];
		$buyer = $userHelper->getUser($row->publisher_userid);
		$daydiff = $row->daydiff;
		
		if($daydiff == -1){
			$startdate = JText::_('COM_JBLANCE_YESTERDAY');
		}
		elseif($daydiff == 0){
			$startdate = JText::_('COM_JBLANCE_TODAY');
		}
		else {
			$startdate =  JHtml::_('date', $row->start_date, $dformat, true);
		}
		
		// calculate expire date and check if expired
		$expiredate = JFactory::getDate($row->start_date);
		$expiredate->modify("+$row->expires days");
		$isExpired = ($now > $expiredate) ? true : false;
		
		$bidsCount = $model->countBids($row->id);
		
		//calculate average bid
		$avg = $projHelper->averageBidAmt($row->id);
		$avg = round($avg, 0);
		
		// 'private invite' project shall be visible only to invitees and project owner
		$isMine = ($row->publisher_userid == $user->id);
		if($row->is_private_invite){
			$invite_ids = explode(',', $row->invite_user_id);
			if(!in_array($user->id, $invite_ids) && !$isMine)
				continue;
		}
	?>
        <div class="project_row_12 row">
          <div class="">
            <?php
		//$attrib = 'width=56 height=56 class="img-polaroid"';
		//$avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
		//echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar) : '&nbsp;' ?>
          </div>
          <div class="col-md-12">
            <div class="project_row_title">
              <?php  echo LinkHelper::getProjectLink($row->id, $row->project_title); ?>
            </div>
            <?php  echo substr($row->description,0,300); ?>
            <br>
            <?php /*?><div class="font14">
				<strong><?php echo JText::_('COM_JBLANCE_POSTED_BY'); ?></strong> : <?php echo LinkHelper::GetProfileLink($row->publisher_userid, $buyer->biz_name); ?>
			</div><?php */?>
            
        <div class="project-option">    
            <?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?> : <span class="medium mr-rt-50"><?php echo JblanceHelper::getCategoryNames($row->id_category); ?> </span> <?php echo JText::_('COM_JBLANCE_LOCATION'); ?> : <span class="medium"><?php echo JblanceHelper::getLocationNames($row->id_location); ?></span>
            </div>
            
            <?php /*?><ul class="promotions">
				<?php if($row->is_featured) : ?>
				<li data-promotion="featured"><?php echo JText::_('COM_JBLANCE_FEATURED'); ?></li>
				<?php endif; ?>
				<?php if($row->is_private) : ?>
	  			<li data-promotion="private"><?php echo JText::_('COM_JBLANCE_PRIVATE'); ?></li>
	  			<?php endif; ?>
				<?php if($row->is_urgent) : ?>
	  			<li data-promotion="urgent"><?php echo JText::_('COM_JBLANCE_URGENT'); ?></li>
	  			<?php endif; ?>
	  			<?php if($sealProjectBids || $row->is_sealed) : ?>
				<li data-promotion="sealed"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></li>
				<?php endif; ?>
				<?php if($row->is_nda) : ?>
				<li data-promotion="nda"><?php echo JText::_('COM_JBLANCE_NDA'); ?></li>
				<?php endif; ?>
			</ul><?php */?>
           <?php /*?> <div class=""> <i class="icon-tags"></i> <?php echo JText::_('COM_JBLANCE_BIDS'); ?> :
              <?php if($sealProjectBids || $row->is_sealed) : ?>
              <span class="label label-info"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
              <?php else : ?>
              <span class="label label-success"><?php echo $bidsCount; ?></span>
              <?php endif; ?>
              <i class="icon-flag"></i> <?php echo JText::_('COM_JBLANCE_STATUS'); ?> : <?php echo $model->getLabelProjectStatus($row->status); ?> </div><?php */?>
          </div>
     <?php /*?>     <div class=" col-md-12 est_bid">

                <div><?php echo JText::_('COM_JBLANCE_AVG_BID'); ?></div>
                <?php if($sealProjectBids || $row->is_sealed) : ?>
                <span class="label label-info"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
                <?php else : ?>
                <span class="font16 boldfont"><?php echo JblanceHelper::formatCurrency($avg, true, false, 0); ?></span><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?>
                <?php endif; ?>


          </div><?php */?>
          <?php if($row->is_private_invite) : ?>
          <div class="">
            <div class="">
              <p class="jbbox-info"><?php echo JText::_('COM_JBLANCE_THIS_IS_A_PRIVATE_INVITE_PROJECT_VISIBLE_TO_OWNER_INVITEES'); ?></p>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php 
	}
	?>
        <div class="pagination pagination-centered clearfix">
          <div class="display-limit pull-right"> <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160; <?php echo $this->pageNav->getLimitBox(); ?> </div>
          <?php echo $this->pageNav->getPagesLinks(); ?> </div>
  <?php /*?>      <?php 
	$link_rss = JRoute::_('index.php?option=com_jblance&view=project&format=feed');
	$rssvisible = (!$config->showRss) ? 'style=display:none' : '';
	?>
        <div class="jbrss" <?php echo $rssvisible; ?>>
          <div id="showrss" class="pull-right"> <a href="<?php echo $link_rss; ?>" target="_blank"> <img src="components/com_jblance/images/rss.png" alt="RSS" title="<?php echo JText::_('COM_JBLANCE_RSS_IMG_ALT'); ?>"> </a> </div>
        </div><?php */?>
        <input type="hidden" name="option" value="com_jblance" />
        <input type="hidden" name="task" value="" />
      </form>
    </div>
  </div>
</div>
