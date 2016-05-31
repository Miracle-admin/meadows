<?php defined('_JEXEC') or die; ?>

<?php
	$image=empty($userInfo->picture)?"components/com_jblance/images/nophoto_big.svg":"images/jblance/".$userInfo->picture;
	$link_home = JRoute::_(JURI::root());
?>
		
<div class="nav-wrapper-right">	
 <div class="nav-wrapper-right" >
	  <div class="user_control"><img height="40" width="40" src="<?php echo JURI::root().$image; ?>"/> 
              <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=dashboarddeveloper&Itemid=368');?>">
                  <?php echo $user->username;  ?></a>
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span></button>
		<ul class="dropdown-menu" role="menu">
			<li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=149&username='.$user->username);?>">View Profile</a></li>
			<li><a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile&Itemid=150');?>">Edit Profile</a></li>
			<li><a href="<?php echo JRoute::_(JURI::root().'index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode($link_home)); ?>">Logout</a></li>
		</ul>
	</div>
</div>	
</div>
