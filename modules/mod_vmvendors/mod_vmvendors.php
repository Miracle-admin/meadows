<?php
/*
 * @component VMVendor
 * @copyright Copyright (C) 2010-2015 Adrien Roussel
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__).'/helper.php';
$sortby = trim( $params->get('sortby', 0) );
$cparams 					= JComponentHelper::getParams('com_vmvendor');
$enable_rating			= $cparams->get('enable_rating',1);
		if($sortby ==0 && $enable_rating)
			$sortby = rand(1,4);
		elseif($sortby ==0)
			$sortby = rand(1,3);

$vendors	= ModVMVendorsHelper::getVendors($params,$sortby);
$moduleclass_sfx = trim( $params->get('moduleclass_sfx') );
$use_fontello	= trim( $params->get('use_fontello',1) );



require( JModuleHelper::getLayoutPath( 'mod_vmvendors' ) );
?>