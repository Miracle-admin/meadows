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

$itemsPerRow=6;
?>
<?php if(isset($offer)) { ?>
<div id="offer-day" class="day-offer<?php echo $moduleclass_sfx ?>">
	<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&controller=offer&view=offer&offerId='.$offer->id) ?>">
		<img  height="196" width="230"
			src="<?php echo JURI::root().PICTURES_PATH.$offer->pictures[0]->picture_path ?> "> 
	</a>
</div>


<?php } ?>