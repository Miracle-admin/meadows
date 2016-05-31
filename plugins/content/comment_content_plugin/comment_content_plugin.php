<?php
/**
* @link joobi.co
* @copyright Copyright (C) 2006-2015 Joobi Limited All rights reserved.
* This file is released under the GPL v3
*/
if (!isset($_SESSION['joobi']['first_install'])){ 
$joobiEntryPoint = __FILE__ ;
$status = @include(JPATH_ROOT.DIRECTORY_SEPARATOR. 'joobi'.DIRECTORY_SEPARATOR.'entry.php');
if (!$status && !defined('JOOBI_DS_INSTALLFOLDER'))
echo "We were unable to load Joobi library for the plugin Joobi - Joomla Articles Commeting. If you removed the joobi folder, please also remove this plugin from the Joomla plugin manager.";
}