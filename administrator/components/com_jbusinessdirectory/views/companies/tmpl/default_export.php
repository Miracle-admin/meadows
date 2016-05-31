<?php 
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task != 'companies.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_COMPANIES_CONFIRM_DELETE', true);?>'))
		{
			Joomla.submitform(task);
		}
	}
</script>

<div class="modal hide fade" id="export-model">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('LNG_EXPORT_CSV'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
		<p><?php echo JText::_('LNG_EXPORT_CSV_TEXT'); ?></p>
		<br/>
		<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=companies');?>" method="post" name="exportForm" id="exportForm" enctype="multipart/form-data">
		
			<div class="row-fluid">
				<div class="control-group">
					<div class="controls">
						<label for="delimiter"><?php echo JText::_('LNG_DELIMITER')?> </label> 
						<select name="delimiter">
							<option value=";"><?php echo JText::_('LNG_SEMICOLON')?></option>
							<option value=","><?php echo JText::_('LNG_COMMA')?></option>
						</select>
						
						<div class="clear"></div>
						
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="category"><?php echo JText::_('LNG_CATEGORY')?> </label> 
							<select name="category" id="category">
								<option value="0"><?php echo JText::_("LNG_ALL_CATEGORIES") ?></option>
								<?php foreach($this->categories as $category){?>
									<option value="<?php echo $category->id?>" <?php $session = JFactory::getSession(); echo $session->get('categorySearch')==$category->id && $preserve?" selected ":"" ?> ><?php echo $category->name?></option>
									<?php foreach($this->subCategories as $subCat){?>
										<?php if($subCat->parent_id == $category->id){?>
											<option value="<?php echo $subCat->id?>" <?php $session = JFactory::getSession(); echo $session->get('categorySearch')==$subCat->id && $preserve?" selected ":"" ?> >-- <?php echo $subCat->name?></option>
										<?php } ?>
									<?php }?>
								<?php }?>
							</select>
						
					</div>
				</div>
				<br/>
				<div class="clear"></div>
				<input type="submit" name="submit" value="<?php echo JText::_("LNG_EXPORT");?>">		
					
			</div>

			 <input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
			 <input type="hidden" name="task" id="task" value="companies.exportCompaniesCsv" /> 
			 <?php echo JHTML::_( 'form.token' ); ?> 
		</form>
	</div>
</div>