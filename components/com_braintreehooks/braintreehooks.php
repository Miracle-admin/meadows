<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/braintreehooks.php';
require_once JPATH_COMPONENT_SITE.'/helpers/route.php';

$controller	= JControllerLegacy::getInstance('Braintreehooks');
$input = JFactory::getApplication()->input;

$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);

JHtml::_('bootstrap.loadCss');
JHtml::_('bootstrap.framework');

try {
	$controller->execute($input->get('task'));
} catch (Exception $e) {
	$controller->setRedirect(JURI::base(), $e->getMessage(), 'error');
}

$controller->redirect();
?>