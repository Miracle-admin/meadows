<?php
/**
 * @version		$Id: default.php 21322 2011-05-11 01:10:29Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>

<div class="juser-container">
<?php if ($type == 'logout') : ?>
<div class="<?php echo $moduleclass_sfx ?>">
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="user-logout-form">
		
		<button type="button" class="ui-dir-button" onclick="logout()">
			<span class="ui-button-text"><?php echo JText::_("JLOGOUT")?></span>
		</button>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
	
	<div class="clear"></div>
	<br/></br>
	<h3 class="reg-h"><?php echo JText::_('MOD_ADMINISTRATION') ?></h3>
	<p><?php echo JText::_('MOD_ADMINISTRATION_TEXT') ?></p>
	
	<a class="ui-dir-button ui-dir-button-green" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=useroptions'); ?>">
		<span class="ui-button-text"><?php echo JText::_("MOD_CONTROL_PANEL")?></span>
	</a>

</div>
<?php else : ?>

<div class="<?php echo $moduleclass_sfx ?>">
	<p class="sign-in-text"><?php echo JText::_('MOD_SIGN_IN_TEXT') ?></p>
	
	<button type="button" class="ui-dir-button ui-dir-button-green" onclick="showUserLogin()">
			<span class="ui-button-text"><?php echo JText::_("MOD_LOGIN")?></span>
	</button>

	
	<div class="clear"></div>
	<br/></br>
	<h3 class="reg-h"><?php echo JText::_('MOD_NOT_REGISTERED') ?></h3>
	<p><?php echo JText::_('MOD_REGISTER_TEXT') ?></p>
	<a class="ui-dir-button" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
		<span class="ui-button-text"><?php echo JText::_("MOD_LOGIN_REGISTER")?></span>
	</a>
</div>


 
<div id="userLoginFrm" style="display:none;">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>

		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('MOD_LOGIN'); ?></h3>
			<div class="dialogContentBody" id="dialogContentBody">
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form2" >
					<?php if ($params->get('pretext')): ?>
						<div class="pretext">
						<p><?php echo $params->get('pretext'); ?></p>
						</div>
					<?php endif; ?>
					<fieldset class="userdata">
					<p id="form-login-username">
						<label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
						<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
					</p>
					<p id="form-login-password">
						<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
						<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
					</p>
					<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
					<p id="form-login-remember">
						<label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
						<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
					</p>
					<?php endif; ?>
					
					<button type="button" class="ui-dir-button ui-dir-button-green" onclick="jQuery('#login-form2').submit()">
							<span class="ui-button-text"><?php echo JText::_("JLOGIN")?></span>
					</button>
				
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.login" />
					<input type="hidden" name="return" value="<?php echo base64_encode("index.php?option=com_jbusinessdirectory&view=useroptions"); ?>" />
					<?php echo JHtml::_('form.token'); ?>
					</fieldset>
					<ul>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
							<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
						</li>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
							<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
						</li>
						<?php
						$usersConfig = JComponentHelper::getParams('com_users');
						if ($usersConfig->get('allowUserRegistration')) : ?>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
								<?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
						</li>
						<?php endif; ?>
					</ul>
					<?php if ($params->get('posttext')): ?>
						<div class="posttext">
						<p><?php echo $params->get('posttext'); ?></p>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
</div>
<script>
	function showUserLogin(){
		jQuery.blockUI({ message: jQuery('#userLoginFrm'), css: {width: '350px'} }); 
	}

	function logout(){
		jQuery("#user-logout-form").submit();
	}
</script>
