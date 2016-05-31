<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$enableSEO = $appSettings->enable_seo;
$enablePackages = $appSettings->enable_packages;
$enableRatings = $appSettings->enable_ratings;
$enableNumbering = $appSettings->enable_numbering;
$user = JFactory::getUser();

$showData = !($user->id==0 && $appSettings->show_details_user == 1);
?>

<div id="results-container" <?php echo $this->appSettings->search_view_mode?'style="display: none"':'' ?> class="search-style-1">
<?php 
if(!empty($this->companies)){
	foreach($this->companies as $index=>$company){
	?>
		<div id="result" class="result <?php echo isset($company->featured) && $company->featured==1?"featured":"" ?>">
			<div class="company-counter" <?php echo !$enableNumbering? 'style="display:none"':'' ?> >
				<span>
					<?php echo $index+1 ?>
				</span>
			</div>
			<div class="business-container">
				<div class="business-info">
					<h3 class="business-name">
						<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>" ><span itemprop="name"> <?php echo $company->name?> </span></a>
					</h3>
					
					<div class="company-rating" <?php echo !$enableRatings? 'style="display:none"':'' ?>>
						<div style="display:none" class="thanks-rating tooltip">
							<div class="arrow">»</div>
							<div class="inner-dialog">
							<a href="javascript:void(0)" class="close-button"><?php echo JText::_('LNG_CLOSE') ?></a>
								<p>
								<strong><?php echo JText::_('LNG_THANKS_FOR_YOUR_RATING') ?></strong>
								</p>
								<p><?php echo JText::_('LNG_REVIEW_TXT') ?></p>
								<p class="buttons">
								<a rel="nofollow" onclick="" class="review-btn track-write-review no-tracks" href=""><?php echo JText::_("LNG_WRITE_A_REVIEW")?></a>
								<a href="javascript:void(0)" class="close-button">X <?php echo JText::_('LNG_NOT_NOW') ?></a>
								</p>
							</div>
						</div>
					
						<div style="display:none" class="rating-awareness tooltip">
							<div class="arrow">»</div>
							<div class="inner-dialog">
							<a href="javascript:void(0)" class="close-button" onclick="jQuery(this).parent().parent().hide()"><?php echo JText::_('LNG_CLOSE') ?></a>
							<strong><?php echo JText::_('LNG_INFO') ?></strong>
								<p>
									<?php echo JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN') ?>
								</p>
							</div>
						</div>
						<div class="rating">
							<p class="rating-average" title="<?php echo $company->averageRating?>" alt="<?php echo $company->id?>" style="display: block;"></p>
						</div>
						<div class="review-count">
							<a rel="nofollow"  <?php echo $company->averageRating == 0 ? 'style="display:none"':'' ?>>(<span id="rateNumber<?php echo $company->id?>"><?php echo $company->nrRatings?></span>)</a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
		
				<div class="business-details row-fluid">
					<?php if(isset($company->packageFeatures) && in_array(SHOW_COMPANY_LOGO,$company->packageFeatures) || !$enablePackages){ ?>
					<div class="company-image span3">
						<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>">
							<?php if(!empty($company->logoLocation)){?>
								<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>"/>
							<?php }else{ ?>
								<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>"/>
							<?php } ?>
						</a>
					</div>
					<?php } ?>
					
					<div class="result-content span9">
						<div class="company-info" itemtype="http://data-vocabulary.org/Organization">
							<?php if(!empty($company->comercialName)){?>
							<h3 class="business-comercial-name" style="display:none">
								<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>"> <?php echo $company->comercialName?> </a>
							</h3>
							<?php } ?>
							
							<span class="company-address" itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
								<span itemprop="address"><?php echo JBusinessUtil::getAddressText($company) ?></span>
							</span>

							<?php if( $showData && (isset($company->packageFeatures) && in_array(PHONE, $company->packageFeatures) || !$enablePackages )){ ?> 
								<?php if(!empty($company->phone)) { ?>
									<span class="phone" itemprop="tel">
										<i class="dir-icon-phone"></i> <a href="tel:<?php  echo $company->phone; ?>"><?php  echo $company->phone; ?></a>
									</span>
								<?php } ?>
							<?php } ?>
						</div>
						
						<ul class="company-features">
							<?php if($showData && !empty($company->website) && (isset($company->packageFeatures) && in_array(WEBSITE_ADDRESS,$company->packageFeatures) || !$enablePackages)){?>				
								<li><a itemprop="url" title="<?php echo $company->name?> Website" target="_blank" rel="nofollow"  href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&task=companies.showCompanyWebsite&companyId='.$company->id) ?>">Â» <?php echo JText::_('LNG_WEBSITE') ?></a></li>
							<?php } ?>
							
							<?php if(!empty($this->location) && !empty($company->latitude) && !empty($company->longitude) && $showData && (isset($company->packageFeatures) && in_array(GOOGLE_MAP,$company->packageFeatures) || !$enablePackages)){?>
								<li><a target="_blank" href="http://maps.google.com/?saddr=<?php echo $this->location["latitude"].",".$this->location["longitude"] ?>&daddr=<?php echo $company->latitude.",".$company->longitude ?>">Â» <?php echo JText::_("LNG_GET_MAP_DIRECTIONS")?></a></li>
							<?php }?>
							
							<li><a rel="nofollow" class="track-more-info no-tracks" href="<?php echo JBusinessUtil::getCompanyLink($company)?>">Â» <?php echo JText::_('LNG_MORE_INFO') ?></a></li>
						</ul>
		
						<div class="classification">
							<div class="categories">
								<?php if(!empty($company->typeName)){ ?>
								<?php echo JText::_('LNG_TYPE')?>: <span><?php echo $company->typeName?></span>
								<?php } ?>	
							</div>
						</div>
						
						<?php if(!empty($company->categoryIds)){?>
							<div class="classification">
								<div class="categories">
									<div style="float:left"><?php echo JText::_('LNG_CATEGORIES')?>:&nbsp;</div>  
									<ul class="business-categories">
									<?php 
										$categoryIds = explode(',',$company->categoryIds);
										$categoryNames = explode('#',$company->categoryNames);
										$categoryAliases = explode('#',$company->categoryAliases);
										for($i=0;$i<count($categoryIds);$i++){
											?>
												<li> <a rel="nofollow" href="<?php echo JBusinessUtil::getCategoryLink($categoryIds[$i], $categoryAliases[$i]) ?>"><?php echo $categoryNames[$i]?><?php echo $i<(count($categoryIds)-1)? ',&nbsp;':'' ?> </a></li>
											<?php 
										}
									?>
									</ul>
								</div>
							</div>
						<?php } ?>
						
						<?php if(!empty($company->customAttributes)){?>
						<div class="clear"></div>
						<div class="custom-fields">
							<?php 
								$renderedContent = AttributeService::renderAttributesFront($company->customAttributes, $enablePackages, $company->packageFeatures);
								echo $renderedContent;
							?>
						</div>
						<?php } ?>
					</div>	
				</div>
				<?php if(isset($company->featured) && $company->featured==1){ ?>
					<div class="featured-text">
						<?php echo JText::_("LNG_FEATURED")?>
					</div>
				<?php } ?>
			</div>
			
			<div class="result-actions">
				<ul>
					<li> </li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	<?php 
	
	}
}
?>

</div>