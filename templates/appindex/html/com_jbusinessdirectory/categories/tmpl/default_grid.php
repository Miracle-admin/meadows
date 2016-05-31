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
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/imagesloaded.pkgd.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.isotope.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/isotope.init.js');
?>


<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
 <?php } ?>
 
<!-- layout -->
<div id="layout" class="pagewidth clearfix grid4 categories-grid" >

<div id="grid-content">
	<div id="loops-wrapper" class="loops-wrapper infinite-scrolling AutoWidthElement">

	<?php $k = 0;?>
	<?php foreach($this->categories as $category){
		if(isset($category[0]->name)){	
			$k= $k+1;
			
	?>

		<article id="post-<?php echo  $category[0]->id ?>" class="post post type-post status-publish format-standard hentry category-food post clearfix ">
			<div class="post-inner">
				<figure class="post-image ">
						<a href="<?php echo JBusinessUtil::getCategoryLink($category[0]->id,  $category[0]->alias) ?>">
							<?php if(!empty($category[0]->imageLocation) ){?>
								<img title="<?php echo $category[0]->name?>" alt="<?php echo $category[0]->name?>" src="<?php echo JURI::root().PICTURES_PATH.$category[0]->imageLocation ?>">
							<?php }else{ ?>
								<img title="<?php echo $category[0]->name?>" alt="<?php echo $category[0]->name?>" src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>">
							<?php } ?>
						</a>
				</figure>
				
				<div class="post-content">
					<h1 class="post-title"><a href="<?php echo  JBusinessUtil::getCategoryLink($category[0]->id,  $category[0]->alias) ?>"><?php echo $category[0]->name?></a></h1>

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
