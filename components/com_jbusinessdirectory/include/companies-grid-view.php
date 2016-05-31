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

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$enableNumbering = $appSettings->enable_numbering;
$user = JFactory::getUser();

$showData = !($user->id==0 && $appSettings->show_details_user == 1);
?>
<div id="jbd-grid-view" <?php echo !$this->appSettings->search_view_mode?'style="display: none"':'' ?>>
<?php 	
	if($this->appSettings->search_result_grid_view == 2){
		require_once JPATH_COMPONENT_SITE.'/include/companies-grid-view-style2.php';
	}else{ 
		require_once JPATH_COMPONENT_SITE.'/include/companies-grid-view-style1.php';
	}
?>
</div>