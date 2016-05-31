<?php
/**
 * @version 2.2
 * @package Joomla 3.x
 * @subpackage RS-Monials
 * @copyright (C) 2013-2022 RS Web Solutions (http://www.rswebsols.com)
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }

require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rssettings.php");
require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."includes".DS."admin.rsfunctions.php");

if(!isset($_REQUEST['view'])){$_REQUEST['view']='testi';}
if(!isset($_REQUEST['action'])){$_REQUEST['action']='';}
if(!isset($_REQUEST['page'])){$_REQUEST['page']=0;}
if(!isset($_REQUEST['limit'])){$_REQUEST['limit']=0;}

$view=$_REQUEST['view'];

switch($view) {
	case 'conf':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rssettings.php");
		break;
	case 'style':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsstylesettings.php");
		break;
	case 'css':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsstyle.php");
		break;
	case 'lang':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rslanguage.php");
		break;
	case 'spam':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsspam.php");
		break;
	case 'doc':
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rsdocumentation.php");
		break;
	case 'testi':
	default:
		require_once(JPATH_BASE.DS."components".DS."com_rsmonials".DS."admin.rstestimonials.php");
		break;
}
?>