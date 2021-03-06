<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/detailproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows details of the project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHtml::_('behavior.framework', true);

 $row 	= $this->row;
 $model = $this->getModel();
 $user 	= JFactory::getUser();
 $uri 	= JFactory::getURI();
 
 $config 		  = JblanceHelper::getConfig();
 $currencycode 	  = $config->currencyCode;
 $dformat 		  = $config->dateFormat;
 $enableReporting = $config->enableReporting;
 $guestReporting  = $config->enableGuestReporting;
 $enableAddThis   = $config->enableAddThis;
 $addThisPubid	  = $config->addThisPubid;
 $showUsername	  = $config->showUsername;
 $sealProjectBids = $config->sealProjectBids;
 
 $nameOrUsername = ($showUsername) ? 'username' : 'name';
 
 $projHelper 	= JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $hasJBProfile  = JblanceHelper::hasJBProfile($user->id);
 $publisher 	= JFactory::getUser($row->publisher_userid);
 
 if($hasJBProfile){
 	$jbuser = JblanceHelper::get('helper.user');
 	$userGroup = $jbuser->getUserGroupInfo($user->id, null);
 }
 
 $isMine = ($row->publisher_userid == $user->id);
 
 $link_report 		= JRoute::_('index.php?option=com_jblance&view=message&layout=report&projectId='.$row->id.'&report=project&link='.base64_encode($uri)/* .'&tmpl=component' */);
 $link_edit_project = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject&projectId='.$row->id); 
 $link_pick_user	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=pickuser&projectId='.$row->id);
 JText::script('COM_JBLANCE_CLOSE');
 
 $now 		 = JFactory::getDate();
 $expiredate = JFactory::getDate($row->start_date);
 $expiredate->modify("+$row->expires days");
 //$isExpired = ($now > $expiredate) ? true : false;
 $isExpired = ($row->status == 'COM_JBLANCE_EXPIRED') ? true : false;
?>
<script type="text/javascript">
	window.addEvent('domready',function() {
		new Fx.SmoothScroll({
			duration: 500
			}, window);
	});

	window.addEvent('domready', function(){
		$('commentForm').addEvent('submit', function(e){
		e.stop();
		var req = new Request.HTML({
			url: 'index.php?option=com_jblance&task=project.submitforum&<?php echo JSession::getFormToken().'=1'; ?>',
			data: $('commentForm'),
			onRequest: function(){ $('btnSendMessage').set({'disabled': true, 'value': '<?php echo JText::_('COM_JBLANCE_SENDING'); ?>'}); },
			onSuccess: function(tree, response){
				
				var li = new Element('li');
				var span = new Element('span', {'text': response[1].get('text')}).inject(li);
				var span1 = new Element('span', {'text': '<?php echo JText::_('COM_JBLANCE_RECENTLY'); ?>', 'class':''}).inject(span);
				var p = new Element('p', {'text': response[2].get('text')}).inject(li);
				li.inject($('commentList')).highlight('#EEE');
				$('commentForm').reset();
				$('btnSendMessage').set('value', '<?php echo JText::_('COM_JBLANCE_SENT'); ?>');
				
				//Scrolls the window to the bottom
				var myFx = new Fx.Scroll('commentList').toBottom();
			}
		}).send();
		});
	});
</script>
<!-- <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="userForm"> -->

<div class="project-d-title">
  <div class="container"><?php echo $row->project_title; ?> </div>
