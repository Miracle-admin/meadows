
<div id="categories-container" class="categories-container">
	<div class="clear"></div>
	<div class="row-fluid">
	<?php $k = 0;?>
	<?php foreach($this->categories as $category){
		if(isset($category[0]->name)){	
			$k= $k+1;
			
	?>
		<div class="category-content span4">
			<?php if(!empty($category[0]->imageLocation)){ ?>
				<div class="category-img-container">
					<img alt="" src="<?php echo JURI::root().PICTURES_PATH.$category[0]->imageLocation ?>">
				</div>
			<?php } ?>
			<h2><a href="<?php echo JBusinessUtil::getCategoryLink($category[0]->id,  $category[0]->alias) ?>"> <?php echo $category[0]->name ?></a></h2>
			<?php
				$i=1; 
				foreach($category["subCategories"] as $cat){ 
					if($i>10)
						break;
					echo $i++==1?'':'|';
			?>
				
				<a class="categoryLink" title="<?php echo $cat[0]->name?>" alt="<?php echo $cat[0]->name?>" 
					href="<?php echo JBusinessUtil::getCategoryLink($cat[0]->id,  $cat[0]->alias) ?>"
				>
					<?php echo $cat[0]->name?>
				</a> 
			<?php } ?>
			<div class="clear"></div>
		</div>
		
		<?php if($k%3==0){?>
			</div>
			<div class="row-fluid">
		<?php }?>
		
	<?php 
		}
	} 
	?>
	</div>
</div>
<div class="clear"></div>