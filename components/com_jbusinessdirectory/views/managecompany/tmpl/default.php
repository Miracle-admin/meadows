<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$isProfile = true;

$user = JFactory::getUser();
if($user->id == 0){
	$app = JFactory::getApplication();
	$return = base64_encode(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompany'));
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return));
}

if($this->item->approved == COMPANY_STATUS_CLAIMED){
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanies'));
}

if(isset($this->item) && $this->item->approved == COMPANY_STATUS_CLAIMED){
	echo "<h3>".JText::_("LNG_COMPANY_NEED_CLAIM_APPROVAL")."</h3>";
}
include(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'company'.DS.'tmpl'.DS.'edit.php');
?>
<script>
	var isProfile = true;
</script>

<style>
#header-box, #control-panel-link{
	display: none;
}
</style>