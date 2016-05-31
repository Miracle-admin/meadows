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

function preparequickIconButton($url, $image, $text, $description=null){
	?>
		<li class="option-button">
		 	<a class="box box-inset" href="<?php  echo $url ?>">
		 		<?php echo JHTML::_('image','administrator/components/com_jbusinessdirectory/assets/img/'.$image, $text); ?>	
		 		<h3><?php echo $text; ?></h3>
	 			<p><?php echo $description ?></p>
 			</a>
		</li>
	<?php
} 

?>
<div id='business-cpanel'>
	<ul>
		<?php echo preparequickIconButton( "index.php?option=".JBusinessUtil::getComponentName()."&view=reports&layout=standard", 'settings_48_48_icon.gif', JText::_('LNG_REPORTS_STANDARD') );?>
		<?php echo preparequickIconButton( "index.php?option=".JBusinessUtil::getComponentName()."&view=reports&layout=statistics", 'settings_48_48_icon.gif', JText::_('LNG_REPORTS_STATISTICS') );?>
		<?php echo preparequickIconButton( "index.php?option=".JBusinessUtil::getComponentName()."&view=reports&layout=income", 'settings_48_48_icon.gif', JText::_('LNG_REPORTS_INCOME') );?>
	</ul>
</div>


<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
</form>	

<script>
jQuery(document).ready(function(){
	jQuery("#accordion-info").accordion({
		 heightStyle: "content"
	 });
});

</script>
