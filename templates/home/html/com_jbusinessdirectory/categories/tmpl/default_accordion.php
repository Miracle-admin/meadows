<?php // no direct access
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
 
<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
 <?php } ?>

<div id="categories-container" class="categories-accordion">
	<ul id="categories-accordion">
	<?php foreach($this->categories as $category){
		if(isset($category[0]->name)){		
	?>
		<li class="accordion-element">
			<div>
				<!-- div class="category-img-container">
					<img alt="" src="<?php echo JURI::root().PICTURES_PATH.$category[0]->imageLocation ?>">
				</div--> 
				<h3><a href="<?php echo JBusinessUtil::getCategoryLink($category[0]->id,  $category[0]->alias)  ?>"><?php echo $category[0]->name ?></a></h3>
			</div>
			<div>
				<ul class="category-list">
					<?php
						$i=1; 
						foreach($category["subCategories"] as $cat){ 
					?>
					<li>
						<a class="categoryLink" title="<?php echo $cat[0]->name?>" alt="<?php echo $cat[0]->name?>" 
							href="<?php echo JBusinessUtil::getCategoryLink($cat[0]->id,  $cat[0]->alias)  ?>"
						>
							<?php echo $cat[0]->name?>
						</a>
					</li> 
				<?php } ?>
				</ul>
			</div>
		</li>
	<?php 
		}
	} 
	?>
	</ul>
</div>
<div class="clear"></div>
<script>
	jQuery(document).ready(function(){
		jQuery("#categories-accordion" ).accordion({
			heightStyle: "content",
			active: "false",
			event: "click hoverintent"
		});
	});
</script>