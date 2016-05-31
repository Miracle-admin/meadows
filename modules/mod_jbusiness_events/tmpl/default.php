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
<div class="latest-events<?php echo $moduleclass_sfx; ?>">
<?php if(!empty($items)){ ?>
<ul >
<?php foreach ($items as $event){  ?>
	<li>
		<div class="event-container">
			<div class="event-image">
				<div class="event-overlay">
					<div class="event-vertical-middle">
						<div> 
							<a href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias)?>" class="btn-view"><?php echo JText::_("LNG_VIEW")?></a>
						</div>
					</div>
				</div>
				<a href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>">
					<?php if(!empty($event->picture_path)){?>
						<img title="<?php echo $event->name?>" alt="<?php echo $event->name ?>"  src="<?php echo JURI::root()."/".PICTURES_PATH.$event->picture_path?>"> 
					<?php }else{ ?>
						<img title="<?php echo $event->name?>" alt="<?php echo $event->name ?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
					<?php } ?>

				</a>
			</div>
			<div class="event-title">
				<a
					title="<?php echo $event->name?>"
					href="<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>"><?php echo $event->name?>
					</a>
			</div>
		</div>
	</li>
<?php } ?>
</ul>
<?php } ?>
<div class="clear"></div>
</div>