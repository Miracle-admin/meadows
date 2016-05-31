<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 March 2012
 * @file name	:	views/project/tmpl/searchproject.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Search projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHtml::_('bootstrap.tooltip');
 JHtml::_('formbehavior.chosen', '#id_categ', null, array('placeholder_text_multiple'=>JText::_('COM_JBLANCE_FILTER_PROJECT_BY_SKILLS')));//JHtmlFormbehavior::chosen()
 JHtml::_('formbehavior.chosen', '#id_location', null, array('placeholder_text_multiple'=>JText::_('COM_JBLANCE_FILTER_PROJECT_BY_SKILLS')));//JHtmlFormbehavior::chosen()
 
 $doc 	 = JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/btngroup.js");
 $doc->addScript("components/com_jblance/js/bootstrap-slider.js");
 $doc->addStyleSheet("components/com_jblance/css/slider.css");
 
 $app  		 = JFactory::getApplication();
 $user		 = JFactory::getUser();
 $model 	 = $this->getModel();
 $now 		 = JFactory::getDate();
 $projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $select 	 = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 $userHelper = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 
 $keyword	  = $app->input->get('keyword', '', 'string');
 $phrase	  = $app->input->get('phrase', 'any', 'string');
 $order	  = $app->input->get('order', 'popular', 'string');
 $id_categ	  = $app->input->get('id_categ', array(), 'array');
 $id_location = $app->input->get('id_location', array(), 'array');
 $proj_type	  = $app->input->get('project_type', array('fixed' => 'COM_JBLANCE_FIXED', 'hourly' => 'COM_JBLANCE_HOURLY'), 'array');
 $budget 	  = $app->input->get('budget', '', 'string');
 $status	  = $app->input->get('status', 'COM_JBLANCE_OPEN', 'string');
 
 $config 		  = JblanceHelper::getConfig();
 $currencysym 	  = $config->currencySymbol;
 $currencycode 	  = $config->currencyCode;
 $dformat 		  = $config->dateFormat;
 $sealProjectBids = $config->sealProjectBids;
 
 $action = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 
 
 $defaultChecked = $this->defaultChecked;
 //echo "<pre>"; print_r($id_categ);
 if(empty($id_categ))
 {
	$id_categ = $defaultChecked;
 }

?>
<script type="text/javascript">
<!--
	jQuery(document).ready(function() {
		jQuery("#budget").slider({});
		jQuery(".location-remove a").removeClass("chzn-single");
		
	});
//-->
</script>

