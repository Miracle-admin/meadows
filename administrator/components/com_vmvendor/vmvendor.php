<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
 
 defined('_JEXEC') or die('Restricted access');
 // Access check.
 
 // Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_vmvendor')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}


// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Vmvendor');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();


JToolBarHelper::title(   JText::_( 'VMVendor - The multivendor solution for Virtuemart' ), 'cpanel.png' );
$db 						= JFactory::getDBO();
$app						= JFactory::getApplication();




$q ="SELECT count(*) FROM `#__extensions` WHERE `name`='virtuemart' AND `type`='component' AND `element`='com_virtuemart' AND `enabled`='1' ";
$db->setQuery($q);
$isVirtuemart = $db->loadResult();
if($isVirtuemart>0)
{
	
	
}
else
{  // VM not installed
	JError::raiseWarning('100' ,  'You must have Virtuemart component installed to use VMVendor. <a href="http://www.virtuemart.net" target="_blank" class="btn btn-mini">Get it free here</a>  <a href="index.php?option=com_installer#upload" class="btn btn-mini">Install it here</a>'  );
	
}




$q ="SELECT count(*) FROM `#__extensions` WHERE `folder`='vmpayment' AND `type`='plugin' AND `element`='points2vendor' AND `enabled`='1' ";
$db->setQuery($q);
$isPayplugin = $db->loadResult();
if($isPayplugin>0){

}
else
	echo '<div class="badsetup" ><img src="components/com_vmvendor/assets/img/file.png" width="12" height="12" align="absmiddle" /> Not ready <img src="components/com_vmvendor/assets/img/bad.png" width="12" height="12" /></div>';

	
	
echo '</div>';






echo '<div style="text-align:center;">
<a href="http://www.nordmograph.com/extensions/index.php?option=com_kunena&view=category&catid=22&Itemid=108" >VMVendor Support Forum</a>  
| <a href="http://www.nordmograph.com/extensions/index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=56&virtuemart_category_id=3&Itemid=58" >VM2tags</a>  
| <a href="http://www.nordmograph.com/extensions/index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=15&virtuemart_category_id=4&Itemid=58" >VM2share</a> </div>';
echo '<div style="text-align:center;">VMVendor Component Version 3.0 - Copyright <a href="http://www.nordmograph.com/extensions" >Nordmograph.com/extensions</a> - 2010-'.date('Y').' ®  All rights reserved.<div>';