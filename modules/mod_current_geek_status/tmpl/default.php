<?php
defined('_JEXEC') or die;
$user = JFactory::getUser();
 $plan = JblanceHelper::getBtPlan(); 
 $planName = $plan['name'];
 $pid      = $plan['id'];

 
?>

<div class="container valign post-project-content">
  <div class="col-md-8">
    <?php 
echo " <h4>Your current geek status is ".$planName;
if($pid!= 19)
{
?>
    <p>Enhance your geek profile and get amazing benefits</p>
</div>
  <div class="col-md-4"><a href="<?php echo JUri::root() ?>index.php?option=com_jblance&view=membership&layout=plans&Itemid=344">
    <button>Upgrade your status</button>
    </a></div>
  <?php } ?>
    
</div>
