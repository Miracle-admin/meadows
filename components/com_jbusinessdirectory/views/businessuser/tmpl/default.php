<?php 
/**
* @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
$user = JFactory::getUser();
?>


<div id="process-container" class="process-container">
	<div class="process-steps-wrapper">
		<div id="process-steps-container" class="process-steps-container">
			<div class="main-process-step completed" >
				<div class="process-step-number">1</div>
				<?php echo JText::_("LNG_CHOOSE_PACKAGE")?>
			</div>
			<div class="main-process-step">
				<div class="process-step-number">2</div>
				<?php echo JText::_("LNG_BASIC_INFO")?>
			</div>
			<div class="main-process-step">
				<div class="process-step-number">3</div>
				<?php echo JText::_("LNG_LISTING_INFO")?>
			</div>
		</div>
	
		<div class="meter">
			<span style="width: 33%"></span>
		</div>
	</div>
	<div class="clear"></div>
</div>
	

<div class="row-fluid jdir-signup-wrap ">
	<div class="span6">
		<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=businessuser.addUser'); ?>" method="post" name="registration-form" id="registration-form" >
			<fieldset>
				<h3><?php echo JText::_("LNG_USER_ACCOUNT_DETAILS")?></h3>
				<p>
					<?php echo JText::_("LNG_USER_ACCOUNT_DETAILS_TXT")?>
				</p>
				<div class="form-item">
					<label for="username"><?php echo JText::_('LNG_NAME') ?></label>
					<div class="outer_input">
						<input type="text" name="name" id="name" size="50" class="validate[required]"><br>
					</div>
				</div>
				<div class="form-item">
					<label for="username"><?php echo JText::_('LNG_USERNAME') ?></label>
					<div class="outer_input">
						<input type="text" name="username" id="username" size="50" class="validate[required]"><br>
					</div>
				</div>
				<div class="form-item">
					<label for="email"><?php echo JText::_('LNG_EMAIL') ?></label>
					<div class="outer_input">
						<input type="text" name="email" id="email" size="50" class="validate[required,custom[email]]"><br>
					</div>
				</div>
				<div class="form-item">
					<label for="password"><?php echo JText::_('LNG_PASSWORD') ?></label>
					<div class="outer_input">
						<input type="password" name="password" id="password" size="50" class="validate[required]"><br>
					</div>
				</div>
				<div class="form-item">
					<label for="passwordc"><?php echo JText::_('LNG_CONFIRM_PASSWORD') ?></label>
					<div class="outer_input">
						<input type="password" name="passwordc" id="passwordc" size="50" class="validate[required]"><br>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="abtn"><?php echo JText::_('LNG_CREATE_ACCOUNT') ?></button>
					</div>
				</div>		
				
				<input type="hidden" name="filter_package" id="filter_package" value="<?php echo $this->filter_package ?>" />
			</fieldset>
		</form>
	</div>
	<div class="span6">
		<div class="">
			<h3><?php echo JText::_("LNG_ALREADY_HAVE_ACCOUNT")?></h3>
			<p>
				<?php echo JText::_("LNG_ALREADY_HAVE_ACCOUNT_TXT")?>
			</p>
			
			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=businessuser.loginUser'); ?>" method="post" id="login-form" name="login-form">
				<fieldset>
					<div class="form-item">
						<label for="username"><?php echo JText::_('LNG_USERNAME') ?></label>
						<div class="outer_input">
							<input type="text" name="username" id="username" size="50" class="validate[required]"><br>
						</div>
					</div>
					<div class="form-item">
						<label for="password"><?php echo JText::_('LNG_PASSWORD') ?></label>
						<div class="outer_input">
							<input type="password" name="password" id="password" size="50" class="validate[required]"><br>
						</div>
					</div>
				
											
					<div class="control-group">
						<div class="controls">
							<button type="submit"  class="abtn"><?php echo JText::_('LNG_LOG_IN') ?></button>
						</div>
					</div>		
				</fieldset>
				<input type="hidden" name="filter_package" id="filter_package" value="<?php echo $this->filter_package ?>" />
			</form>
		</div>
	</div>
</div>
	
<script>

jQuery(document).ready(function(){
	jQuery("#registration-form").validationEngine('attach');
	jQuery("#login-form").validationEngine('attach');
});
</script>