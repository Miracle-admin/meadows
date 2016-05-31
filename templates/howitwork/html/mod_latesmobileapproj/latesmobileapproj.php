<?php
// No direct access
defined('_JEXEC') or die; 
?>

<div style="clear:both"></div>
<div class="box_wrapper latest_project_wrapper">
<div class="container"> 
              <h3>Latest Mobile App Development</h3>
              <?php
foreach($latest as $result)
{
	?>
	<div class="col-md-6">
                
                <div class="latest_project_home">
                    
                    	<a class="project_home_title" href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$result->id.'&Itemid=128') ?>"><?php  echo $result->project_title; ?></a>
                    
                    <div class="project_home_price">
                    	Price : $<?php  echo $result->budgetmin.' - $'.$result->budgetmax; ?>
                    </div>
                     <ul class="project_featured">
                    <?php if($result->is_featured=='1')
					{
						?>
                   
                    <li>
                    	<span class="featured_project"><?php  echo "Featured"; ?></span>
                    </li>
                    <?php 
					} ?>
                    <?php if($result->is_urgent=='1')
					{
						?>
                    <li>
                    	<span class="urgent_project"><?php  echo "Urgent"; ?></span>
                        </li>
                    <?php 
					} ?>
                    <?php if($result->is_private=='1')
					{
						?>
                    <li>
                    	<span class="urgent_project"><?php  echo "Private"; ?></span>
                        </li>
                  
                    <?php 
					} ?>
                    <?php if($result->is_sealed=='1')
					{
						?>
                    <li>
                    	<span class="urgent_project"><?php  echo "Sealded"; ?></span>
                        </li>
                   
                    <?php 
					} ?>
                     <?php if($result->is_nda=='1')
					{
						?>
                    <li>
                    	<span class="urgent_project"><?php  echo NDA; ?></span>
                        </li>
                    
                    <?php 
					} ?>
                     <div class="">
                    	<?php  echo substr($result->description,0,200); ?>
                    </div>
                    </div>
                </div>
                <?php
}

?>
        </div></div>