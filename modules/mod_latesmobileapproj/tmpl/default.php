<?php
// No direct access
defined('_JEXEC') or die; 
?>

<div style="clear:both"></div>
<div class="box_wrapper latest_project_wrapper">
<div class="container"> 
              <h3>Latest Mobile App Development Projects</h3>
              
              <?php
			  	include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');
			   $config 	  = JblanceHelper::getConfig();
				 $currencysym = $config->currencySymbol;
				 $currencycod = $config->currencyCode;
foreach($latest as $result)
{ 
	?>
	<div class="col-md-6">
                
                <div class="latest_project_home">
                    
                    	<a class="project_home_title" href="<?php echo JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&pid='.$result->id.'&Itemid=128') ?>"><?php  echo $result->project_title; ?></a>
                    
                    <div class="project_home_price">
                    	Price : <?php  echo $currencysym.$result->budgetmin.' - '. $currencysym.$result->budgetmax.'('.$currencycod.')'; ?>
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
                    	<span class="privtae_project"><?php  echo "Private"; ?></span>
                        </li>
                  
                    <?php 
					} ?>
                    <?php if($result->is_sealed=='1')
					{
						?>
                    <li>
                    	<span class="sealded_project"><?php  echo "Sealded"; ?></span>
                        </li>
                   
                    <?php 
					} ?>
                     <?php if($result->is_nda=='1')
					{
						?>
                    <li>
                    	<span class="nda_project"><?php  echo NDA; ?></span>
                        </li>
                    
                    <?php 
					} ?>
                     <div class="ho_pro_dsp">
                    	<?php  echo substr($result->description,0,200); ?>
                    </div>
                     <div class="time_post">
                    	<?php  if($result->status=='COM_JBLANCE_OPEN')
						echo 'Open'; ?>
                        <?php  if($result->status=='COM_JBLANCE_CLOSED')
						echo 'Closed '; ?>
                       <?php
					  	$start_date = new DateTime($result->create_date);
						$since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));
						if($since_start < 1)
						{
							echo '/ Posted Today /';
						}
						else
						{
							echo '/ Posted '.$since_start->days.' d ago / ';
						
						}
						echo intval($result->expires)-intval($since_start->days).' d remains';
						
						?>
                    </div>
                    </div>
                </div>
                <?php
}

?>
        </div></div>