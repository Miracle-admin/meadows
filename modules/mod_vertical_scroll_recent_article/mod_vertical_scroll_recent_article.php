<?php
/**
 * Vertical scroll recent article
 *
 * @package Vertical scroll recent article
 * @subpackage Vertical scroll recent article
 * @version   3.4
 * @author    Gopi Ramasamy
 * @copyright Copyright (C) 2010 - 2015 www.gopiplus.com, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
 
// Lide Demo : http://www.gopiplus.com/extensions/2011/06/vertical-scroll-recent-article-joomla-module/
// Technical Support : http://www.gopiplus.com/extensions/2011/06/vertical-scroll-recent-article-joomla-module/

// no direct access
defined('_JEXEC') or die;

require_once(dirname(__FILE__).'/helper.php');
$items = modVerticalScrollRecentArticleHelper::getArticleList($params);
modVerticalScrollRecentArticleHelper::loadScripts($params);
require(JModuleHelper::getLayoutPath('mod_vertical_scroll_recent_article'));
?>