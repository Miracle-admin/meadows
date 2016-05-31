<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
?>


<div class="meadows_login_wrapper">

<div class="dev-reg-hdr">
  <h2>App Meadows Login </h2>
  <span> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium<br>
 doloremque </span> </div>
  <div class="meadows_login_outer bg-white">
    <div class="<?php echo $this->pageclass_sfx?>">
      <?php if ($this->params->get('show_page_heading')) : ?>
      <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
      </div>
      <?php endif; ?>
      <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
      <div class="login-description">
        <?php endif; ?>
        <?php if ($this->params->get('logindescription_show') == 1) : ?>
        <?php echo $this->params->get('login_description'); ?>
        <?php endif; ?>
        <?php if (($this->params->get('login_image') != '')) :?>
        <img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USERS_LOGIN_IMAGE_ALT')?>"/>
        <?php endif; ?>
        <?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
      </div>
      <?php endif; ?>
      <div class="col-md-12 text-center login_bg_logo"><img src="<?php echo $this->baseurl . '/templates/home' ; ?>/images/login/loginlogo.jpg"></div>
      <form id="login-appmeadows" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate form-horizontal">
        <fieldset class="col-md-12">
          <?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
          <?php if (!$field->hidden) : ?>
          <div class="form-group row"> <?php echo $field->label; ?> <?php echo $field->input; ?> </div>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php if ($this->tfa): ?>
          <div class="form-group row"> <?php echo $this->form->getField('secretkey')->label; ?> <?php echo $this->form->getField('secretkey')->input; ?> </div>
          <?php endif; ?>
          <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
          <div  class="form-group">
            <div class="col-md-6 row">
              <input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
              <label><?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?></label>
            </div>
            <div class="col-md-7 row text-right"><a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"> <?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a></div>
          </div>
          <?php endif; ?>
          <div class="form-group">
            <div class="">
              <button type="submit" class="login_sumbmit"> <?php echo JText::_('JLOGIN'); ?> </button>
            </div>
          </div>
          <input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
          <?php echo JHtml::_('form.token'); ?>
        </fieldset>
      </form>
      
      
      <div class="dev-reg-hdr or">
      <span>or</span>
      <hr>
   </div>
      
      <div class="social_login_btn_wrp row">
        <?php 
			$document = &JFactory::getDocument();
			$renderer   = $document->loadRenderer('modules');
			$position   = 'fglogin';
			$options   = array('style' => 'raw');
			echo $renderer->render($position, $options, null); 
		?>
        <!--	<div class="col-md-6">
        	<a href="" class="gplus_login_btn">Google+</a>
        </div>
        <div class="col-md-6">
        	<a href="" class="fb_login_btn">Facebook</a>        
        </div>--> 
      </div>
    </div>
    <?php /*?><div>
	<ul class="nav nav-tabs nav-stacked">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
</div><?php */?>
  </div>
</div>
