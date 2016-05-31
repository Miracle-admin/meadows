<?php
/*
 * @title		VM - Custom, Downloadable Products
 * @version		3.3
 * @package		Joomla
 * @author		ekerner@ekerner.com.au
 * @website		http://www.ekerner.com.au
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * @copyright		Copyright (C) 2012 eKerner.com.au All rights reserved.
 */

define('_JEXEC', true); // Not only for JED
defined('_JEXEC') or die('Restricted access!'); // for JED do not remove
define('JPATH_BASE', realpath(dirname(__FILE__).'/../../..'));

require_once ( JPATH_BASE.'/includes/defines.php' );
require_once ( JPATH_BASE.'/includes/framework.php' );

$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

$usage = 'Permission denied!';
if (!($_GET['uid'] && $_GET['pid']))
	die($usage);

$session = JFactory::getSession();
$session_key = 'vmdownloadable_' . $_GET['uid'] . '_' . $_GET['pid'];
$filePath = $session->get($session_key);
if (!($filePath && file_exists($filePath)))
	die($usage);

$fileName = basename($filePath);
$fileSize = filesize($filePath);
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$fileMime = finfo_file($finfo, $filePath);
finfo_close($finfo);
if (substr($fileMime, 0, 4) == 'text') {
	$fileEnc = 'quoted-printable';
	$fileMode = 'r';
}
else {
	$fileEnc = 'binary';
	$fileMode = 'rb';
}

$file = fopen($filePath, $fileMode);
if(!$file)
	die($usage);

header('Content-Type: ' . $fileMime);
header('Content-Length: ' . $fileSize);
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Transfer-Encoding: ' . $fileEnc);

$read_bytes = 1024 * 8;
while (!feof($file) && connection_status() == 0) {
	echo fread($file, $read_bytes);
	flush();
}
fclose($file);

?>
