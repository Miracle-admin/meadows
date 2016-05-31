<?php
/**
 * @version     1.0.0
 * @package     com_vmvendor
 * @copyright   Copyright (C) 2015. All rights rserved
 * @license     GNU General Public License version 3 ; see LICENSE.txt
 * @author      Nordmograph <contact@nordmograph.com> - http://www.nordmograph.com/extensions
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_vmvendor/assets/css/vmvendor.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'pointsactivity.cancel') {
            Joomla.submitform(task, document.getElementById('pointsactivity-form'));
        }
        else {
            
            if (task != 'pointsactivity.cancel' && document.formvalidator.isValid(document.id('pointsactivity-form'))) {
                
                Joomla.submitform(task, document.getElementById('pointsactivity-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_vmvendor&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="pointsactivity-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_VMVENDOR_TITLE_POINTSACTIVITY', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('userid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('userid'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('points'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('points'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('insert_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('insert_date'); ?></div>
			</div>
				<input type="hidden" name="jform[expire_date]" value="<?php echo $this->item->expire_date; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('status'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('status'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('approved'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('approved'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('keyreference'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('keyreference'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('datareference'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('datareference'); ?></div>
			</div>


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>