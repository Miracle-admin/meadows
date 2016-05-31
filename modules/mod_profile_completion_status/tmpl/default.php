<?php
defined('_JEXEC') or die;
$user     = JFactory::getUser();
$ComLogo = JblanceHelper::getLogo($user->id);
$profMeter=JblanceHelper::getProfileCompleted($user->id);
$profile=JblanceHelper::get("userhelper");
$jbuser=$profile->getUser($user->id);


$dispatcher = JDispatcher::getInstance();
            $results = $dispatcher->trigger( 'onProfileProgress', array(10,621) );

?>
<div class="profile-complete-wrap">
<h4><?php echo $jbuser->biz_name ?></h4>
<p class="u_thumbnail"><?php echo $ComLogo; ?></p>
<p class="u_p_meter"><?php echo $profMeter[1]; ?></p>
<p class="u_completion">Your profile is <?php echo ($profMeter[0])?$profMeter[0]:'10' ?>% complete.</p>

<p><?php if($profMeter[0]!=100){ ?><a href="<?php echo JUri::root() ?>index.php?option=com_jblance&view=project&layout=editprojectcustom&Itemid=337"><button>Complete Your Profile</button></a></p>
<?php } ?>

</div>
