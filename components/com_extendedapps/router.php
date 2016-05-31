<?php
if ( defined('_JEXEC') && !defined('JOOBI_SECURE') ) define( 'JOOBI_SECURE', true );
defined('JOOBI_SECURE') or die('J....');
/**
* @link joobi.co
* @copyright Copyright (C) 2006-2015 Joobi Limited All rights reserved.
* This file is released under the GPLv3
*/
function ExtendedappsBuildRoute(&$query){
if(!class_exists("WPage")){
$status=include(JPATH_ROOT.DIRECTORY_SEPARATOR."joobi".DIRECTORY_SEPARATOR."entry.php");
if (!$status) return;
}
return WPage::buildURL($query);
}//endfct

function ExtendedappsParseRoute($segments){
if (!class_exists("WPage")){
$status=include(JPATH_ROOT.DIRECTORY_SEPARATOR."joobi".DIRECTORY_SEPARATOR."entry.php");
if (!$status) return;
}
return WPage::interpretURL($segments);
}//endfct
