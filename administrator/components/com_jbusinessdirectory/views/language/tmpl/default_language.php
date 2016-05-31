<?php
JHtml::_('behavior.modal');

defined('_JEXEC') or die('Restricted access');
?>
<div id="language-content">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post" name="adminForm"  id="adminForm" autocomplete="off">
		<fieldset class="acyheaderarea">
			<div class="toolbar" id="toolbar" style="float: right;">
				<table>
					<tr>
						<td>
							<button class="btn btn-small btn-success" onclick="Joomla.submitbutton('language.apply')" title="<?php echo JText::_('LNG_SAVE_CHANGES',true); ?>">
								<span class="icon-apply icon-white"></span>
								<?php echo JText::_('LNG_SAVE',true); ?>
							</button>
							<button class="btn btn-default btn-small" id="languageSaveButton" onclick="Joomla.submitbutton('language.send_email');" title="<?php echo JText::_('LNG_SEND_THE_LANGUAGE_FILES_TO_AUTHOR',true); ?>" >
								<span class="icon-envelope" style="color:#000;"></span>
								<?php echo JText::_('LNG_SEND_EMAIL',true); ?>
							</button>
							<button class="btn btn-default btn-small" onclick="window.parent.SqueezeBox.close();" title="<?php echo JText::_('LNG_CANCEL_OPERATION',true); ?>">
								<span class="icon-cancel" style="color:#bd362f;"></span>
								<?php echo JText::_('LNG_CANCEL',true); ?>
							</button>
							
						</td>
					</tr>
				</table>
			</div>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_FILE',true).' : '.$this->file->name;?></legend>
			<textarea rows="30" name="content" id="translation" style="width:100%;max-width:95%;"><?php echo $this->file->content;?></textarea>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_FILE_CUSTOM',true); ?></legend>
			<textarea rows="18" name="custom_content" id="translation" style="width:100%;max-width:95%;"><?php echo $this->file->custom_content;?></textarea>
		</fieldset>

		<div class="clr"></div>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName(); ?>" />
		<input type="hidden" id="task" name="task" value=""/>
		<input type="hidden" id="view" name="view" value="language"/>
		<input type="hidden" id="code" name="code" value="<?php echo $this->file->name?>"/>
		<input type="hidden" name="ctrl" value="file" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function () {
		var iframeButton = jQuery("#sbox-window #sbox-content>iframe").find("#languageSaveButton");

		console.log(iframeButton);
		jQuery(iframeButton).mouseup(function () {
			jQuery('#sbox-overlay').remove();
			jQuery('#sbox-window').remove();
		});
	});
</script>