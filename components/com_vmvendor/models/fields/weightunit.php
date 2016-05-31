<?php 
/*
 * @component com_vmvendor
 * @copyright Copyright (C) 2010-2015 Adrien ROUSSEL Nordmograph.com
 * @license GNU/GPL Version 3
 * @Website : http://www.nordmograph.com/extensions
 */

// used in the editshipment form
  
 defined('JPATH_BASE') or die; 
 /* 
 jimport('joomla.html.html'); 
 jimport('joomla.form.formfield'); 
 jimport('joomla.form.helper'); 
 */ 
 JFormHelper::loadFieldClass('sql'); 
  
 if (!class_exists( 'VmConfig' ))
 	require JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/config.php';
if (!class_exists('ShopFunctions'))
	require VMPATH_ADMIN .'/helpers/shopfunctions.php';
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldWeightunit extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
    public $type = 'weightunit'; 
	protected function getInput()
	{
		return ShopFunctions::renderWeightUnitList($this->name, $this->value);
	}
} 
?> 