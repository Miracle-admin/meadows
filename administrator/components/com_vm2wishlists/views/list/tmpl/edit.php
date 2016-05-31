<?php
/**
 * @version     2.0.0
 * @package     com_vm2wishlists
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 3 or higher ; See LICENSE.txt
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
$document->addStyleSheet('components/com_vm2wishlists/assets/css/vm2wishlists.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'list.cancel') {
            Joomla.submitform(task, document.getElementById('list-form'));
        }
        else {
            
            if (task != 'list.cancel' && document.formvalidator.isValid(document.id('list-form'))) {
                
                Joomla.submitform(task, document.getElementById('list-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_vm2wishlists&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="list-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_VM2WISHLISTS_TITLE_LIST', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('list_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('list_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('list_description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('list_description'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('icon_class'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('icon_class'); ?></div>
			</div>
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('privacy'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('privacy'); ?></div>
			</div>

            <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('forbidcatids'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('forbidcatids'); ?></div>
            </div>
            <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('onlycatids'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('onlycatids'); ?></div>
            </div>
            <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('adders'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('adders'); ?></div>
            </div>

				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>

                </fieldset>
                
                
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
         <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'optional', JText::_('COM_VM2WISHLISTS_TITLE_OPTIONAL', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
            <fieldset class="adminform">
                	<div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('amz_link'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('amz_link'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('amz_base'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('amz_base'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('amz_prefix'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('amz_prefix'); ?></div>
                    </div>
            		
                </fieldset>
          </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php if (JFactory::getUser()->authorise('core.admin','vm2wishlists')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>