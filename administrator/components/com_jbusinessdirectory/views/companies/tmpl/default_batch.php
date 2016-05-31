<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$published = $this->state->get('filter.published');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('COM_JBUSINESSDIRECTORY_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
		<p><?php echo JText::_('COM_JBUSINESSDIRECTORY_BATCH_TIP'); ?></p>
		<div class="row-fluid">
			<div class="control-group span6">
				<div class="controls">
					<div class="controls">
						<label title="" class="modalTooltip" for="batch-approval_status_id" id="batch-status-lbl" data-original-title="&lt;strong&gt;Set Approval Status&lt;/strong&gt;&lt;br /&gt;Not making a selection will keep the original approval status when processing."><?php echo JText::_("LNG_SET_APPROVAL_STATUS")?></label>
						<select id="batch-approval_status_id" class="inputbox" name="batch[approval_status_id]">
							<option value=""><?php echo JText::_("LNG_KEEP_ORIGINAL_APPROVAL_STATUS")?></option>
							<?php echo JHtml::_('select.options', $this->statuses, 'value', 'text', $this->state->get('filter.status_id'));?>
						</select>	
					</div>
				</div>
			</div>
			<div class="control-group span6">
				<div class="controls">
					<label title="" class="modalTooltip" for="batch-featured-status-id" id="batch-featured-status-lbl" data-original-title="&lt;strong&gt;Set Featured Status&lt;/strong&gt;&lt;br /&gt;Not making a selection will keep the original featured status when processing."><?php echo JText::_("LNG_SET_FEATURED_STATUS")?></label>
					<select id="batch-featured-status-id" class="inputbox" name="batch[featured_status_id]">
						<option value=""><?php echo JText::_("LNG_KEEP_ORIGINAL_FEATURED_STATUS")?></option>
						<option value="1"><?php echo JText::_("LNG_FEATURED")?></option>
						<option value="0"><?php echo JText::_("LNG_NOT_FEATURED")?></option>
					</select>	
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="control-group span6">
				<div class="controls">
					<div class="controls">
						<label title="" class="modalTooltip" for="batch-state_id" id="batch-state-lbl" data-original-title="&lt;strong&gt;Set State&lt;/strong&gt;&lt;br /&gt;Not making a selection will keep the original state when processing."><?php echo JText::_("LNG_SET_STATE")?></label>
						<select id="batch-state-id" class="inputbox" name="batch[state_id]">
							<option value=""><?php echo JText::_("LNG_KEEP_ORIGINAL_STATE")?></option>
							<?php echo JHtml::_('select.options', $this->states, 'value', 'text', $this->state->get('filter.state_id'));?>
						</select>	
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.getElementById('batch-approval_status_id').value='';document.getElementById('batch-featured-status-id').value='';document.getElementById('batch-state-id').value='';" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('company.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
