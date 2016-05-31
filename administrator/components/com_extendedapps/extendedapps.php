<?php
/**
* @link joobi.co
* @copyright Copyright (C) 2006-2015 Joobi Limited All rights reserved.
* This file is released under the GPL v3
*/

$joobiEntryPoint = __FILE__ ;
$status = @include(JPATH_ROOT.DIRECTORY_SEPARATOR. 'joobi'.DIRECTORY_SEPARATOR.'entry.php');
if (!$status && !defined('JOOBI_DS_INSTALLFOLDER'))
echo "We were unable to load Joobi library for the component Tools. If you removed the joobi folder, please also remove this component from the Joomla component manager.";
