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
 //JFormHelper::loadFieldClass('sql'); 
  
if (!class_exists( 'VmConfig' ))
 	require JPATH_ROOT.'/administrator/components/com_virtuemart/helpers/config.php';
if (!class_exists('ShopFunctions'))
	require VMPATH_ADMIN .'/helpers/shopfunctions.php';
if (!class_exists ('VirtueMartModelCalc')) {
          require(VMPATH_ADMIN . '/models/calc.php');
        }
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldShipmenttaxrules extends JFormFieldSQL 
 { 
     /** 
      * The form field type. 
      */ 
    public $type = 'shipmenttaxrules'; 
	protected function getInput()
	{
    $vendor_id =  JFormFieldShipmenttaxrules::getVendorid();
    $kind       = array('TAX','VatTax','TaxBill');
    $db = JFactory::getDBO();

    $nullDate   = $db->getNullDate();
    $now      = JFactory::getDate()->toSQL();

    $q = "SELECT * FROM `#__virtuemart_calcs` WHERE `virtuemart_vendor_id` ='".$vendor_id."' AND (";
    foreach ($kind as $field){
      $q .= "`calc_kind`=".$db->Quote($field)."OR ";
    }
    $q=substr($q,0,-3);

   $q .= ') AND ( publish_up = "' . $db->escape($nullDate) . '" OR publish_up <= "' . $db->escape($now) . '" )
        AND ( publish_down = "' . $db->escape($nullDate) . '" OR publish_down >= "' . $db->escape($now) . '" ) ';



    $db->setQuery($q);
    $data = $db->loadObjectList();

    $name = 'shipmenttaxrules';
    $class='';

    $selected='';
    
   //$taxrates = array();
    //$taxrates[] = JHtml::_ ('select.option', '-1', vmText::_ ('COM_VIRTUEMART_PRODUCT_TAX_NONE'), $name);
    //$taxrates[] = JHtml::_ ('select.option', '0', vmText::_ ('COM_VIRTUEMART_PRODUCT_TAX_NO_SPECIAL'), $name);
    //foreach ($data as $tax) {
      //$taxrates[] = JHtml::_ ('select.option', $tax->virtuemart_calc_id, $tax->calc_name, $name);
    //}
    //$listHTML = JHtml::_ ('Select.genericlist', $taxrates, $name, $class, $name, 'text', $selected);
    //return $listHTML;
    //
    echo '<select  name = "'.$this->name.'"  id="'.$this->id.'" class="catseleclist" >';
     echo '<option value="-1">'.vmText::_ ('COM_VIRTUEMART_PRODUCT_TAX_NONE').'</option>';
      echo '<option value="0">'.vmText::_ ('COM_VIRTUEMART_PRODUCT_TAX_NO_SPECIAL').'</option>';
    foreach ($data as $tax) {
      //$taxrates[] = JHtml::_ ('select.option', $tax->virtuemart_calc_id, $tax->calc_name, $name);
      echo '<option value="'.$tax->virtuemart_calc_id.'"';
      echo '';
       echo ' >'.$tax->calc_name.'</option>';
    }
    echo '</select>';

	}

  public function getVendorid() 
  {
    $db   = JFactory::getDBO();
    $user   = JFactory::getUser();
    $q = "SELECT `virtuemart_vendor_id` FROM `#__virtuemart_vmusers` WHERE `virtuemart_user_id` = '".$user->id."' ";
    $db->setQuery($q);
    $virtuemart_vendor_id = $db->loadResult();
    $this->_virtuemart_vendor_id = $virtuemart_vendor_id;
    return $this->_virtuemart_vendor_id;
  }
} 
?> 