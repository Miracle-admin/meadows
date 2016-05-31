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
<div id="layout" class="pagewidth clearfix grid4 grid-view-2" <?php echo !$this->appSettings->offers_view_mode?'style="display: none"':'' ?>>

<div id="grid-content">
	<div id="loops-wrapper" class="loops-wrapper infinite-scrolling AutoWidthElement">

	<?php 
	if(!empty($this->offers)){
		foreach($this->offers as $index=>$offer){
		?>

		<article id="post-<?php echo  $offer->id ?>" class="post post type-post status-publish format-standard hentry category-food post clearfix ">
			<div class="post-inner">
				<h1 class="post-title"><a href="<?php echo  $offer->link ?>"><?php echo $offer->subject?></a></h1>
				<figure class="post-image ">
						<a href="<?php echo $offer->link ?>">
							<?php if(!empty($offer->picture_path) ){?>
								<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.$offer->picture_path ?>">
							<?php }else{ ?>
								<img title="<?php echo $offer->subject?>" alt="<?php echo $offer->subject?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
							<?php } ?>
						</a>
				</figure>
				
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
