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
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldMulticountries extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
    public $type = 'multicountries'; 
	protected function getInput()
	{
		$this->multiple=true;
		return parent::getInput();
	}
	protected function getOptions()
	{
		$options = array();
		$this->multiple=true;

		$q = 'SELECT `virtuemart_country_id` AS value, `country_name` AS text FROM `#__virtuemart_countries` WHERE `published` = 1 ORDER BY `country_name` ASC ';
		$db = JFactory::getDBO();
		$db->setQuery($q);
		$values = $db->loadObjectList();
		foreach ($values as $v)
		{
			$options[] = JHtml::_('select.option', $v->value, $v->text);
		}
		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
} 
?> 