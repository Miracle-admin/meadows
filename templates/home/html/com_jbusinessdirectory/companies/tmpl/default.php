<?php /*------------------------s------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once 'header.php';
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php'; 
?>

<?php 
require_once 'breadcrumbs.php';
?>
<?php require_once JPATH_COMPONENT_SITE."/include/social_share.php"?>

<div class="company-details-tabs">
<div class="company-name">
	<h1>
		<?php  echo isset($this->company->name)?$this->company->name:"" ; ?>	
	</h1>
</div>
<div class="clear"></div>
<div class="row-fluid">
	<div id="company-info" class="dir-company-info span4">
		<?php if(isset($this->package->features) && in_array(SHOW_COMPANY_LOGO,$this->package->features) || !$appSettings->enable_packages){ ?>
			<div class="company-image">
				<?php if(isset($this->company->logoLocation) && $this->company->logoLocation!=''){?>
					<img title="<?php echo $this->company->name?>" alt="<?php echo $this->company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->company->logoLocation ?>">
				<?php }else{ ?>
					<img title="<?php echo $this->company->name?>" alt="<?php echo $this->company->name?>" src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName().'/assets/no_image.jpg' ?>">
				<?php } ?>
				<?php if($this->appSettings->enable_bookmarks) { ?>
					<div id="bookmark-container">
					<?php if(!empty($company->bookmark)){?>
						<a href="javascript:showUpdateBookmarkDialog()"  title="<?php echo JText::_("LNG_UPDATE_BOOKMARK")?>" class="bookmark "><i class="dir-icon-heart"></i></a>
					<?php }else{?>
						<a href="javascript:showAddBookmarkDialog()"  title="<?php echo JText::_("LNG_ADD_BOOKMARK")?>" class="bookmark "><i class="dir-icon-heart-empty"></i></a>
					<?php } ?>
				<?php } ?>
			</div>
			</div>
		<?php } ?>
		<div class="company-info-container" >
			<div class="company-info-rating" itemscope itemtype="http://data-vocabulary.org/Review-aggregate" <?php echo !$appSettings->enable_ratings? 'style="display:none"':'' ?>>
			 	<span style="display:none" itemprop="itemreviewed"><?php echo $this->company->name?></span>
			 	 <span style="display:none" itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">
			      	<span itemprop="average"><?php echo $this->company->averageRating?></span>  <span itemprop="best">5</span>
			    </span>
	    		<span  style="display:none" itemprop="count"><?php echo count($this->reviews)?></span>
	    
				<div class="company-info-average-rating">
					<div class="rating">
						<div id="rating-average" title="<?php echo $this->company->averageRating?>"></div>
					</div>
					<p class="rating-text">
					 <?php echo JText::_('LNG_AVG_OF') ?> <span id="average-rating-count"> <span id="rateNumber<?php echo $this->company->id?>" itemprop="votes"> <?php echo $this->ratingCount ?> </span> <?php echo JText::_('LNG_RATINGS') ?></span></p>
				</div>
				<div class="company-info-user-rating">
					<div class="rating">
						<div id="rating-user"></div>
					</div>
					<p class="rating-text">  <span id="average-rating-count" > <?php echo JText::_('LNG_YOUR_RATING') ?></span></p>
				</div>
			</div>
			
			<div class="company-info-review" <?php echo !$appSettings->enable_reviews? 'style="display:none"':'' ?>>
				<div style="display:none" class="login-awareness tooltip">
								<div class="arrow">»</div>
								<div class="inner-dialog">
								<a href="javascript:void(0)" class="close-button" onclick="jQuery(this).parent().parent().hide()"><?php echo JText::_('LNG_CLOSE') ?></a>
								<p>
								<strong><?php echo JText::_('LNG_INFO') ?></strong>
								</p>
								<p>
									<?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?>
								</p>
								<p>
									<a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url)); ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a>
								</p>
								</div>
				</div>
				<p class="review-count">
					<?php if(count($this->reviews)){ ?> 
					 <a  href="javascript:void(0)" onclick="jQuery('#dir-tab-3').click()"><?php echo count($this->reviews)?> <?php echo JText::_('LNG_REVIEWS') ?></a>
						 <?php if(!$appSettings->enable_reviews_users || $user->id !=0) {?>
						&nbsp;|&nbsp;
						<a href="javascript:void(0)" onclick="addNewReviewTabs()"> <?php echo JText::_('LNG_WRITE_REVIEW') ?></a>
						<?php }?>
					<?php } else{ ?>
					<a href="javascript:void(0)" onclick="addNewReviewTabs()" <?php echo ($appSettings->enable_reviews_users && $user->id ==0) ? 'style="display:none"':'' ?>><?php echo JText::_('LNG_BE_THE_FIRST_TO_REVIEW') ?></a>
					<?php }?>
				</p>
			</div>
			
			<?php if(!empty($this->company->slogan)){?>
				<p class="business-slogan"><?php echo  $this->company->slogan ?> </p>
			<?php }?>
			
			<div>		
				<div id="company-info-details" class="company-info-details">
					<h4 class="contact"><?php echo JText::_('LNG_CONTACT') ?></h4>
					
					<ul class="company-contact">
						<li>
							<span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
								<i class="dir-icon-map-marker"></i> <?php echo JBusinessUtil::getAddressText($this->company) ?>
							</span>
						</li>
						<li>
							<?php if( $showData && isset($this->package->features) && in_array(PHONE, $this->package->features) || !$appSettings->enable_packages ) { ?>
								<?php if(!empty( $this->company->phone)) { ?>
									<span itemprop="tel">
										<i class="dir-icon-phone"></i> <a href="tel:<?php  echo $this->company->phone; ?>"><?php  echo $this->company->phone; ?></a>
									</span>
								<?php } ?>
									
								<?php if(!empty( $this->company->mobile)) { ?>
									<span itemprop="tel">
										<i class="dir-icon-mobile-phone"></i> <a href="tel:<?php  echo $this->company->mobile; ?>"><?php  echo $this->company->mobile; ?></a>
									</span>
								<?php } ?>
									
								<?php if(!empty( $this->company->fax)) {?>
									<span itemprop="fax">
										<i class="dir-icon-fax"></i> <?php echo $this->company->fax ?>
									</span>
								<?php } ?>
							<?php } ?>
						</li>
						<li>
							<?php if(!empty( $this->company->email) && $showData && $appSettings->show_email){?>
								<span itemprop="email">
									 <i class="dir-icon-envelope"></i> <?php echo $this->company->email?>
								</span>
							<?php } ?>
						</li>
						<li>
							<?php if(($showData && isset($this->package->features) && in_array(WEBSITE_ADDRESS,$this->package->features) || !$appSettings->enable_packages) && !empty($company->website)){ ?>
								<span itemprop="url">
									<i class="dir-icon-globe"></i>	<a title="<?php echo $this->company->name?> Website" target="_blank" rel="nofollow"  href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$company->id) ?>"> <?php echo $company->website ?></a>
								</span>
							<?php }?>
						</li>
						<li>
							<?php if(!empty($company->contact_name)){ ?>
								<div id="contact-person-details" class="contact-person-details">
									<div onclick="jQuery('#contact-person-details').toggleClass('open')"><strong ><?php echo JText::_('LNG_CONTACT_PERSON') ?>: </strong> <?php echo $company->contact_name?> (+)</div> 
									<ul>
										<?php if(!empty($company->contact_phone) || !empty($company->contact_fax)){?>
											<li><i class="dir-icon-phone"></i> <a href="tel:<?php  echo $this->company->phone; ?>"><?php echo $company->contact_phone?></a> &nbsp;&nbsp; <i class="dir-icon-fax"></i> <?php echo $company->contact_fax?></li>
										<?php }?>
										<?php if(!empty($company->contact_email)){ ?>
											<li><i class="dir-icon-envelope"></i> <?php echo $company->contact_email?></li>
										<?php }?>
									</ul>
								</div>
							<?php } ?>
						</li>
					</ul>
						
					<div class="classification">
						<div class="categories">
							<?php if(isset($this->company->typeName)){ ?>
							<?php echo JText::_('LNG_TYPE')?>: <span><?php echo $this->company->typeName?></span>
							<?php } ?>	
						</div>
					</div>
					
					<?php if(!empty($this->company->categoryIds)){?>
						<div class="classification">
							<div>
								<ul class="business-categories">
									<li><?php echo JText::_('LNG_CATEGORIES')?>:&nbsp;</li>
								<?php 
									$categoryIds = explode(',',$this->company->categoryIds);
									$categoryNames =  explode('#',$this->company->categoryNames);
									$categoryAliases =  explode('#',$this->company->categoryAliases);
									
									for($i=0;$i<count($categoryIds);$i++){
										?>
											<li><a rel="nofollow" href="<?php echo JBusinessUtil::getCategoryLink($categoryIds[$i],  $categoryAliases[$i]) ?>"><?php echo $categoryNames[$i]?><?php echo $i<(count($categoryIds)-1)? ',&nbsp;':'' ?></a> </li>
									<?php }	?>
								</ul>
							</div>
						</div>
					<?php } ?>
					
					<div class="clear"></div>

					<?php if( $showData && isset($this->package->features) && in_array(ATTACHMENTS, $this->package->features) || !$appSettings->enable_packages ) { ?>
						<?php if(!empty($this->company->attachments)) { ?>
							<div class="attachments">
								<ul>
									<?php foreach($this->company->attachments as $attachment) { ?>	
										<li><a href="<?php echo JURI::root()."/".ATTACHMENT_PATH.$attachment->path?>"><?php echo !empty($attachment->name)?$attachment->name:basename($attachment->path)?></a> </li>
									<?php }?>
								</ul>
							</div>
							<div class="clear"></div>
						<?php } ?>
					<?php } ?>

					<div class="custom-fields">
						<?php 
							// to do fix warning
							$renderedContent = AttributeService::renderAttributesFront($this->companyAttributes,$appSettings->enable_packages, $this->package->features);
							echo $renderedContent;
						?>
					</div>
					<?php require_once 'listing_social_networks.php'; ?>
					
					<?php if(($showData && isset($this->package->features) && in_array(CONTACT_FORM,$this->package->features) || !$appSettings->enable_packages) && !empty($company->email)){ ?>
							<button type="button" class="ui-dir-button" onclick="showContactCompany()">
								<span class="ui-button-text"><i class="dir-icon-edit"></i><?php echo JText::_("LNG_CONTACT_COMPANY")?></span>
							</button>
					<?php } ?>
				</div>
			</div>
		</div>
			<div style="display:none" class="login-awareness tooltip" id="claim-login-awarness">
				<div class="arrow">»</div>
				<div class="inner-dialog">
					<a href="javascript:void(0)" class="close-button" onclick="jQuery(this).parent().parent().hide()"><?php echo JText::_('LNG_CLOSE') ?></a>
					<p>
						<strong><?php echo JText::_('LNG_INFO') ?></strong>
					</p>
					<p>
						<?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?>
					</p>
					<p>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url)); ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a>
					</p>
				</div>
			</div>
		<div class="clear"></div>
		
		
		
		<div class="clear"></div>
		
		<?php if((!isset($this->company->userId) || $this->company->userId == 0) && $appSettings->claim_business){ ?>
		<div class="claim-container" id="claim-container">
			<div class="claim-btn">
				<a href="javascript:claimCompany()"><?php echo JText::_('LNG_CLAIM_COMPANY')?></a>
			</div>
		</div>
		<?php  } ?>
	</div>
	<div id="tab-panel" class="dir-tab-panel span8">
		<div id="tabs" class="clearfix">
			<ul class="tab-list">
				<?php
					$tabs = array();
					$tabs[1]=JText::_('LNG_BUSINESS_DETAILS');
					if((isset($this->package->features) && in_array(GOOGLE_MAP,$this->package->features) || !$appSettings->enable_packages ) 
							&& !empty($this->company->latitude) && !empty($this->company->longitude)){ 
						$tabs[2]=JText::_('LNG_MAP');
					}
					if($appSettings->enable_reviews){
						$tabs[3]=JText::_('LNG_REVIEWS');
					}
					if((isset($this->package->features) && in_array(IMAGE_UPLOAD,$this->package->features) || !$appSettings->enable_packages)
							&& !empty($this->pictures)){
						$tabs[4]=JText::_('LNG_GALLERY');
					}
					if((isset($this->package->features) && in_array(VIDEOS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->videos) && count($this->videos)>0){
						$tabs[5]=JText::_('LNG_VIDEOS');
					}
					if((isset($this->package->features) && in_array(COMPANY_OFFERS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->offers) && count($this->offers) && $appSettings->enable_offers){
						$tabs[6]=JText::_('LNG_OFFERS');
					}
					
					if((isset($this->package->features) && in_array(COMPANY_EVENTS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->events) && count($this->events) && $appSettings->enable_events){
						$tabs[7]=JText::_('LNG_EVENTS');
					}
					
					if(!empty($this->company->locations)){
						$tabs[8]=JText::_('LNG_COMPANY_LOCATIONS');
					}
					
					if(!empty($this->company->business_hours)){
						$tabs[9]=JText::_('LNG_OPENING_HOURS');
					}
					
					foreach($tabs as $key=>$tab){
					?>
						<li class="dir-dir-tabs-options"><span id="dir-tab-<?php echo $key?>"  onclick="showDirTab('#tabs-<?php echo $key?>')" class="track-business-details"><?php echo $tab?></span></li>
					<?php } ?>
			</ul>
		
		
			<div id="tabs-1" class="dir-tab ui-tabs-panel">
				<?php require_once 'details.php'; ?>
			</div>
			
			<?php if((isset($this->package->features) && in_array(GOOGLE_MAP,$this->package->features) || !$appSettings->enable_packages ) 
							&& isset($this->company->latitude) && isset($this->company->longitude)){ 
			?>
			<div id="tabs-2" class="dir-tab ui-tabs-panel">
				<?php 
					if(isset($this->company->latitude) && isset($this->company->longitude) && $this->company->latitude!='' && $this->company->longitude!='')
						require_once 'map.php';
					else
						echo JText::_("LNG_NO_MAP_COORDINATES_DEFINED");
				?>
			</div>
			<?php } ?>
			
			<?php if($appSettings->enable_reviews){ ?>
				<div id="tabs-3" class="dir-tab ui-tabs-panel">
					<?php require_once 'reviews.php'; ?>
				</div>
			<?php }?>
			<?php 
				if((isset($this->package->features) && in_array(IMAGE_UPLOAD,$this->package->features) || !$appSettings->enable_packages)
					&& !empty($this->pictures)){ 
			?>
			<div id="tabs-4" class="dir-tab ui-tabs-panel">
				<?php require_once JPATH_COMPONENT_SITE.'/include/image_gallery.php'; ?>
					
			</div>
			<?php } ?>
			
			<?php 
				if((isset($this->package->features) && in_array(VIDEOS,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->videos) && count( $this->videos)>0){	
			?>
			<div id="tabs-5" class="dir-tab ui-tabs-panel">
				<?php require_once 'companyvideos.php'; ?>
			</div>	
			<?php } ?>
			
			<?php 
				if((isset($this->package->features) && in_array(COMPANY_OFFERS,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->offers) && count($this->offers) && $appSettings->enable_offers){
			?>
			<div id="tabs-6" class="dir-tab ui-tabs-panel">
				<?php require_once 'companyoffers.php'; ?>
			</div>
			<?php } ?>
			
			<?php 
				if((isset($this->package->features) && in_array(COMPANY_EVENTS,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->events) && count($this->events) && $appSettings->enable_events){
			?>
			<div id="tabs-7" class="dir-tab ui-tabs-panel">
				<?php require_once 'events.php'; ?>
			</div>
			<?php } ?>
			
			<?php if(!empty($this->company->locations)){ ?>
				<div id="tabs-8" class="dir-tab ui-tabs-panel">
					<?php require_once 'locations.php'; ?>	
				</div>
			<?php } ?>
			
			<?php if(!empty($this->company->business_hours)){ ?>
				<div id="tabs-9" class="dir-tab ui-tabs-panel">
					<?php require_once 'business_hours.php'; ?>	
				</div>
			<?php } ?>
	</div>
	</div>
</div >
<div class="clear"></div>

<form name="tabsForm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" id="tabsForm" method="post">
 	 <input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" value="companies.displayCompany" /> 
	 <input type="hidden" name="tabId" id="tabId" value="<?php echo $this->tabId?>" /> 
	 <input type="hidden" name="view" value="companies" /> 
	 <input type="hidden" name="layout2" id="layout2" value="" /> 
	 <input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
	 <input type="hidden" name="controller"	value="<?php echo JRequest::getCmd('controller', 'J-BusinessDirectory')?>" />
</form>
</div>
<script>
jQuery(document).ready(function(){
	jQuery( "#tabs" ).tabs();

	jQuery("#dir-tab-2").click(function(){
		loadScript();
	});

	jQuery(".dir-tabs-options").click(function(){
		jQuery(".dir-tabs-options").each(function(){
			jQuery(this).removeClass("ui-state-active");
		});
		jQuery(this).addClass("ui-state-active");
	});

	jQuery("#dir-tab-<?php echo $this->tabId ?>").click(); 
});   

</script>

<?php require_once 'company_util.php'; ?>