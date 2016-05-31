<?php 
/*
 * @component com_vm2wishlists
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

 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldMyname extends JFormField 
 { 
     /** 
      * The form field type. 
      */ 
    public $type = 'myname'; 
	protected function getInput()
	{
		$user = JFactory::getUser();
		$html[] = '<input type="text" 
		name="' . $this->name . '" 
		value="' . $user->name . '" 
		class="validate inputbox required required" />';
		
		return  implode($html);
	}
} 
?> 