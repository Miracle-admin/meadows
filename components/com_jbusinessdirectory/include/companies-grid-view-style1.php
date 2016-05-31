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
?>

<!-- layout -->
<div id="layout" class="pagewidth clearfix grid4">

<div id="grid-content">
	<div id="loops-wrapper" class="loops-wrapper infinite-scrolling AutoWidthElement row-fluid">

	<?php 
	if(isset($this->companies)){
		foreach($this->companies as $index=>$company){
		?>

		<article class="post type-post span3">
			<div class="post-inner">
				<?php if(isset($company->packageFeatures) && in_array(SHOW_COMPANY_LOGO,$company->packageFeatures) || ! $appSettings->enable_packages){ ?>
					<figure class="post-image ">
							<a href="<?php echo JBusinessUtil::getCompanyLink($company)?>">
								<?php if(isset($company->logoLocation) && $company->logoLocation!=''){?>
									<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.$company->logoLocation ?>">
								<?php }else{ ?>
									<img title="<?php echo $company->name?>" alt="<?php echo $company->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
								<?php } ?>
							</a>
					</figure>
				<?php }?>
				
				<div class="post-content">
					<h1 class="post-title"><a href="<?php echo JBusinessUtil::getCompanyLink($company)?>"><?php echo $company->name?></a></h1>
					<span class="post-date" ><span itemprop="street-address"><?php echo JBusinessUtil::getAddressText($company) ?></span></span>
					<p class="company-clasificaiton">
						<span class="post-category">
							<a rel="nofollow" href="<?php echo  JBusinessUtil::getCategoryLink($company->mainCategoryId, $company->mainCategoryAlias) ?>"><?php echo $company->mainCategory?> </a>
						</span> <br/>
						<span>
							<?php if(isset($company->typeName)){ ?>
								<?php echo $company->typeName?>
							<?php } ?>
						</span>
					</p>
					
					<p>
						<?php echo $company->slogan?>
					</p>
					
					<?php if(($showData && (isset($company->packageFeatures) && in_array(SOCIAL_NETWORKS, $company->packageFeatures) || !$appSettings->enable_packages)
							&& ((isset($company->facebook) && strlen($company->facebook)>3 || isset($company->twitter) && strlen($company->twitter)>3 || isset($company->googlep) && strlen($company->googlep)>3)))){ ?> 
					<div id="social-networks-container">
						
						<ul class="social-networks">
							<li>
								<span class="social-networks-follow"><?php echo JText::_("LNG_FOLLOW_US")?>: &nbsp;</span>
							</li>
							<?php if(isset($company->facebook) && strlen($company->facebook)>3){ ?>
							<li >
								<a title="Follow us on Facebook" target="_blank" rel="nofollow" class="share-social facebook" href="<?php echo $company->facebook ?>">Facebook</a>			
							</li>
							<?php } ?>
							<?php if(isset($company->twitter) && strlen($company->twitter)>3){ ?>
							<li >
								<a title="Follow us on Twitter" target="_blank" rel="nofollow" class="share-social twitter" href="<?php echo $company->twitter ?>">Twitter</a>			
							</li>
							<?php } ?>
							<?php if(isset($company->googlep) && strlen($company->googlep)>3){ ?>
							<li >
								<a title="Follow us on Google" target="_blank" rel="nofollow" class="share-social google" href="<?php echo $company->googlep ?>">Google</a>			
							</li>
							<?php } ?>
						</ul>
					</div>
					<?php } ?>
					
				</div>
				<!-- /.post-content -->
			</div>
		<!-- /.post-inner -->
		</article>
	<?php 
		}
		}
	 ?>	
	 <div class="clear"></div>
	</div>
</div>
</div>	

	
