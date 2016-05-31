<?php
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';
$userInfo = ModMeadowsLoginHelper::getuserInfo();
		
			
$user	= JFactory::getUser();
$layout = $params->get('layout', 'default');
require JModuleHelper::getLayoutPath('mod_meadowslogin', $layout);
