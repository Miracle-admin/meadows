<?php
/**
 * @copyright	Copyright (C) 2009-2012 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal','a.modal');

?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task != 'language.delete' || confirm('<?php echo JText::_("LNG_ARE_YOU_SURE_YOU_WANT_TO_DELETE", true,true);?>')) {
            Joomla.submitform(task);
        }
    }
</script>
<fieldset class="acyheaderarea">
	<div class="toolbar" id="toolbar" style="float:right;">
		<table>
			<tr>
				<td>
					<button class="btn btn-small btn-success" id="languageSaveButton" onclick="Joomla.submitbutton('language.create');" title="<?php echo JText::_('LNG_NEW_LANGUAGE',true); ?>">
						<span class="icon-apply icon-white"></span>
						<?php echo JText::_('LNG_NEW',true); ?>
					</button>
					<button class="btn btn-danger btn-small" id="languageSaveButton" onclick="Joomla.submitbutton('language.remove');" title="<?php echo JText::_('LNG_DELETE_LANGUAGES',true); ?>">
						<span class="icon-cancel"></span>
						<?php echo JText::_('LNG_DELETE',true); ?>
					</button>
				</td>
			</tr>
		</table>
	</div>
</fieldset>
<fieldset class='adminform'>
	<legend><?php echo JText::_('LNG_LANGUAGES',true) ?></legend>
	<table class="table table-striped adminlist" id="itemList">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="5%" class=""><?php echo JText::_('LNG_EDIT',true); ?></th>
				<th width=""  class=""><?php echo JText::_('LNG_NAME',true); ?></th>
				<th width="5%" class="hidden-phone"><?php echo JText::_('LNG_ID',true); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for($i = 0,$a = count($this->languages);$i<$a;$i++) {
				$row = $this->languages[$i]; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $i + 1; ?></td>
					<TD class="hidden-phone" align=center>
						<?php echo JHtml::_('grid.id', $i, $row->language); ?>
					</TD>
					<td align="center">
					   <a class="modal" title="<?php echo JText::_('LNG_CLICK_TO_EDIT',true) ?>" rel="{handler: 'iframe', size:{x:800, y:600}}" href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&tmpl=component&view=language&task=language.editLanguage&code='.$row->language )?>'>
					   		<img class="icon16" src="<?php echo JURI::base()."components/com_jbusinessdirectory/assets/img/edit.png" ?>" alt="'.JText::_('LNG_EDIT_LANGUAGE_FILE',true).'"/>
				    	</a>
					</td>
					<td align="center">
						<a class="modal" title="<?php echo JText::_('LNG_CLICK_TO_EDIT',true) ?>" rel="{handler: 'iframe', size:{x:800, y:600}}" href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&tmpl=component&view=language&task=language.editLanguage&code='.$row->language )?>' 
							title="<?php echo JText::_('LNG_CLICK_TO_EDIT',true); ?>"><?php echo $row->name; ?></a>
					</td>
					<td align="center"><?php echo $row->language; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			} ?>
		</tbody>
	</table>
</fieldset>