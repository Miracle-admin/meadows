<?php
// No direct access
defined('_JEXEC') or die; 
?>

<div class="row">
          <div class="col-md-12 buy-wrap">
            <h2>Buy Source TEWMM <br>
              Code And Templates</h2>
               <?php
			  $widthRSM = $params->get('module_description', '');
              echo '<p>'.$widthRSM.'</p>';
			  ?>
            </div>
        </div>
<div class="row game-wrapper">
<?php
             
			 
foreach($source as $result)
{
	?>
	<div class="col-md-6 game-wrap">
                <div class="img-wrap"> 
                <img src="<?php echo JURI::root(); ?>joobi/user/media/images/products/<?php echo $result->name.'.'.$result->type ?>" alt="" />
                </div>
                <h2><?php echo $result->itemname; ?> </h2>
                <p><?php echo $result->catalias; ?></p>
          </div>
                <?php
}

?>
       <a class="start_wrap"href="<?php echo JRoute::_('index.php?option=com_jmarket&view=list_of_all_items&controller=catalog-items&Itemid=200') ?>">Check Out the App Market</a> 
          </div>