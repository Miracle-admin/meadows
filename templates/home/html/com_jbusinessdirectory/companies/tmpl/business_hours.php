<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div id="busienss_hours">
	<fieldset class="fieldset-business_hours">
		<div>
			<h3><i class="dir-icon-time"></i> <?php echo JText::_('LNG_OPENING_HOURS');?></h3>
				<?php $dayNames = array(JText::_("MONDAY"),JText::_("TUESDAY"),JText::_("WEDNESDAY"),JText::_("THURSDAY"),JText::_("FRIDAY"),JText::_("SATURDAY"),JText::_("SUNDAY"))
				?>
				<?php foreach($dayNames as $index=>$day){?>
					<div class="business-hour" itemprop="openingHours">
						<div class="day"><?php echo $day?></div>
					    <span class="business-hour-time">
					        <span class="start"><?php echo !empty($this->company->business_hours)?$this->company->business_hours[$index*2]:"" ?></span> <span class="end"><?php echo !empty($this->company->business_hours) && !empty($this->company->business_hours[$index*2+1])?" - ".$this->company->business_hours[$index*2+1]:"" ?></span>
					    </span>
				    </div>
				<?php } ?>
			</table>
		</div>
	</fieldset>
</div>