</div>
<div class="col-md-8">

  <div class="row-fluid">
    <div class="pro-desp-details">
      <h4><?php echo JText::_('COM_JBLANCE_PROJECT_DESCRIPTION'); ?>:</h4>
      <div style="text-align: justify;"><?php echo $row->description; ?></div>
      <?php 
			if($row->project_type == 'COM_JBLANCE_HOURLY'){
				// Convert the commitment params field to an array.
				$commitment = new JRegistry;
				$commitment->loadString($row->commitment);
			?>
      <h4><?php echo JText::_('COM_JBLANCE_HOURS_OF_WORK'); ?>:</h4>
      <div>
        <?php 
				if($commitment->get('undefined') == 'notsure'){
					echo JText::_('COM_JBLANCE_UNSPECIFIED');
				}
				else {
					echo $commitment->get('period').' '.JText::_('COM_JBLANCE_HOURS_PER').' '.JText::_($commitment->get('interval')); 
				}	
				?>
      </div>
      <h4><?php echo JText::_('COM_JBLANCE_PROJECT_DURATION'); ?>:</h4>
      <div><?php echo JblanceHelper::getProjectDuration($row->project_duration); ?></div>
      <?php 
			}
			?>
      <h4><?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?>:</h4>
      <div><?php echo JblanceHelper::getCategoryNames($row->id_category); ?></div>
      <h4><?php echo JText::_('COM_JBLANCE_LOCATION'); ?>:</h4>
      <div><?php echo JblanceHelper::getLocationNames($row->id_location); ?></div>
      <br>
      <?php 
			//show map if the id_location is > 0
			if($row->id_location > 0){
				$location = new JRegistry;
				$location->loadString($this->get('State')->get('projectLocation'));
				if($location->get('latitude') !== '' && $location->get('longitude') !== ''){ 
					JblanceHelper::getGoogleMap($location->get('latitude'), $location->get('longitude'), $row->project_title);
					?>
      <div class="img-polaroid">
        <div id="map-canvas" style="width: 100%; height: 300px;"></div>
      </div>
      <?php	
				}
				else { ?>
      <?php	
				}
			}
			 ?>
      <?php
			if(count($this->projfiles) > 0) : ?>
      <h4><?php echo JText::_('COM_JBLANCE_ADDITIONAL_FILES'); ?>:</h4>
      <div><i class="icon-file"></i>
        <?php
				foreach($this->projfiles as $projfile){ 
					if($user->guest)
						echo $projfile->show_name.', ';
					else
						echo LinkHelper::getDownloadLink('project', $projfile->id, 'project.download').', ';
				} ?>
      </div>
      <?php endif; ?>
      <?php if($this->fields) : ?>
      <?php 
			$fields = JblanceHelper::get('helper.fields');		// create an instance of the class FieldsHelper
			$parents = $children = array();
			//isolate parent and childr
			foreach($this->fields as $ct){
				if($ct->parent == 0)
					$parents[] = $ct;
				else
					$children[] = $ct;
			}
			if(count($parents)){
				foreach($parents as $pt){ ?>
      <h4><?php echo JText::_($pt->field_title); ?>:</h4>
      <div class="form-horizontal">
        <?php
						foreach($children as $ct){
							if($ct->parent == $pt->id){ ?>
        <div class="control-group">
          <label class="control-label nopadding"><?php echo JText::_($ct->field_title); ?>: </label>
          <div class="controls">
            <?php $fields->getFieldHTMLValues($ct, $row->id, 'project'); ?>
          </div>
        </div>
        <?php
							}
						} ?>
      </div>
      <?php
				}
			}
			?>
      <?php endif; ?>
    </div>
  </div>
 
  <div class="row-fluid clearfix">
    <div class="add-msh-wrapper">
      <div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PUBLIC_CLARIFICATION_BOARD'); ?></div>
      <div><?php echo JText::sprintf('COM_JBLANCE_X_MESSAGES', count($this->forums)); ?></div>
      <div class="add-msg"><a href="#addmessage_bm" class="abtn"><?php echo JText::_('COM_JBLANCE_ADD_MESSAGE'); ?></a></div>
      <div id="comments">
        <ul id="commentList" style="max-height: 400px; overflow: auto;">
          <?php 
				for($i=0, $x=count($this->forums); $i < $x; $i++){
					$forum = $this->forums[$i];
					$poster = JFactory::getUser($forum->user_id)->$nameOrUsername;
					$postDate = JFactory::getDate($forum->date_post); ?>
          <li> <span><?php echo LinkHelper::GetProfileLink($forum->user_id, $poster); ?> <span class=""> <?php echo JblanceHelper::showTimePastDHM($postDate, 'SHORT'); ?> </span> </span>
            <p><?php echo $forum->message; ?></p>
          </li>
          <?php 
				}
		      	?>
        </ul>
        <form id="commentForm" method="post" action="<?php echo JRoute::_('index.php'); ?>" class="form-inline">
          <a id="addmessage_bm"></a> 
          <!-- show the forum add message only for bidder and publisher -->
          <?php 
					$hasBid = $projHelper->hasBid($row->id, $user->id);
					if(($user->id == $row->publisher_userid) || $hasBid) :
					?>
          <div class="well">
            <textarea id="message" name="message" rows="3" class="input-xxlarge"></textarea>
            <input type="submit" value="<?php echo JText::_('COM_JBLANCE_POST_MESSAGE'); ?>" id="btnSendMessage" class="btn btn-primary" />
            <div style="margin-top: 5px;"><?php echo JText::_('COM_JBLANCE_SHARING_CONTACT_PROHIBITED'); ?></div>
            <input type="hidden" name="project_id" value="<?php echo $row->id; ?>" />
            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>" />
          </div>
          <?php 
					else : ?>
          <div class="jbbox-info"><?php echo JText::_('COM_JBLANCE_MUST_BID_TO_POST_MESSAGES'); ?></div>
          <?php	
					endif;
					?>
        </form>
      </div>
    </div>
  </div>

    <div class="all-bids-below">
      <h4><?php echo JText::_('COM_JBLANCE_ALL_BIDS'); ?></h4>
      
      <ul >
      <!-- if the project is sealed and the user is not the publisher, then hide the bid details of the project -->
      
      
      <?php 
			//check if the user has bid
			$hasBid = $projHelper->hasBid($row->id, $user->id);
			?>
      <?php if(($sealProjectBids || $row->is_sealed) && ($user->id != $row->publisher_userid) && !$hasBid) : ?>
      <div class="jbbox-info"><?php echo JText::_('COM_JBLANCE_SEALED_PROJECT_PULBISHER_AND_BIDDERS_SEE_DETAILS'); ?></div>
      <?php else : ?>
      
      
      <?php
				if(!count($this->bids))
					echo JText::_('COM_JBLANCE_THERE_ARE_NO_BIDS_YET');
				else {
					for($i=0, $n=count($this->bids); $i < $n; $i++){
						$bid = $this->bids[$i];
					?>
      <li class="row-fluid">
     <?php /*?>   <div class="span1">
          <?php
						$attrib = 'width=56 height=56 class="img-polaroid"';
						$avatar = JblanceHelper::getThumbnail($bid->user_id, $attrib);
						echo !empty($avatar) ? LinkHelper::GetProfileLink($bid->user_id, $avatar) : '&nbsp;'; ?>
        </div><?php */?>
        <div class="">
          <h5 class="media-heading"> <?php echo LinkHelper::GetProfileLink(intval($bid->user_id), $this->escape($bid->$nameOrUsername)); ?> </h5>
      <?php /*?>    <p><?php echo ($bid->details) ? $bid->details : JText::_('COM_JBLANCE_DETAILS_NOT_PROVIDED'); ?></p><?php */?>
        </div>
     <?php /*?>   <div class="span3">
          <?php $rate = JblanceHelper::getAvarageRate($bid->user_id, true); ?>
        </div><?php */?>
      <?php /*?>  <div class="span2">
          <div class="text-center"> <span class="font20"><?php echo JblanceHelper::formatCurrency($bid->amount, true, false, 0); ?></span><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?><br>
            <span class="font12">
            <?php if($row->project_type == 'COM_JBLANCE_FIXED') : ?>
            <?php echo $bid->delivery; ?> <?php echo JText::_('COM_JBLANCE_BID_DAYS'); ?>
            <?php elseif($row->project_type == 'COM_JBLANCE_HOURLY') : 
				                        	$commitment = new JRegistry;
				                        	$commitment->loadString($row->commitment);
		                        	?>
            <?php echo $bid->delivery; ?> <?php echo JText::_('COM_JBLANCE_HOURS_PER').' '.JText::_($commitment->get('interval')); ?>
            <?php endif; ?>
            </span><br>
            <?php if($bid->status == 'COM_JBLANCE_ACCEPTED') : ?>
            <span class="label label-success"><?php echo JText::_($bid->status); ?></span>
            <?php elseif($bid->status == 'COM_JBLANCE_DENIED') : ?>
            <span class="label label-important"><?php echo JText::_($bid->status); ?></span>
            <?php endif; ?>
          </div>
        </div><?php */?>
      </li>

      <?php 
					}
				}
				?>
      <?php endif; ?>
      </ul>
      
    </div>

