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

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$user = JFactory::getUser();

$showData = !($user->id==0 && $appSettings->show_details_user == 1);

?>

<!-- layout -->
<div id="layout" class="pagewidth clearfix grid4 grid-view-1" <?php echo !$this->appSettings->offers_view_mode?'style="display: none"':'' ?>>

<div id="grid-content">
	<div id="loops-wrapper" class="loops-wrapper infinite-scrolling AutoWidthElement">

	<?php 
	if(!empty($this->offers)){
		foreach($this->offers as $index=>$offer){
		?>

		<article id="post-<?php echo  $offer->id ?>" class="post post type-post status-publish format-standard hentry category-food post clearfix ">
			<div class="post-inner">
				<figure class="post-image ">
						<a href="<?php echo $offer->link ?>">
							<?php if(!empty($offer->picture_path) ){?>
								<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.$offer->picture_path ?>">
							<?php }else{ ?>
								<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
							<?php } ?>
						</a>
				</figure>
				
				<div class="post-content">
					<h1 class="post-title"><a href="<?php echo  $offer->link ?>"><?php echo $offer->subject?></a></h1>
					<span class="post-date" ><span itemprop="address"><?php echo JBusinessUtil::getLocationText($offer)?></span></span>
					
					<p class="offer-dates">
						<?php 
							echo JBusinessUtil::getDateGeneralShortFormat($offer->startDate)." - ".JBusinessUtil::getDateGeneralShortFormat($offer->endDate);
						?>
					</p>
					
					<?php if(!empty($offer->categoryIds)){ ?>
					<p class="company-clasificaiton">
						<span class="post-category">
							<?php 
									$categoryIds = explode(',',$offer->categoryIds);
									$categoryNames = explode('#',$offer->categoryNames);
									$categoryAliases = explode('#',$offer->categoryAliases);
									for($i=0;$i<count($categoryIds);$i++){
										?>
											 <a rel="nofollow" href="<?php echo JBusinessUtil::getOfferCategoryLink($categoryIds[$i], $categoryAliases[$i]) ?>"><?php echo $categoryNames[$i]?><?php echo $i<(count($categoryIds)-1)? ',&nbsp;':'' ?> </a>
										<?php 
									}
								?>
						</span> <br/>
					</p>
					<?php } ?>
					<p>
						<?php echo $offer->short_description?>
					</p>
					
					
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
