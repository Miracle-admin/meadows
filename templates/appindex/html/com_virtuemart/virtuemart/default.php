<?php
	/**
	 *
	 * Description
	 *
	 * @package	VirtueMart
	 * @subpackage
	 * @author
	 * @link http://www.virtuemart.net
	 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
	 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	 * VirtueMart is free software. This version may have been modified pursuant
	 * to the GNU General Public License, and as distributed it includes or
	 * is derivative of works licensed under the GNU General Public License or
	 * other free or open source software licenses.
	 * @version $Id: default.php 8847 2015-05-06 12:22:37Z Milbo $
	 */
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	?>

<div class="hding-app-mar">
  <div class="container">
    <h4>Featured Products</h4>
    <p> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusatium doloremque laudantium</p>
    <?php
	$moduleDa = JModuleHelper::getModule('mod_featured_products', "");
	echo $featured_products = JModuleHelper::renderModule($moduleDa);
	?>
  </div>
</div>
<div class="latest-pro-wrp hding-app-mar">
  <div class="container">
    <h4>Latest Products</h4>
    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusatium doloremque laudantium</p>
    <?php
	$moduleData = JModuleHelper::getModule('mod_latest_products', "");
	echo $latest_products = JModuleHelper::renderModule($moduleData);
	?>
  </div>
</div>
<div class="hding-app-mar rec-sold">
  <div class="container">
    <h4>Recently Sold Products</h4>
    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusatium doloremque laudantium</p>
    <?php
	$moduleData1 = JModuleHelper::getModule('mod_recentsoldproducts', "");
	echo $mod_recentsoldproducts = JModuleHelper::renderModule($moduleData1);
	?>
  </div>
</div>
