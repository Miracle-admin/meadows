<?php
defined('_JEXEC') or die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/*
* featured/Latest/Topten/Random Products Module
*
* @version $Id: mod_virtuemart_product.php 2789 2011-02-28 12:41:01Z oscar $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) 2010 - Patrick Kohl
* @copyright (C) 2011 - The VirtueMart Team
* @author Max Milbers, Valerie Isaksen, Alexander Steiner
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*/


    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
	
	// Include the syndicate functions only once
    require_once __DIR__ . '/helper.php';

    VmConfig::loadConfig();

    VmConfig::loadJLang('mod_virtuemart_producthome', true);
    
	
	
	
	
	
	
	//$products=modVirtuemartProducthomeHelper::productList($params);
	
	require(JModuleHelper::getLayoutPath('mod_virtuemart_producthome',$params->get('layout','default')));
	
    echo vmJsApi::writeJS();
    ?>
