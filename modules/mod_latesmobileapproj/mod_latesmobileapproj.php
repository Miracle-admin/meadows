<?php defined('_JEXEC') or die;
/**
 * File       mod_latesmobileapproj.php
 * Created    1/17/14 12:29 PM
 * Author     Tarun Kumar | tarunkumar07@yahoo.com | http://betweenbrain.com
 * License    GNU General Public License version 2, or later.
 */

// Include the helper.
require_once __DIR__ . '/helper.php';
$latest = modLatest::getLatest($params);
require(JModuleHelper::getLayoutPath('mod_latesmobileapproj'));
?>