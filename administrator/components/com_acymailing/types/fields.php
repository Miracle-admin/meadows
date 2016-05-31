<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class fieldsType{
	var $allValues;

	var $allTypes;
	function fieldsType(){
		$this->allValues = array();
		$this->allValues["text"] = JText::_('FIELD_TEXT');
		$this->allValues["textarea"] = JText::_('FIELD_TEXTAREA');
		$this->allValues["radio"] = JText::_('FIELD_RADIO');
		$this->allValues["checkbox"] = JText::_('FIELD_CHECKBOX');
		$this->allValues["singledropdown"] = JText::_('FIELD_SINGLEDROPDOWN');
		$this->allValues["multipledropdown"] = JText::_('FIELD_MULTIPLEDROPDOWN');
		$this->allValues["date"] = JText::_('FIELD_DATE');
		$this->allValues["birthday"] = JText::_('FIELD_BIRTHDAY');
		$this->allValues["file"] = JText::_('FIELD_FILE');
		$this->allValues["phone"] = JText::_('FIELD_PHONE');
		$this->allValues["customtext"] = JText::_('CUSTOM_TEXT');
		$this->allValues["gravatar"] = 'Gravatar';

		$this->allTypes = array();
		$this->allTypes['text'] = array('size','required','default','columnname','checkcontent');
		$this->allTypes['textarea'] = array('cols','rows','required','default','columnname');
		$this->allTypes['radio'] = array('multivalues','required','default','columnname','dbValues');
		$this->allTypes['checkbox'] = array('multivalues','required','default','columnname','dbValues');
		$this->allTypes['singledropdown'] = array('multivalues','required','default','columnname','size','dbValues');
		$this->allTypes['multipledropdown'] = array('multivalues','required','size','default','columnname','dbValues');
		$this->allTypes['date'] = array('required','format','size','default','columnname');
		$this->allTypes['birthday'] = array('required','format','default','columnname');
		$this->allTypes['file'] = array('columnname','required','size');
		$this->allTypes['phone'] = array('columnname','required','size','default');
		$this->allTypes['customtext'] = array('customtext');
		$this->allTypes['gravatar'] = array('columnname','required','size');

		JPluginHelper::importPlugin('acymailing');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAcyCreateField', array(&$this->allValues, &$this->allTypes));
	}

	function display($map,$value){
		$js = "function updateFieldType(){
			newType = document.getElementById('fieldtype').value;
			hiddenAll = new Array('multivalues','cols','rows','size','required','format','default','customtext','columnname','checkcontent','dbValues');
			allTypes = new Array();
			";

			foreach($this->allTypes as $type => $detail){
				$js .= "allTypes['" . $type ."'] = new Array('" . implode("','", $detail) . "');
				";
			}

			$js .= "for (var i=0; i < hiddenAll.length; i++){
				$$('tr[class='+hiddenAll[i]+']').each(function(el) {
					el.style.display = 'none';
				});
			}

			for (var i=0; i < allTypes[newType].length; i++){
				$$('tr[class='+allTypes[newType][i]+']').each(function(el) {
					el.style.display = '';
				});
			}
		}
		window.addEvent('domready', function(){ updateFieldType(); });";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );

		$this->values = array();
		foreach($this->allValues as $oneType => $oneVal){
			$this->values[] = JHTML::_('select.option', $oneType,$oneVal);
		}


		return JHTML::_('select.genericlist', $this->values, $map , 'size="1" onchange="updateFieldType();"', 'value', 'text', (string) $value,'fieldtype');
	}
}
