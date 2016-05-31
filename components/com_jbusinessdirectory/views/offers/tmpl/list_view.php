<div id="offer-list-view" class='offer-container <?php echo $fullWidth ?'full':'noClass' ?>' <?php echo $this->appSettings->offers_view_mode?'style="display: none"':'' ?>>
	<ul class="offer-list">
	<?php 
		if(isset($this->offers) && count($this->offers)>0){
			foreach ($this->offers as $offer){ ?>
				<li>
					<div class="offer-box row-fluid <?php echo !empty($offer->featured)?"featured":"" ?>">
						<div class="offer-img-container span3">
							<a class="offer-image"
								href="<?php echo $offer->link ?>">
								
								<?php if(isset($offer->picture_path) && $offer->picture_path!=''){?>
									<img  alt="<?php ?>" src="<?php echo JURI::root()."/".PICTURES_PATH.$offer->picture_path?>"> &nbsp;</a>
								<?php }else{?>
									<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
								<?php } ?>
						</div>
						<div class="offer-content span9">
							<div class="offer-subject">
								<a title="<?php echo $offer->subject?>"
									href="<?php echo $offer->link ?>"><?php echo $offer->subject?>
								</a>
							</div>
							<div class="offer-location">
								<span itemprop="address"><i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($offer)?></span>
							</div>
							<div class="offer-dates">
								<i class="dir-icon-calendar"></i>
								<?php 
									echo  JBusinessUtil::getDateGeneralFormat($offer->startDate)." - ". JBusinessUtil::getDateGeneralFormat($offer->endDate);
								?>
							</div>
					
							<div class="offer-categories">
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
							</div>
							
							<div class="offer-desciption">
								<?php echo $offer->short_description ?>
							</div>
						</div>
						<?php if(isset($offer->featured) && $offer->featured==1){ ?>
							<div class="featured-text">
								<?php echo JText::_("LNG_FEATURED")?>
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</li>
			<?php }
		}else{
			echo JText::_("LNG_NO_OFFER_FOUND");
		} ?>
	</ul>
</div>