<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancelatest/tmpl/default.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 
 $show_logo = intval($params->get('show_logo', 1));
 $set_Itemid	= intval($params->get('set_itemid', 0));
 $Itemid = ($set_Itemid > 0) ? '&Itemid='.$set_Itemid : '';

 $user			  = JFactory::getUser();
 $config 		  = JblanceHelper::getConfig();
 $currencycod 	  = $config->currencyCode;
 $dformat 		  = $config->dateFormat;
 $showUsername 	  = $config->showUsername;
 $sealProjectBids = $config->sealProjectBids;
 //$vmModel=VmvendorModelDashboard::getVendorPoints();
 //echo"<pre>";
 //print_r($vmModel);
 $nameOrUsername = ($showUsername) ? 'username' : 'name';

 $document = JFactory::getDocument();
 $direction = $document->getDirection();
 $document->addStyleSheet("components/com_jblance/css/style.css");
 $document->addStyleSheet("modules/mod_jblancecategory/css/style.css");
 if($direction === 'rtl')
 	$document->addStyleSheet("components/com_jblance/css/style-rtl.css");
 
 if($config->loadBootstrap){
 	JHtml::_('bootstrap.loadCss', true, $direction);
 }

 $link_listproject = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject'.$Itemid); 

 $lang = JFactory::getLanguage();
 $lang->load('com_jblance', JPATH_SITE);
?>

<div style="clear:both"></div>
<div class="box_wrapper latest_project_wrapper">
<div class="container"> 
<h3>Projects:</h3>


<?php 
$rowCount=count($rows);
if($rowCount>0)
{
 for ($i=0, $x=count($rows); $i < $x; $i++){
 $row = $rows[$i];
 
 $isMine = ($row->publisher_userid == $user->id);
		if($row->is_private_invite){
			$invite_ids = explode(',', $row->invite_user_id);
			if(!in_array($user->id, $invite_ids) && !$isMine)
				continue;
		}
 
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
		
	$isMine = ($row->publisher_userid == $user->id);
		if($row->is_private_invite){
			$invite_ids = explode(',', $row->invite_user_id);
			if(!in_array($user->id, $invite_ids) && !$isMine)
				continue;
		}
   $expiredate = JFactory::getDate($row->start_date);
   $expiredate->modify("+$row->expires days");
   $daydiff = $row->daydiff;
   
   //days remaining
   $remaining=JblanceHelper::showRemainingDHM($expiredate, 'SHORT', 'COM_JBLANCE_PROJECT_EXPIRED_SHORT');
   
   //budget
   $budget=	JblanceHelper::formatCurrency($row->budgetmin, true, false, 0).' - '. JblanceHelper::formatCurrency($row->budgetmax, true, false, 0);
   
   //project link
   $link_proj_detail=JRoute::_( 'index.php?option=com_jblance&view=project&layout=detailproject&pid='.$row->id.$Itemid); 
   
   //project title
   $title=$row->project_title;
   
   //project description
   $description=$row->description;
   
   //project categories
   $categories=$row->categories;
   
   //project status
   
   $status=JText::_($row->status);
   
   //project location
   $location=JblanceHelper::getLocationNames($row->id_location);
   
   //project upgrades
   
   $featured=$row->is_featured;
   $urgent= $row->is_urgent;
   $assisted=$row->is_assisted;
   $private= $row->is_private;
   $sealed= $row->is_sealed;
   $nda= $row->is_nda;
   ?>
   
   
   
   <div style="width:709px;">
                
                <div class="latest_project_home">
                    
                    <a class="project_home_title" target="_blank" href="<?php echo $link_proj_detail; ?>"><?php  echo $title; ?></a>
                    
                    <div class="project_home_price">
                    	<?php echo $budget; ?>
                    </div>
                     <ul class="project_featured">
                    <?php if($featured)
					{
						?>
                   
                    <li>
                    	<span class="featured_project">Featured</span>
                    </li>
                    <?php 
					} ?>
                    <?php if($urgent)
					{
						?>
                    <li>
                    	<span class="urgent_project">Urgent</span>
                        </li>
                    <?php 
					} ?>
                    <?php if($assisted)
					{
						?>
                    <li>
                    	<span class="privtae_project">Assisted</span>
                        </li>
                  
                    <?php 
					} ?>
                    <?php if($private)
					{
						?>
                    <li>
                    	<span class="sealded_project">Private</span>
                        </li>
                   
                    <?php 
					} ?>
                     <?php if($sealed)
					{
						?>
                    <li>
                    	<span class="nda_project">Sealed</span>
                        </li>
                    
                    <?php 
					} if($nda){?>
					<li>
                    	<span class="nda_project">NDA</span>
                        </li>
					
					<?php } ?>
					</ul>
                     <div class="ho_pro_dsp">
                    	<?php  echo substr($description,0,200); ?>
                    </div>
                     <div class="time_post">
                    <?php echo $categories.' / '.$status.' for proposals / Posted '.$startdate.' / '.$remaining.' remain '.$location; ?>
                    </div>
					<div class="num_bids">
					No. of proposals: <?php echo $row->bids; ?>
					</div>
					<div class="bid_pj">
					<a href="<?php echo JUri::root();?>index.php?option=com_jblance&view=project&layout=placebid&id=<?php echo $row->id; ?>&Itemid=368"><button style="width: 654px;margin: 13px 0 0 0;padding: 5px;background: #6F6FF5;color: #fff;
">Make a proposal</button></a>
					
					</div>
                    </div>
					
                </div>
   
   
   
   
   
   
   <?php 
   

   
   }
   ?>
<a href="<?php echo Juri::root(); ?>index.php?option=com_jblance&view=project&layout=listproject&Itemid=197"><button style="width: 708px;margin: 25px 0 0 0;background: #3CDE3C;font-weight: bold;color: #fff;padding: 10px;
">View All</button></a>
<?php echo $totalFunds;
}
else{
?>
No Projects Found
<?php

}
   ?>
   
     </div></div>


