<?php 

//$calendarSource = html_entity_decode(JRoute::_('index.php?option=com_jbusinessdirectory&task=events.getCalendarEvents&companyId='.$this->company->id));
//require_once JPATH_COMPONENT_SITE.'/libraries/calendar/calendar.php';
?>

<div class='events-container full' style="">
	<ul class="event-list">
	<?php
		if(!empty($this->events)){
			foreach ($this->events as $event){ ?>
				<li>
					<div class="event-box">
						<div class="event-img-container">
							<a class="event-image"	href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>">
								<?php if(!empty($event->picture_path)){?>
									<img title="<?php echo $event->name?>" alt="<?php echo $event->name ?>"  src="<?php echo JURI::root()."/".PICTURES_PATH.$event->picture_path?>"> 
								<?php }else{ ?>
									<img title="<?php echo $event->name?>" alt="<?php echo $event->name ?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
								<?php } ?>
							</a>
						</div>
						<div class="event-content">
							<div class="event-subject">
								<a
									title="<?php echo $event->name?>"
									href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>"><?php echo $event->name?>
									</a>
							</div>
							<div class="event-location">
								<?php echo $event->location?>&nbsp;-&nbsp;<?php echo JBusinessUtil::getDateGeneralFormat($event->start_date)." ".JText::_("LNG_UNTIL")." ".JBusinessUtil::getDateGeneralFormat($event->end_date) ?>
							</div>
							<div class="event-type">
								<?php echo $event->eventType?>
							</div>
							<div class="event-desciption">
								<?php echo $event->short_description ?>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</li>
			<?php }
		}else{
			echo JText::_("LNG_NO_EVENT_FOUND");
		} ?>
	</ul>
</div>
		
<div class="clear"></div>	