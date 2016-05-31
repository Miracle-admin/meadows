<?php
// No direct access
defined('_JEXEC') or die; 
?>

<div style="clear:both"></div>
<div class="row featured_com_wrap">
<div class="container-fluid">
	<div class="container">

              <h3>Featured Mobile App Development Companies</h3>
 
			<?php
			
			foreach($list as $listValue)
			{
			$userId=$listValue->user_id; 
			   
			$desc= $helper::getDescription($userId);
			$desc=!empty($desc)?$desc:'N/A';
			
			$link=$helper::getLink($userId);
			$link=!empty($link)?$link:'#';	
			
			$picture=!empty($listValue->picture)?JURI::root().'images/jblance/'.$listValue->picture:JURI::root().'images/noimage.png';
			
			$bizName=!empty($listValue->biz_name)?$listValue->biz_name:'N/A';
				?>
                <div class="mobcontainer col-md-4 col-sm-4">
                	<div class="compimg">
                    	
                        <img src="<?php echo $picture; ?>" alt="<?php echo $bizName; ?>"/>
                        
                    </div>
                    <div class="compname">
                    	<h3><?php  echo $bizName; ?></h3>
                    </div>
                    
                      <div class="compdesc">
                    	<span><?php  echo substr($desc,0,150); ?></span>
                    </div>
                   
                    <div class="compname">
                    	<a target="_blank" class="visit_link"  href="<?php  echo $link; ?>">Website</a>
                    </div>
                </div>
                
                
               
                
                <?php
			}
			?>
            
             <div class="col-md-12 button_wrapper">
                	<a href="">View more development companies</a>
                </div>
            
            </div>
        </div></div>