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

class plgAcymailingTaguser extends JPlugin
{

	var $sendervalues =array();

	function plgAcymailingTaguser(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('acymailing', 'taguser');
			$this->params = new JParameter( $plugin->params );
		}
	}

	 function acymailing_getPluginType() {

		$app = JFactory::getApplication();
	 	if($this->params->get('frontendaccess') == 'none' && !$app->isAdmin()) return;
	 	$onePlugin = new stdClass();
	 	$onePlugin->name = JText::_('TAGUSER_TAGUSER');
	 	$onePlugin->function = 'acymailingtaguser_show';
	 	$onePlugin->help = 'plugin-taguser';

	 	return $onePlugin;
	 }

	 function acymailingtaguser_show(){?>

		<script language="javascript" type="text/javascript">
			function applyTag(tagname){
				var string = '{usertag:'+tagname;
				for(var i=0; i < document.adminForm.typeinfo.length; i++){
					 if (document.adminForm.typeinfo[i].checked){ string += '|info:'+document.adminForm.typeinfo[i].value; }
				}
				string += '}';
				setTag(string);
				insertTag();
			}
		</script>
	<?php
		$typeinfo = array();
		$typeinfo[] = JHTML::_('select.option', "receiver",JText::_('RECEIVER_INFORMATION'));
		$typeinfo[] = JHTML::_('select.option', "sender",JText::_('SENDER_INFORMATIONS'));
		echo JHTML::_('acyselect.radiolist', $typeinfo, 'typeinfo' , '', 'value', 'text', 'receiver');


		$notallowed = array('password','params','sendemail','gid','block','email','name','id');
		$text = '<table class="adminlist table table-striped table-hover" cellpadding="1">';
		$db = JFactory::getDBO();
		$fields = acymailing_getColumns('#__users');

	 	$descriptions['username'] = JText::_('TAGUSER_USERNAME');
	 	$descriptions['usertype'] = JText::_('TAGUSER_GROUP');
	 	$descriptions['lastvisitdate'] = JText::_('TAGUSER_LASTVISIT');
	 	$descriptions['registerdate'] = JText::_('TAGUSER_REGISTRATION');

		$k = 0;
		foreach($fields as $fieldname => $oneField){
			if(in_array(strtolower($fieldname),$notallowed)) continue;
			$type = '';
			if(strpos(strtolower($oneField),'date') !== false) $type = '|type:date';
			$text .= '<tr style="cursor:pointer" class="row'.$k.'" onclick="applyTag(\''.$fieldname.$type.'\');" ><td class="acytdcheckbox"></td><td>'.$fieldname.'</td><td>'.@$descriptions[strtolower($fieldname)].'</td></tr>';
			$k = 1-$k;
		}

		if(ACYMAILING_J16){
			$db->setQuery('SELECT DISTINCT `profile_key` FROM `#__user_profiles`');
			$extraFields = $db->loadObjectList();
			if(!empty($extraFields)){
				foreach($extraFields as $oneField){
					$text .= '<tr style="cursor:pointer" class="row'.$k.'" onclick="applyTag(\''.$oneField->profile_key.'|type:extra\');" ><td class="acytdcheckbox"></td><td>'.$oneField->profile_key.'</td><td></td></tr>';
					$k = 1-$k;
				}
			}
		}
		if(ACYMAILING_J30){
			$link = 'index.php/component/users/?task=registration.activate&token={usertag:activation|info:receiver}';
		} elseif(ACYMAILING_J16){
			$link = 'index.php?option=com_users&task=registration.activate&token={usertag:activation|info:receiver}';
		} else{
			$link = 'index.php?option=com_user&task=activate&activation={usertag:activation|info:receiver}';
		}
		$text .= '<tr style="cursor:pointer" class="row'.$k.'" onclick="setTag(\''.htmlentities('<a target="_blank" href="'.$link.'">'.JText::_('JOOMLA_CONFIRM_ACCOUNT').'</a>').'\'); insertTag();" ><td class="acytdcheckbox"></td><td>confirmJoomla</td><td>'.JText::_('JOOMLA_CONFIRM_LINK').'</td></tr>';

		$text .= '</table>';

		echo $text;
	}

	function acymailing_replaceusertags(&$email,&$user,$send = true)
	{
		$pluginsHelper = acymailing_get('helper.acyplugins');
		$extractedTags = $pluginsHelper->extractTags($email, 'usertag');
		if(empty($extractedTags)) return;

		$tags = array();
		$db = JFactory::getDBO();
		$receivervalues = array();
		foreach($extractedTags as $i => $mytag)
		{
			if(isset($tags[$i])) continue;
			$mytag->default = $this->params->get('default_'.$mytag->id,'');


			$values = new stdClass();
			$idused = 0;
			$save = false;

			if(!empty($mytag->info) && $mytag->info == 'sender' && !empty($email->userid)){
				$idused = $email->userid;
				$save = true;
			}
			if(!empty($mytag->info) && $mytag->info == 'current'){
				$my = JFactory::getUser();
				if(!empty($my->id)) $idused = $my->id;
			}
			if((empty($mytag->info) || $mytag->info == 'receiver') && !empty($user->userid)){
				$idused = $user->userid;
			}

			if(!empty($idused) && empty($this->sendervalues[$idused]) && empty($receivervalues[$idused])){
				$db->setQuery('SELECT * FROM '.acymailing_table('users',false).' WHERE id = '.intval($idused).' LIMIT 1');
				$receivervalues[$idused] = $db->loadObject();

				if(ACYMAILING_J16){
					$db->setQuery('SELECT * FROM #__user_profiles WHERE user_id = '.intval($idused));
					$receivervalues[$idused]->extraFields = $db->loadObjectList('profile_key');
				}

				if($save) $this->sendervalues[$idused] = $receivervalues[$idused];
			}


			if(!empty($this->sendervalues[$idused])) $values = $this->sendervalues[$idused];
			elseif(!empty($receivervalues[$idused])) $values = $receivervalues[$idused];

			if(empty($mytag->type) || $mytag->type != 'extra'){
				$replaceme = isset($values->{$mytag->id}) ? $values->{$mytag->id} : $mytag->default;
			} else{
				$replaceme = isset($values->extraFields[$mytag->id]) ? trim(json_decode($values->extraFields[$mytag->id]->profile_value),'"') : $mytag->default;
			}

			$tags[$i] = $replaceme;
			$pluginsHelper->formatString($tags[$i],$mytag);
		}

		$pluginsHelper->replaceTags($email, $tags);
	 }//endfct

 	function onAcyDisplayFilters(&$type,$context="massactions"){

		if($this->params->get('displayfilter_'.$context,true) == false) return;

		$db = JFactory::getDBO();
		$fields = acymailing_getColumns('#__users');
		if(empty($fields)) return;

		$type['joomlafield'] = JText::_('JOOMLA_FIELD');
		$type['joomlagroup'] = JText::_('ACY_GROUP');

		$field = array();
		$field[] = JHTML::_('select.option', 0, '- - -');
		foreach($fields as $oneField => $fieldType){
			$field[] = JHTML::_('select.option',$oneField,$oneField);
		}

		if(ACYMAILING_J16){
			$db->setQuery('SELECT DISTINCT `profile_key` FROM `#__user_profiles`');
			$extraFields = $db->loadObjectList();
			if(!empty($extraFields)){
				foreach($extraFields as $oneField){
					$field[] = JHTML::_('select.option','customfield_'.$oneField->profile_key,$oneField->profile_key);
				}
			}
		}

		$jsOnChange = "displayCondFilter('displayUserValues', 'toChange__num__',__num__,'map='+document.getElementById('filter__num__joomlafieldmap').value+'&cond='+document.getElementById('filter__num__joomlafieldoperator').value+'&value='+document.getElementById('filter__num__joomlafieldvalue').value); ";

		$operators = acymailing_get('type.operators');
		$operators->extra = 'onchange="'.$jsOnChange.'countresults(__num__)"';

		$return = '<div id="filter__num__joomlafield">'.JHTML::_('select.genericlist',   $field, "filter[__num__][joomlafield][map]", 'class="inputbox" size="1" onchange="'.$jsOnChange.'countresults(__num__)"', 'value', 'text');
		$return.= ' '.$operators->display("filter[__num__][joomlafield][operator]").' <span id="toChange__num__"><input onchange="countresults(__num__)" class="inputbox" type="text" name="filter[__num__][joomlafield][value]" id="filter__num__joomlafieldvalue" style="width:200px" value=""></span></div>';

		if(!ACYMAILING_J16){
			$acl = JFactory::getACL();
			$groups = $acl->get_group_children_tree( null, 'USERS', false );
		}else{
			$db = JFactory::getDBO();
			$db->setQuery('SELECT a.*, a.title as text, a.id as value  FROM #__usergroups AS a ORDER BY a.lft ASC');
			$groups = $db->loadObjectList('id');
			foreach($groups as $id => $group){
				if(isset($groups[$group->parent_id])){
					$groups[$id]->level = intval(@$groups[$group->parent_id]->level) + 1;
					$groups[$id]->text = str_repeat('- - ',$groups[$id]->level).$groups[$id]->text;
				}
			}
		}

		$inoperator = acymailing_get('type.operatorsin');
		$inoperator->js = 'onchange="countresults(__num__)"';

		$return .= '<div id="filter__num__joomlagroup">'.$inoperator->display("filter[__num__][joomlagroup][type]").' '.JHTML::_('select.genericlist',   $groups, "filter[__num__][joomlagroup][group]", 'class="inputbox" size="1" onchange="countresults(__num__)"', 'value', 'text').'</div>';

	 	return $return;
	 }

	 function onAcyTriggerFct_displayUserValues(){
 		$num = JRequest::getInt('num');
		$map = JRequest::getCmd('map');
		$cond = JRequest::getVar('cond','','','string',JREQUEST_ALLOWHTML);
		$value = JRequest::getVar('value','','','string',JREQUEST_ALLOWHTML);

		$emptyInputReturn = '<input onchange="countresults('.$num.')" class="inputbox" type="text" name="filter['.$num.'][joomlafield][value]" id="filter'.$num.'joomlafieldvalue" style="width:200px" value="'.$value.'">';
		$dateInput = '<input onclick="displayDatePicker(this,event)" onchange="countresults('.$num.')" class="inputbox" type="text" name="filter['.$num.'][joomlafield][value]" id="filter'.$num.'joomlafieldvalue" style="width:200px" value="'.$value.'">';

		if(in_array($map, array('registerDate', 'lastvisitDate', 'lastResetTime'))) return $dateInput;

		if(empty($map) || in_array($map, array('password', 'params', 'optKey', 'otep')) || !in_array($cond,array('=','!='))) return $emptyInputReturn;

		$db = JFactory::getDBO();
		$db->setQuery('SELECT DISTINCT `'.acymailing_secureField($map).'` AS value FROM #__users LIMIT 100');
 		$prop = $db->loadObjectList();

 		if(empty($prop) || count($prop) >= 100 || (count($prop) == 1 && (empty($prop[0]->value) || $prop[0]->value == '-'))) return $emptyInputReturn;

 		return JHTML::_('select.genericlist', $prop, "filter[$num][joomlafield][value]", 'onchange="countresults('.$num.')" class="inputbox" size="1" style="width:200px"', 'value', 'value', $value, 'filter'.$num.'joomlafieldvalue');
	}

	function onAcyProcessFilterCount_joomlafield(&$query,$filter,$num){
	 	$this->onAcyProcessFilter_joomlafield($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	 }

	 function onAcyDisplayFilter_joomlafield($filter){
		return JText::_('JOOMLA_FIELD').' : '.$filter['map'].' '.$filter['operator'].' '.$filter['value'];
	 }


	function onAcyProcessFilter_joomlafield(&$query,$filter,$num){
		if(empty($filter['map'])) return;
		$type = '';
		if(strpos($filter['map'],'customfield_') !== false){
			$query->leftjoin['joomlauserprofiles'.$num] = '#__user_profiles AS joomlauserprofiles'.$num.' ON joomlauserprofiles'.$num.'.user_id = sub.userid AND joomlauserprofiles'.$num.'.profile_key = '.$query->db->Quote(str_replace('customfield_','',$filter['map']));
			$val = trim($filter['value'],'"');
			if(in_array($filter['operator'],array('=','!=','<','>','<=','>=','BEGINS','LIKE','NOT LIKE'))){
				$val = '"'.$val;
			}
			if(in_array($filter['operator'],array('=','!=','<','>','<=','>=','END','LIKE','NOT LIKE'))){
				$val = $val.'"';
			}

			$query->where[] = $query->convertQuery('joomlauserprofiles'.$num,'profile_value',$filter['operator'],$val, $type);
		}else{
			$query->leftjoin['joomlauser'] = '#__users AS joomlauser ON joomlauser.id = sub.userid';
		 	if(in_array($filter['map'],array('registerDate','lastvisitDate'))){
		 		$filter['value'] = acymailing_replaceDate($filter['value']);
		 		if(!is_numeric($filter['value'])) $filter['value'] = strtotime($filter['value']);
				$filter['value'] = strftime('%Y-%m-%d %H:%M:%S',$filter['value']);
				$type = 'datetime';
			}
		 	$query->where[] = $query->convertQuery('joomlauser',$filter['map'],$filter['operator'],$filter['value'], $type);
		}
	 }

	function onAcyProcessFilterCount_joomlagroup(&$query,$filter,$num){
		$this->onAcyProcessFilter_joomlagroup($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	}

	function onAcyProcessFilter_joomlagroup(&$query,$filter,$num){
		$operator = (empty($filter['type']) || $filter['type'] == 'IN') ? 'IS NOT NULL' : "IS NULL";
		if(!ACYMAILING_J16){
			$query->leftjoin['joomlauser'.$num] = "#__users AS joomlauser$num ON joomlauser$num.id = sub.userid AND joomlauser$num.gid = ".intval($filter['group']);
	 		$query->where[] = "joomlauser$num.id ".$operator;
		}else{
			$query->leftjoin['joomlauser'.$num] = "#__user_usergroup_map AS joomlauser$num ON joomlauser$num.user_id = sub.userid AND joomlauser$num.group_id = ".intval($filter['group']);
	 		$query->where[] = "joomlauser$num.user_id ".$operator;
		}

	 }
}//endclass
