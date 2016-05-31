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
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		
			Joomla.submitform(task);
		
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=discounts');?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<fieldset  class="boxed">
		<div class="form-box">
			<h2> <?php echo JText::_('LNG_EXPORT_CSV');?></h2>
			<div>
				<?php echo JText::_('');?>									
			</div>			
			</br/></br/>
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="delimiter"><?php echo JText::_('LNG_DELIMITER')?> </label> 
					<select name="delimiter">
						<option value=";"><?php echo JText::_('LNG_SEMICOLON')?></option>
						<option value=","><?php echo JText::_('LNG_COMMA')?></option>
					</select>
					
					<div class="clear"></div>
					
				</div>
			
					<br/><br/>
				
			<div class="clear"></div>
			<input type="submit" name="submit" value="<?php echo JText::_("LNG_EXPORT");?>">		
			
		</div>
		
	</fieldset>
	
	 <input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	 <input type="hidden" name="task" id="task" value="discounts.exportDiscountsCsv" /> 
	 <?php echo JHTML::_( 'form.token' ); ?> 
</form>