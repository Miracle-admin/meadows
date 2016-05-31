<?php /*------------------------------------------------------------------------
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

<div id="one-page-container" class="one-page-container">
	<?php require_once JPATH_COMPONENT_SITE."/include/social_share.php"?>
	<div class="company-name">
		<h1>
			<?php  echo isset($this->company->name)?$this->company->name:"" ; ?>	
		</h1>
	</div>
	<div class="clear"></div>

	<div class="row-fluid" style="margin-bottom: 0">
		<div id="company-info" class="company-info span8">
			<?php if(isset($this->package->features) && in_array(SHOW_COMPANY_LOGO,$this->package->features) || !$appSettings->enable_packages){ ?>
			<div class="company-image">
				<?php if(isset($this->company->logoLocation) && $this->company->logoLocation!=''){?>
					<img title="<?php echo $this->company->name?>" alt="<?php echo $this->company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->company->logoLocation ?>">
				<?php }else{ ?>
					<img title="<?php echo $this->company->name?>" alt="<?php echo $this->company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
				<?php } ?>
				<?php if($this->appSettings->enable_bookmarks) { ?>
					<div id="bookmark-container">
						<?php if(!empty($company->bookmark)){?>
							<a href="javascript:showUpdateBookmarkDialog()"  title="<?php echo JText::_("LNG_UPDATE_BOOKMARK")?>" class="bookmark "><i class="dir-icon-heart"></i></a>
						<?php }else{?>
							<a href="javascript:showAddBookmarkDialog()"  title="<?php echo JText::_("LNG_ADD_BOOKMARK")?>" class="bookmark "><i class="dir-icon-heart-empty"></i></a>
						<?php } ?>
					</div>
				<?php } ?>
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
								<a href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a>
							</p>
							
						</div>
					</div>
					<p class="review-count">
						<?php if(count($this->reviews)){ ?> 
						 <a href="#reviews"><?php echo count($this->reviews)?> <?php echo JText::_('LNG_REVIEWS') ?></a>
							 <?php if(!$appSettings->enable_reviews_users || $user->id !=0) {?>
							&nbsp;|&nbsp;
							<a href="javascript:void(0)" onclick="addNewReview()"> <?php echo JText::_('LNG_WRITE_REVIEW') ?></a>
							<?php }?>
						<?php } else{ ?>
						<a href="javascript:void(0)" onclick="addNewReview()" <?php echo ($appSettings->enable_reviews_users &&  $user->id == 0) ? 'style="display:none"':'' ?>><?php echo JText::_('LNG_BE_THE_FIRST_TO_REVIEW') ?></a>
						<?php }?>
					</p>
				</div>
				
				<div>		
					<div class="company-info-details">
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
						<p>
							<span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
								<i class="dir-icon-map-marker"></i> <?php echo JBusinessUtil::getAddressText($this->company) ?>
							</span>
						</p>

						<?php if( $showData && isset($this->package->features) && in_array(PHONE, $this->package->features) || !$appSettings->enable_packages ) { ?>
							<?php if(!empty($this->company->phone)) { ?>
								<span class="phone" itemprop="tel">
									<i class="dir-icon-phone"></i> <a href="tel:<?php  echo $this->company->phone; ?>"><?php  echo $this->company->phone; ?></a>
								</span>
								<br/>
							<?php } ?>
								
							<?php if(!empty($this->company->mobile)) { ?>
								<span class="phone" itemprop="tel">
									<i class="dir-icon-mobile-phone"></i> <a href="tel:<?php  echo $this->company->mobile; ?>"><?php  echo $this->company->mobile; ?></a>
								</span>
								<br/>
							<?php } ?>
								
							<?php if(!empty($this->company->fax)) {?>
								<span class="phone">
									<i class="dir-icon-fax"></i> <?php echo $this->company->fax?>
								</span>
								<br/>
							<?php } ?>
						<?php } ?>

						<?php if(!empty( $this->company->email) && $showData && $appSettings->show_email){?>
							<span itemprop="email">
								<i class="dir-icon-envelope"></i> <?php echo $this->company->email?>
							</span>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div class="company-links">
				<ul class="features-links">
					<?php if(($showData && isset($this->package->features) && in_array(WEBSITE_ADDRESS,$this->package->features) || !$appSettings->enable_packages) && !empty($company->website)){ ?>
						<li>
							<a class="website" title="<?php echo $this->company->name?> Website" target="_blank" rel="nofollow"  href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$company->id) ?>"><?php echo JText::_('LNG_WEBSITE') ?></a>
						</li>
					<?php }?>
					<?php if((isset($this->package->features) && in_array(CONTACT_FORM,$this->package->features) || !$appSettings->enable_packages) && $showData && !empty($company->email)){ ?>
						<li>
							<a class="email" href="javascript:showContactCompany()" ><?php echo JText::_('LNG_CONTACT_COMPANY'); ?></a>
						</li>
					<?php } ?>
				</ul>
			
				<?php require_once 'listing_social_networks.php'; ?>
			</div>
		</div>
		<?php if($showData){?>
			<div class="company-map span4">
				<a href="javascript:showMap()" title="Show Map">
					<?php 
						if((isset($this->package->features) && in_array(GOOGLE_MAP,$this->package->features) || !$appSettings->enable_packages ) 
										&& !empty($this->company->latitude) && !empty($this->company->longitude)){ 
							echo '<img src="https://maps.googleapis.com/maps/api/staticmap?center='.$this->company->latitude.','.$this->company->longitude.'&zoom=13&size=200x106&markers=color:blue|'.$this->company->latitude.','.$this->company->longitude.'&sensor=false">';
						}
					?>
				</a> 	
				
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
									<a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($url)) ?>"><?php echo JText::_('LNG_CLICK_LOGIN') ?></a>
								</p>
								</div>
				</div>
				<div class="clear"></div>
				
				<?php if((!isset($this->company->userId) || $this->company->userId == 0) && $appSettings->claim_business){ ?>
				<div class="claim-container" id="claim-container">
					
					<a href="javascript:void(0)" onclick="claimCompany()">
						<div class="claim-btn">
							<?php echo JText::_('LNG_CLAIM_COMPANY')?>
						</div>
					</a>
				</div>
				<?php  } ?>
			</div>
		<?php } ?>
	</div>
	
	<div id="company-map-holder" style="display:none" class="company-cell">
		<div class="search-toggles">
			<span class="button-toggle">
				<a title="" class="" href="javascript:hideMap()"><?php echo JText::_("LNG_CLOSE_MAP")?></a>
			</span>
			<div class="clear"></div>
		</div>
		<h2><?php echo JText::_("LNG_BUSINESS_MAP_LOCATION")?></h2>
		
		<?php 
			if(isset($this->company->latitude) && isset($this->company->longitude) && $this->company->latitude!='' && $this->company->longitude!='')
				require_once 'map.php';
		?>
	</div>
	<div class="clear"></div>

	<div class="company-menu">
		<nav>
			<a id="business-link" href="javascript:showDetails('company-business');" class="active"><?php echo JText::_("LNG_BUSINESS_DETAILS")?></a>
			<?php 
				if((isset($this->package->features) && in_array(IMAGE_UPLOAD,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->pictures) && count($this->pictures)>0){ 
			?>
				<a id="gallery-link" href="javascript:showDetails('company-gallery');" class=""><?php echo JText::_("LNG_GALLERY")?></a>
			<?php } ?>
		
			<?php 
				if((isset($this->package->features) && in_array(VIDEOS,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->videos) && count( $this->videos)>0){	
			?>
					<a id="videos-link" href="javascript:showDetails('company-videos');" class=""><?php echo JText::_("LNG_VIDEOS")?></a>
			<?php } ?>
			
			<?php 
				if((isset($this->package->features) && in_array(COMPANY_OFFERS,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->offers) && count($this->offers) && $appSettings->enable_offers){
			?>
					<a id="offers-link" href="javascript:showDetails('company-offers');" class=""><?php echo JText::_("LNG_OFFERS")?></a>
			<?php } ?>
			
			<?php 
				if((isset($this->package->features) && in_array(COMPANY_EVENTS,$this->package->features) || !$appSettings->enable_packages)
					&& isset($this->events) && count($this->events) && $appSettings->enable_events){
			?>
					<a id="events-link" href="javascript:showDetails('company-events');" class=""><?php echo JText::_("LNG_EVENTS")?></a>
			<?php } ?>
			
			<?php if($appSettings->enable_reviews){ ?>
				<a id="reviews-link" href="javascript:showDetails('company-reviews');" class=""><?php echo JText::_("LNG_REVIEWS")?></a>
			<?php }?>
			
		</nav>
	</div>

	<div id="company-details" class="company-cell">
		<?php if(isset($this->company->slogan) && strlen($this->company->slogan)>2){?>
			<p class="business-slogan"><?php echo  $this->company->slogan ?> </p>
		<?php }?>
			
		<dl>
			<?php if(!empty($this->company->typeName)){?>
				<dt><?php echo JText::_('LNG_TYPE')?>:</dt>
				<dd><?php echo $this->company->typeName?></dd>
			<?php } ?>
		
			<?php if(!empty($this->company->categoryIds)){?>
				<dt><?php echo JText::_('LNG_CATEGORIES')?>:</dt>
				<dd><ul><?php 
							$categoryIds = explode(',',$this->company->categoryIds);
							$categoryNames =  explode('#',$this->company->categoryNames);
							$categoryAliases =  explode('#',$this->company->categoryAliases);
							
							for($i=0;$i<count($categoryIds);$i++){
								?>
									<li><a rel="nofollow" href="<?php echo JBusinessUtil::getCategoryLink($categoryIds[$i],  $categoryAliases[$i]) ?>"><?php echo $categoryNames[$i]?><?php echo $i<(count($categoryIds)-1)? ',&nbsp;':'' ?></a> </li>
								<?php 
							}
						?>
					</ul>
				</dd>
			<?php } ?>
			<?php if(!empty($this->company->description)){?>
				<dt><?php echo JText::_("LNG_GENERAL_INFO")?></dt>
				<dd><?php echo $this->company->description;	?></dd>
			<?php }?>
			
			<?php if(!empty($this->company->locations)){ ?>
				<dt><?php echo JText::_("LNG_COMPANY_LOCATIONS")?></dt>
				<dd><?php require_once 'locations.php';?></dd>
			<?php } ?>
			
			<?php if(!empty($this->company->business_hours)){ ?>
				<dt><?php echo JText::_("LNG_OPENING_HOURS")?></dt>
				<dd><?php require_once 'business_hours.php'; ?>	</dd>
			<?php } ?>
			
			<?php if( $showData && isset($this->package->features) && in_array(ATTACHMENTS, $this->package->features) || !$appSettings->enable_packages ) { ?>
				<?php if(!empty($this->company->attachments)) { ?>
					<dt><?php echo JText::_("LNG_ATTACHMENTS")?></dt>
					<dd>
						<div class="attachments">
							<ul>
								<?php foreach($this->company->attachments as $attachment) { ?>	
									<li><a href="<?php echo JURI::root()."/".ATTACHMENT_PATH.$attachment->path?>"><?php echo !empty($attachment->name)?$attachment->name:basename($attachment->path)?></a> </li>
								<?php }?>
							</ul>
						</div>
					</dd>
				<?php } ?>
			<?php } ?>
		</dl>
			
		<div class="classification">
			<?php 
				$packageFeatured = isset($this->package->features)?$this->package->features:null;
				$renderedContent = AttributeService::renderAttributesFront($this->companyAttributes,$appSettings->enable_packages, $packageFeatured);
				echo $renderedContent;
			?>
		</div>
			
	</div>
	<div class="clear"></div>
					
	<?php 
	if((isset($this->package->features) && in_array(IMAGE_UPLOAD,$this->package->features) || !$appSettings->enable_packages)
			&& ((isset($this->pictures) && count($this->pictures)>0) || (isset($this->videos) && count($this->videos)>0)) ){
	?>
	<div id="company-gallery" class="company-cell">
		<h2><?php echo JText::_("LNG_GALLERY")?></h2>
		<?php require_once 'gallery_slider.php';?>
	</div>
	<div class="clear"></div>
	<?php }?>
	
	<?php 
		if((isset($this->package->features) && in_array(VIDEOS,$this->package->features) || !$appSettings->enable_packages)
							&& isset($this->videos) && count( $this->videos)>0){
	?>			
		<div id="company-videos" class="company-cell">
			<h2><?php echo JText::_("LNG_VIDEOS")?></h2>
			<?php  require_once 'companyvideos.php';?>
		</div>
	<?php }	?>
	
	<?php 
	if((isset($this->package->features) && in_array(COMPANY_OFFERS,$this->package->features) || !$appSettings->enable_packages)
			&& isset($this->offers) && count($this->offers) && $appSettings->enable_offers){
	?>
		<div id="company-offers" class="company-cell">
			<h2><?php echo JText::_("LNG_COMPANY_OFFERS")?></h2>
			<?php require_once 'companyoffers.php';?>
		</div>
		<div class="clear"></div>
	<?php } ?>
	
	<?php 
	if((isset($this->package->features) && in_array(COMPANY_EVENTS,$this->package->features) || !$appSettings->enable_packages)
			&& isset($this->events) && count($this->events) && $appSettings->enable_events){
	?>
		<div id="company-events" class="company-cell">
			<h2><?php echo JText::_("LNG_COMPANY_EVENTS")?></h2>
			<?php require_once 'events.php';?>
		</div>
		<div class="clear"></div>
	<?php } ?>
	
	<?php 
	if($appSettings->enable_reviews){
	?>
		<div id="company-reviews" class="company-cell">
		<h2><?php echo JText::_("LNG_BUSINESS_REVIEWS")?></h2>
			<?php require_once 'reviews.php';?>
		</div>
		<div class="clear"></div>
	<?php } ?>
	
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

function showMap(){
	jQuery("#company-map-holder").show();
	loadScript();

}

function hideMap(){
	jQuery("#company-map-holder").hide();
}

function readMore(){
	jQuery("#general-info").removeClass("collapsed");
	jQuery(".read-more").hide();
}

function showDetails(identifier){
	var ids = ["company-details","company-gallery","company-videos" ,"company-offers","company-events","company-reviews"];
	var pos = ids.indexOf(identifier); 
	
	jQuery(".company-menu a").each(function(){
		jQuery(this).removeClass("active");
	});

	jQuery("#"+identifier+"-link").addClass("active");
	
	for(var i=0;i<pos;i++){
		jQuery("#"+ids[i]).slideUp();
	}

	for(var i=pos;i<ids.length;i++){
		jQuery("#"+ids[i]).slideDown();
	}
}
</script>
<?php require_once 'company_util.php'; ?>