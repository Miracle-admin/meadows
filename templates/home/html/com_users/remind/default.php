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
JHtml::_('behavior.formvalidator');
?>

<div class="meadows_login_wrapper">
  <div class="meadows_login_outer bg-white">
    <div class="remind<?php echo $this->pageclass_sfx?>">
      <?php if ($this->params->get('show_page_heading')) : ?>
      <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
      </div>
      <?php endif; ?>
      <form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=remind.remind'); ?>" method="post" class="form-validate form-horizontal ">
        <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
        <fieldset class="">
          <h2>Forgot Email ID?</h2>
          <p><?php echo JText::_($fieldset->label); ?></p>
          <?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
          <div class="form-group"> <?php echo $field->label; ?> <?php echo $field->input; ?> </div>
          <?php endforeach; ?>
        </fieldset>
        <?php endforeach; ?>
        <div class="">
          <div class="">
            <button type="submit" class="login_sumbmit"><?php echo JText::_('JSUBMIT'); ?></button>
          </div>
        </div>
        <?php echo JHtml::_('form.token'); ?>
      </form>
    </div>
  </div>
</div>
