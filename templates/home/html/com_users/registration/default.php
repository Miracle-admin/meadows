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
<div class="registration<?php echo $this->pageclass_sfx ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>
    
<div class="dev-reg-wrap">
<div class="dev-reg-common simple-user-reg-wrap">

    <form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
        <?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one. ?>
            <?php $fields = $this->form->getFieldset($fieldset->name); ?>
            <?php if (count($fields)): ?>
                <fieldset>
                    <div class="">
                        <?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend. ?>
                            <h2><?php echo JText::_($fieldset->label); ?></h2>
                        <?php endif; ?>
                        <?php foreach ($fields as $field) :// Iterate through the fields in the set and display them.?>
                            <div >
                                <?php if ($field->hidden):// If the field is hidden, just display the input.?>
                                    <?php echo $field->input; ?>
                                <?php else: ?>
                                    <div class="control-group">
                                        <div class="control-label">
                                            <?php echo $field->label; ?>
                                            <?php if (!$field->required && $field->type != 'Spacer') : ?>
                                                <span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="controls">
                                            <?php echo $field->input; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="clearfix"></div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="abtn validate"><?php echo JText::_('JREGISTER'); ?></button>
                <a class="abtn" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
                <input type="hidden" name="option" value="com_users" />
                <input type="hidden" name="task" value="registration.register" />
            </div>
        </div>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>    
    
    </div>
    
    
</div>
