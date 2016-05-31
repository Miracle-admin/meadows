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
  JHtml::script(Juri::base() . '/media/system/js/bpopup.js');
 $nameOrUsername = ($showUsername) ? 'username' : 'name';

 $action	= JRoute::_('index.php?option=com_jblance&view=project&layout=projecthistory');
 $link_search	= JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 
 $projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $userHelper = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 
 
?>

<form action="<?php echo $action; ?>" method="post" name="userForm">
	<a href="<?php echo $link_search; ?>" class="pull-right btn btn-primary"><?php echo JText::_('COM_JBLANCE_SEARCH_PROJECTS'); ?></a>
	<div class="sp10">&nbsp;</div>
	<div class="jbl_h3title"><?php echo JText::_('Completed Projects'); ?></div>
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
	<div class="row-fluid">
		<div class="span1">
		<?php
		$attrib = 'width=56 height=56 class="img-polaroid"';
		$avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
		echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar) : '&nbsp;' ?>
		</div>
		<div class="span6">
			<h3 class="media-heading">
				<?php echo LinkHelper::getProjectLink($row->project_id, $row->project_title); ?>
			</h3>
			<div class="font14">
				<strong><?php echo JText::_('COM_JBLANCE_POSTED_BY'); ?></strong> : <?php echo LinkHelper::GetProfileLink($row->publisher_userid, $buyer->biz_name); ?>
			</div>
			<div class="font14">
				<strong><?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?></strong> : <?php echo JblanceHelper::getCategoryNames($row->id_category); ?>
			</div>
			<div class="font14">
				<strong><?php echo JText::_('COM_JBLANCE_LOCATION'); ?></strong> : <span class="small"><?php echo JblanceHelper::getLocationNames($row->id_location); ?></span>
			</div>
			<ul class="promotions">
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
			</ul>
		</div>
		<div >
	
		<a "viewpm-<?php echo $row->project_id; ?>" onclick="showpminfo(this,event,<?php echo $row->project_id; ?>,<?php  echo $row->publisher_userid; ?>);"  href="<?php echo JUri::root(); ?>index.php?option=com_jblance&view=membership&layout=managepay" style="border: 1px solid;background: red;color: #fff;padding: 8px;border-radius: 8px;">View Payments</a>
		
		</div>
		<div >
		<a onclick="showposrev(this,event,<?php echo $row->project_id; ?>);" id="porev-<?php echo $row->id; ?>" href="#" style="border: 1px solid;background: #0600FF;color: #fff;padding: 8px;border-radius: 8px;float: left;
    margin: 10px 0 0 0;"  >See PO's Review</a>
        </div>
		
	</div>
	<div class="lineseparator"></div>
	<?php 
	}
	?>
	
	<div class="pagination pagination-centered clearfix">
		<div class="display-limit pull-right">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pageNav->getLimitBox(); ?>
		</div>
		<?php echo $this->pageNav->getPagesLinks(); ?>
	</div>
	<?php 
	$link_rss = JRoute::_('index.php?option=com_jblance&view=project&format=feed');
	$rssvisible = (!$config->showRss) ? 'style=display:none' : '';
	?>
	<div class="jbrss" <?php echo $rssvisible; ?>>
		<div id="showrss" class="pull-right">
			<a href="<?php echo $link_rss; ?>" target="_blank">
				<img src="components/com_jblance/images/rss.png" alt="RSS" title="<?php echo JText::_('COM_JBLANCE_RSS_IMG_ALT'); ?>">
			</a>
		</div>
	</div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="" />	
</form>
<div style="display:none;" id="popup-container-payment"></div>
<div style="display:none;" id="popup-container-poreview"></div>
<script type="text/javascript">
function showpminfo(elem,e,id,po)
{
e.preventDefault();
url='<?php echo JUri::base()."index.php?option=com_jblance&task=upgrades.showPayments&id="?>'+id+'&po='+po ;

jQuery("#popup-container-payment").bPopup({
content:'ajax', //'ajax', 'iframe' or 'image'
closeClass :'btn-close',
loadUrl: url
//,
//onOpen: function() { alert('onOpen fired'); }, 
//onClose: function() { alert('onClose fired'); }

},
 function()
 {
  //alert('Callback fired');
 })

}

function showposrev(elem,e,id)
{
e.preventDefault();
url='<?php echo JUri::base()."index.php?option=com_jblance&task=upgrades.showReviews&id="?>'+id ;
jQuery("#popup-container-poreview").bPopup(
{
content:'ajax', //'ajax', 'iframe' or 'image'
closeClass :'btn-close',
loadUrl: url,
//onOpen: function() { alert('onOpen fired'); }, 
//onClose: function() { alert('onClose fired'); }

}, 
 function()
 {
  //alert('Callback fired');
 })

}
</script>
<style>
#popup-container-poreview,#popup-container-payment{
    background-color: #fff;
    border-radius: 10px;
   
    color: #111;
    display: none;
    min-width: 450px;
    padding: 25px;
}
.pm_info {
    float: left;
	 width: 403px;
}
.pm_info {
    height: 500px;
}
.b-ajax-wrapper {
    height: 500px;
}
.accepted {
    border: 2px solid;
    border-radius: 4px;
    margin: 0 0 0 16px;
    padding: 4px;
    text-align: center;
	    background: #38DC7B;
}
.released {
    border: 2px solid;
    border-radius: 4px;
    margin: 0 0 0 16px;
    padding: 4px;
    text-align: center;
	    background: #F58847;
}
</style>