<form action="<?php echo $action; ?>" method="get" name="userFormJob" enctype="multipart/form-data">
  <div class="col-md-3 project-filter-lf">
  <h4>Project Filter</h4>
    <h5>Project Category</h5>
    <?php /*?>     <div id="filter-bar" class="btn-toolbar">
          <div class="filter-search btn-group pull-left">
            <input type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>" class="input-xlarge hasTooltip" placeholder="<?php echo JText::_('COM_JBLANCE_SEARCH_KEYWORD_TIPS'); ?>" />
          </div>
          <div class="btn-group pull-left">
            <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('COM_JBLANCE_SEARCH'); ?>"><i class="icon-search"></i>Search</button>
            <a href="<?php echo $action; ?>" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></a> </div>
        </div><?php */?>
    <?php /*?>      <div class="">
        <?php $list_phrase = $select->getRadioSearchPhrase('phrase', $phrase);	   					   		
			echo $list_phrase; ?>
      </div><?php */?>
    <div class="selct-cate">
      <?php //echo"<pre>"; print_r($id_categ); ?>
      <div class="srch-row">
        <?php 
			//echo "<pre>"; print_r($attribs);
			$attribs = "";// "class='input-block-level required' size='5' MULTIPLE";
			echo $select->getCheckBoxCategoryTree('id_categ[]', $id_categ,$attribs); ?>
      </div>
    </div>
    
    <!--<div class="">
			<?php 
			//$attribs = "class='input-block-level required' size='5' MULTIPLE";
			//echo $select->getCheckCategoryTree('id_categ[]', $id_categ,$attribs); ?>
	</div>
		
	<div class="">
			<?php 
			//$attribs = "class='input-block-level required' size='5' MULTIPLE";
			//echo $select->getSelectCategoryTree('id_categ[]', $id_categ, '', $attribs, '', true); ?>
		</div> -->
    
    <div class="p-type">
      <h5><?php echo JText::_('COM_JBLANCE_PROJECT_TYPE'); ?></h5>
      <div class="selct-cate">
        <label class="checkbox inline" for="">
          <input onclick="this.form.submit()" type="checkbox" id="fixed" name="project_type[fixed]" value="COM_JBLANCE_FIXED" <?php if(isset($proj_type['fixed'])) {echo 'checked'; } ?> >
          <?php echo JText::_('COM_JBLANCE_FIXED'); ?> </label>
        <label class="checkbox inline"> 
          <!-- <input type="hidden" name="project_type['hourly']" value="0" /> -->
          <input type="checkbox" onclick="this.form.submit()" id="hourly" name="project_type[hourly]" value="COM_JBLANCE_HOURLY" <?php if(isset($proj_type['hourly'])) {echo 'checked';} ?> >
          <?php echo JText::_('COM_JBLANCE_HOURLY'); ?> </label>
      </div>
    </div>
    <div class="p-status">
      <h5><?php echo JText::_('COM_JBLANCE_PROJECT_STATUS'); ?></h5>
      <?php 
					$attribs = "class='input-small' size='1' onchange='this.form.submit()'";
					$list_status = $select->getSelectProjectStatus('status', $status, 'COM_JBLANCE_ANY', $attribs, '');	   					   		
					echo $list_status; ?>
    </div>
    <div class="srch-row location-remove">
      <h5>Location</h5>
      <?php 
			$style = "onchange='this.form.submit()'";
			echo $select->getSelectLocationTree('id_location', $id_location, '', 'COM_JBLANCE_ALL_LOCATIONS', $style); ?>
    </div>
    <div class="p-budget">
      <h5><?php echo JText::_('COM_JBLANCE_BUDGET'); ?></h5>
      <label class="radio">
        <?php $limit = $model->getMaxMinBudgetLimit('COM_JBLANCE_FIXED'); 
						$sliderValue = (empty($budget)) ? $limit->minlimit.','.$limit->maxlimit : $budget;
						?>
        <b style="margin-right: 15px;"><?php echo JblanceHelper::formatCurrency($limit->minlimit, true, false, 0); ?></b>
        <input type="text" name="budget" id="budget" class="input-xlarge" value="<?php echo $budget; ?>" data-slider-min="<?php echo $limit->minlimit; ?>" data-slider-max="<?php echo $limit->maxlimit; ?>" data-slider-step="50" data-slider-value="[<?php echo $sliderValue; ?>]" style="display: none; margin-top: 20px;" />
        <b style="margin-left: 15px;"><?php echo JblanceHelper::formatCurrency($limit->maxlimit, true, false, 0); ?></b> </label>
    </div>
  </div>
  <div class="col-md-9"> 
    
    <!-- <div class="lineseparator"></div> -->
    <div class="npo-filter">
      <div class="jbl_h3title col-md-6">Latest Projects</div>
      <div class="fiter-new-old">
        <?php $list_order = $select->getRadioSearchOrder('order', $order);	   					   		
			echo $list_order; ?>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="clr"> 
          
          <!--Body content-->
          
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
					
					/* if($isExpired)
						$statusLabel = 'label';
					else */if($row->status == 'COM_JBLANCE_OPEN')
						$statusLabel = 'label label-success';
					elseif($row->status == 'COM_JBLANCE_FROZEN')
						$statusLabel = 'label label-warning';
					elseif($row->status == 'COM_JBLANCE_CLOSED')
						$statusLabel = 'label label-important';
					elseif($row->status == 'COM_JBLANCE_EXPIRED')
						$statusLabel = 'label';
					
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
          <div class="row projects-detail-wrapper">
            <div class="col-md-1">
              <div class="project-thumb">
                <?php
					$attrib = 'width=56 height=56 class="img-polaroid"';
					$avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
					echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar) : '&nbsp;' ?>
              </div>
            </div>
            <div class="col-md-11 project-detail">
              <h5><?php echo LinkHelper::getProjectLink($row->id, $row->project_title); ?> </h5>
              <span><?php echo JText::_('COM_JBLANCE_LOCATION'); ?> : <?php echo JblanceHelper::getLocationNames($row->id_location); ?></span>
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
              
              <p><?php //echo '<pre>'; print_r($row); die; 
              echo (strlen($row->description) > 130) ? substr($row->description,0,130).'...' : $row->description;; ?></p>
              <span class="sklill"> <?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?></span>
              
              <ul class="bid-footer">
              <li>Budget $<?php echo $row->budgetmin.' - $'.$row->budgetmax; ?></li>
              <li>Total Bid
              
               <sub class="tol-bid"> <?php if($sealProjectBids || $row->is_sealed) : ?>
                <?php echo JText::_('COM_JBLANCE_SEALED'); ?>
                <?php else : ?>
                <?php echo $bidsCount; ?>
                <?php endif; ?>
                
                </sub>
                
                </li>
              <li><?php echo JText::_('COM_JBLANCE_STATUS'); ?><sub class="<?php echo $statusLabel; ?>"><?php echo JText::_($row->status); ?></sub></li>
            </ul>
              <div class="clearfix "> <a href="<?php ?>" class="abtn">Bid Now</a>
              
              </div>
            </div>
            
          </div>
          <?php 
				}
				?>
          <?php if(!count($this->rows)){ ?>
          <div class="alert alert-info"> <?php echo JText::_('COM_JBLANCE_NO_MATCHING_RESULTS_FOUND'); ?> </div>
          <?php } ?>
          <div class="pagination pagination-centered clearfix">
            <div class="display-limit pull-right"> <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160; <?php echo $this->pageNav->getLimitBox(); ?> </div>
            <?php echo $this->pageNav->getPagesLinks(); ?> </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="option" value="com_jblance" />
  <input type="hidden" name="view" value="project" />
  <input type="hidden" name="layout" value="searchproject" />
  <input type="hidden" name="task" value="" />
</form>
