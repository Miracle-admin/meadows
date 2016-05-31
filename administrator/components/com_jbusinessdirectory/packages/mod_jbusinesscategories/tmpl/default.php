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

$itemsPerRow=6;

$rowConfiguration = array('8'=>array(18,14,26),'9'=>array(14,15,26),'15'=>array(20,20,26),'16'=>array(18,18,28));

//var_dump($rowConfiguration);
?>
<script>
	jQuery(document).ready(function(){
		jQuery("li.main-cat").mouseover(function() {
			jQuery(this).addClass('over');
		}).mouseout(function() {
			jQuery(this).removeClass('over');
		});

		jQuery("ul.subcategories").mouseover(function() {
			jQuery(this).parent().addClass('over');
		}).mouseout(function() {
			jQuery(this).parent().removeClass('over');
		});

		jQuery("#category-holder").height( jQuery("#categories-menu-container").height());

		jQuery(".subcategories").each(function(){
			//var p = jQuery("#main-categories").position();
			var offset =jQuery( ".main-categories" ).width()-1;
			jQuery(this).css({left: offset});
		});
	});
</script>


<div class="categories-menu<?php echo $moduleclass_sfx ?>" id="category-holder">
	<ul class="main-categories" id="categories-menu-container">
		
		<?php 
			foreach($categories as $category){
				if(!is_array($category) || $category[0]->published==0)
					continue;
		?>
		<li class="main-cat">
			<?php 
				if(isset($category["subCategories"]) && count($category["subCategories"])>0){
			?>
				<ul class="subcategories">
				<?php 
					if(count($category["subCategories"]) < 12 ){
						foreach($category["subCategories"] as $subcategory){
				?>
					<li class="subcat"><a href="<?php echo JBusinessUtil::getCategoryLink($subcategory[0]->id, $subcategory[0]->alias) ?>"><?php echo $subcategory[0]->name ?></a></li>
				<?php }

					} else{
				?>
						<li class="subcat-in-list">
							<ul class="subcat-list">
						<?php 
							$index =0;
							$rowIndex = 0;
							$nrCategories = count($category["subCategories"]);
							if($nrCategories>0)
								foreach($category["subCategories"] as $subcategory){
									$index++;
									//echo $index .' '.$rowIndex. ' x ' .$rowConfiguration[$category[0]->id][$rowIndex].' x '.$category[0]->id ;
									//if($index == $rowConfiguration[$category[0]->id][$rowIndex]){
									if($index % 10 == 0){
										$rowIndex ++;
										$index = 1;
										echo '</ul><ul class="subcat-list">';
									}
						?>
							<li class="subcat">
								<a href="<?php echo JBusinessUtil::getCategoryLink($subcategory[0]->id, $subcategory[0]->alias) ?>">
									<?php echo $subcategory[0]->name ?>
								</a>
							</li>
						<?php } ?>
								</ul>
							</li>
				<?php }?>			
						
			</ul>
				<?php }?>	
			<h2>
				<a href="<?php echo JBusinessUtil::getCategoryLink($category[0]->id, $category[0]->alias) ?>"><?php echo $category[0]->name?> </a><i></i>
			</h2>
		</li>
		<?php } ?>
	</ul>	
</div>


