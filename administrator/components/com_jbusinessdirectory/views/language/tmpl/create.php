<?php
defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<div id="acy_content">
	<form action="index.php?option=<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=language&task=store');?>" method="post" name="adminForm" id="item-form">
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_LANGUAGE'); ?></legend>
			<input 
				type		= "text"
				name		= "code"
				id		 	= "code"
				placeholder = "en-GB"
				maxlength 	= "5"
				class       = 'input_txt validate[required]'
			/>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_LANGUAGE_FILE',true) ;?></legend>
			<textarea rows="30" name="content" id="translation" class="input_txt validate[required]" style="width:100%;max-width:95%;" 
			placeholder='LNG_NAME="Name"
LNG_SURNAME="Surname"'></textarea>
		</fieldset>
		<div class="clr"></div>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName(); ?>" />
		<input type="hidden" name="task" value="language.store" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>