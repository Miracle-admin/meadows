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

<div id="company-locations">
<div class="company-location" id="location"><?php echo JBusinessUtil::getAddressText($this->company)?></div>
<?php foreach($this->company->locations as $location){
	$location->publish_only_city = false;
?>
	<div class="company-location" id="location-<?php echo $location->id?>"><?php echo $location->name." - ".JBusinessUtil::getAddressText($location)."&nbsp;&nbsp;&nbsp;  <i class='dir-icon-phone'></i> ".$location->phone?></div>
<?php }?>
</div>
