<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancelatest/helper.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');	//include this helper file to make the class accessible in all other PHP files
 include_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/link.php');	//include this helper file to make the class accessible in all other PHP files
 JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');

class ModJblanceLatestprojectHelper {	
	
	public static function getLatestProjects($total_row, $limit_title,$limit_desc,$project_upgrade){
		$db	  = JFactory::getDbo();
		$user = JFactory::getUser();
		$now  = JFactory::getDate();
		
		$pjUpgrade="";
		
		switch ($project_upgrade) {
        case 0:
        $pjUpgrade="";
        break;
        case 1:
        $pjUpgrade="AND p.is_featured=1";
        break;
        case 2:
        $pjUpgrade="AND p.is_urgent=1";
        break;
		case 3:
        $pjUpgrade="AND p.is_private=1";
        break;
		case 4:
        $pjUpgrade="AND p.is_sealed=1";
        break;
        case 5:
        $pjUpgrade="AND p.is_assisted=1";
        break;
      
            }
	
		
	
		$query = "SELECT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p ".
				 "WHERE p.status=".$db->quote('COM_JBLANCE_OPEN')." AND p.approved=1 ".$pjUpgrade."  AND '$now' > p.start_date ".
				 "ORDER BY p.is_featured DESC, p.id DESC ".
				 "LIMIT 0,". $total_row ;
				 
	   
		$db->setQuery($query);
		$db->execute();
		$total = $db->getNumRows();
	
		$db->setQuery($query);
		$rows2 = $db->loadObjectList();
		
		$rows = null;
		if(count($rows2)){
			$i = 0;
			foreach($rows2 as $row){
			
			    $pub=$row->publisher_userid;
			    $publisher=JFactory::getUser($pub);
				$valid=$publisher->emailvalid==""?true:false;
				if($valid)
				{
				$rows[$i] = new stdClass();
				$row->project_title = self::limitTitle($row->project_title, $limit_title);
				$row->description=self::limitDescription($row->description, $limit_desc);
				
				$rows[$i] = $row;
				$rows[$i]->categories = self::getCategoryNames($row->id_category);
				$rows[$i]->bids = self::countBids($row->id);
				}
				$i++;
			}
		}
	
		return $rows;
	}
	
	public static function countBids($id){
		$db = JFactory::getDbo();
		$row = JTable::getInstance('project', 'Table');
		$row->load($id);
		
		//for nda projects, bid count should include only signed bids
		$ndaQuery = 'TRUE';
		if($row->is_nda)
			$ndaQuery = "is_nda_signed=1";
		
		$query = "SELECT COUNT(*) FROM #__jblance_bid WHERE project_id = $id AND $ndaQuery";
		$db->setQuery($query);
		$total = $db->loadResult();
		return $total;
	}
	
	public static function limitTitle($title, $limit_title){
		$len = strlen($title);
		if($len < $limit_title || $limit_title == 0){
			return $title;
		}
		else {
			$trimmed = substr($title, 0, $limit_title);
			return $trimmed.'...';
		}
	}
	
		public static function limitDescription($description, $limit_desc){
		$len = strlen($description);
		if($len < $limit_desc || $limit_desc == 0){
			return $description;
		}
		else {
			$trimmed = substr($description, 0, $limit_desc);
			return $trimmed.'...';
		}
	}
	
	public static function getCategoryNames($id_categs){
		$db = JFactory::getDbo();
		$query = "SELECT category,id FROM #__jblance_category c WHERE c.id IN ($id_categs)";
		$db->setQuery($query);
		$cats = $db->loadColumn();
		return implode($cats, ", ");
	}
	
}

?>