</div>
<div class="col-md-4 trs-none">
  <div class=" shopper-info ">
    <div class="media">
      <div class="col-md-3">
        <?php /*?><?php 
				$attrib = 'width=105 height=105 class="img-polaroid"';
				$avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
				echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar, '', '', '') : '&nbsp;'; ?><?php */?>
      </div>
      <div class="col-md-5">
        <?php /*?>   <div class="media-heading"> <strong><?php echo JText::_('COM_JBLANCE_POSTED_BY'); ?> :</strong> </div><?php */?>
        <h4>
          <?php echo $this->escape($publisher->$nameOrUsername); ?>
        </h4>

       <?php /*?> <?php echo LinkHelper::GetProfileLink($row->publisher_userid, $this->escape($publisher->$nameOrUsername));?><?php */?>

          <div class="rating"><?php JblanceHelper::getAvarageRate($row->publisher_userid); ?></div>

      </div>
    </div>
  </div>
  <div class="box-border-ct">
    <ul class="promotions" >
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
    <div class="clearfix"></div>
    <div class="row-fluid">
      <div class=" pro-attr">
        <ul>
<li>
          <div class="row-t">  <?php echo JText::_('COM_JBLANCE_BIDS'); ?></div>
    <div class="row-b">
              <?php if($sealProjectBids || $row->is_sealed) : ?>
              <span class="label label-info"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
              <?php else : ?>
              <?php echo count($this->bids); ?>
              <?php endif; ?></div>
