<div id="events-list-view"> 
	<ul class="event-list">
	<?php 
		if(isset($this->events) && count($this->events)>0){
			foreach ($this->events as $event){ ?>
				<li>
					<div class="event-box row-fluid <?php echo !empty($event->featured)?"featured":"" ?>">
						<div class="event-img-container span3">
							<a class="event-image"
								href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>">
								
								<?php if(isset($event->picture_path) && $event->picture_path!=''){?>
									<img  alt="<?php echo $event->name ?>" src="<?php echo JURI::root()."/".PICTURES_PATH.$event->picture_path?>"> &nbsp;</a>
								<?php }else{?>
									<img title="<?php echo $event->name?>" alt="<?php echo $event->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
								<?php } ?>
						</div>
						<div class="event-content span8">
							<div class="event-subject">
								<a title="<?php echo $event->name?>"
									href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>"><?php echo $event->name?>
								</a>
							</div>
							<div class="event-location">
								<i class="dir-icon-map-marker dir-icon-large"></i> <?php echo JBusinessUtil::getLocationText($event)?>
							</div>
							
							<div class="event-date">
								<i class="dir-icon-calendar"></i> <?php echo JBusinessUtil::getDateGeneralFormat($event->start_date).(!empty($event->start_date) && $event->start_date!=$event->end_date?" - ".JBusinessUtil::getDateGeneralFormat($event->end_date):"")?>, 
								<?php echo JBusinessUtil::convertTimeToFormat($event->start_time)." ".JText::_("LNG_UNTIL")." ".JBusinessUtil::convertTimeToFormat($event->end_time) ?>
							
							</div>
							
							<div class="event-type">
								<?php echo JText::_("LNG_TYPE")?>: <strong><?php echo $event->eventType?></strong>
							</div>
							<div class="event-desciption">
								<?php echo $event->short_description ?>
							</div>
						</div>
						<?php if(isset($event->featured) && $event->featured==1){ ?>
							<div class="featured-text">
								<?php echo JText::_("LNG_FEATURED")?>
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</li>
			<?php }
		} ?>
	</ul>
	<div class="clear"></div>
</div>