</li>


         <li>   <div class="row-t"><?php echo JText::_('COM_JBLANCE_AVG_BID').' ('.$currencycode.')'; ?>
  </div>
              <div class="row-b"><?php
							$projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
							$avg = $projHelper->averageBidAmt($row->id);
							$avg = round($avg, 0);
							 ?>
              <?php if($sealProjectBids || $row->is_sealed) : ?>
              <span class="label label-info"><?php echo JText::_('COM_JBLANCE_SEALED'); ?></span>
              <?php else : ?>
              <?php echo JblanceHelper::formatCurrency($avg, true, 0, 0); ?><span class="font14"><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?></span>
              <?php endif; ?></div>
</li>

   <li>
           <div class="row-t"> <?php echo JText::_('COM_JBLANCE_BUDGET').' ('.$currencycode.')'; ?></div>
          <div class="row-b">  <?php echo JblanceHelper::formatCurrency($row->budgetmin, true, false, 0); ?> - <?php echo JblanceHelper::formatCurrency($row->budgetmax, true, false, 0); ?><span><?php echo ($row->project_type == 'COM_JBLANCE_HOURLY') ? ' / '.JText::_('COM_JBLANCE_HR') : ''; ?></span> </div>
    </li>
    
    <li>
            <?php if($row->status == 'COM_JBLANCE_OPEN') : ?>
            <div > <?php echo JblanceHelper::showRemainingDHM($expiredate, 'LONG', 'COM_JBLANCE_PROJECT_EXPIRED_LONG'); ?> </div>
            <?php endif; ?>
            <div  class="label label-success"><?php echo strtoupper(JText::_($row->status)); ?></div>
    
    </li>
    
        </ul>
      </div>

    </div>
    
    
  </div>
  
    

  
   <div class="bid-on-wrap"> 
    <!-- show the bid button only if the status is OPEN & not expired -->
    <?php if($row->status == 'COM_JBLANCE_OPEN' && !$isMine) : ?>
    <?php $link_place_bid = JRoute::_( 'index.php?option=com_jblance&view=project&layout=placebid&id='.$row->id); ?>
    <a href="<?php echo $link_place_bid; ?>" class="abtn"><?php echo JText::_('COM_JBLANCE_BID_ON_THIS_PROJECT'); ?></a>
    <?php endif; ?>
  </div>
  

    
  
  
  <div class="page-actions">
    <?php if($enableAddThis) : ?>
    <div id="social-bookmark" class="page-action pull-left"> 
      <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox addthis_default_style "> <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_google_plusone" g:plusone:size="medium"></a> <a class="addthis_counter addthis_pill_style"></a> </div>
      <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script> 
      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo $addThisPubid; ?>"></script> 
      <!-- AddThis Button END --> 
    </div>
    <?php endif; ?>
    <!-- show Edit Project and Pick User only to publisher -->
    <?php if($isMine) : 
				if($row->status == 'COM_JBLANCE_OPEN' || $row->status == 'COM_JBLANCE_EXPIRED'){ ?>
    <div id="edit-project" class="page-action"> <a href="<?php echo $link_edit_project; ?>"><i class="icon-edit"></i> <?php echo JText::_('COM_JBLANCE_EDIT_PROJECT'); ?></a> </div>
    <?php } ?>
    <!-- show Pick User if bids>0 and status=open -->
    <?php if(($row->status == 'COM_JBLANCE_OPEN' || $row->status == 'COM_JBLANCE_EXPIRED') && count($this->bids) > 0) :?>
    <div id="pick-user" class="page-action"> <a href="<?php echo $link_pick_user; ?>"><i class="icon-map-marker"></i> <?php echo JText::_('COM_JBLANCE_PICK_USER').' ('.count($this->bids).')'; ?></a> </div>
    <?php endif; ?>
    <?php else : ?>
    
    <?php if($enableReporting && ($user->id !=0 || $guestReporting )) : ?>
    <div id="report-this" class="page-action"> <a href="<?php echo $link_report; ?>"><i class="icon-warning-sign"></i> <?php echo JText::_('COM_JBLANCE_REPORT_PROJECT'); ?></a> </div>
    
    <div id="send-message" class="page-action">
      <?php $link_sendpm = JRoute::_('index.php?option=com_jblance&view=message&layout=compose&username='.$publisher->username.'&subject='.$row->project_title); ?>
      <a href="<?php echo $link_sendpm; ?>"><i class="icon-comment"></i> <?php echo JText::_('COM_JBLANCE_SEND_MESSAGE'); ?></a> </div>
      
      
    <?php endif; ?>
    <?php endif; ?>
    <!-- end of ismine if --> 
  </div>
</div>

<!-- </form> -->