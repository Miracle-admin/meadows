<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 April 2013
 * @file name	:	plugins/system/jblanceexpiry/jblanceexpiry.php
 * @copyright   :	Copyright (C) 2012 - 2014 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	This plugin streams JoomBri activities on to JomSocial wall.
 */
 defined('_JEXEC') or die('Restricted access');

 //JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_jblance/tables');
 require_once(JPATH_ADMINISTRATOR.'/components/com_jblance/helpers/jblance.php');

class plgSystemJblanceExpiry extends JPlugin {

	function plgSystemJblanceExpiry(&$subject, $config){
		parent::__construct($subject, $config);
		JPlugin::loadLanguage('plg_system_jblanceexpiry', JPATH_ADMINISTRATOR);	//Load Language file.
	}


	function onAfterInitialise(){
		@ini_set("max_execution_time", 0);	//to avoid php max execution time as sending will take time.
		
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
		
		$alertbefore  = $this->params->get('alertbefore', 5);
		//$alertsubscr  = $this->params->get('alertsubscr', 0);
		$alertproject = $this->params->get('alertproject', 1);
		$db			  = JFactory::getDbo();
		
		//get a list of subscription expiry records
	   /*  $query = "
			    SELECT u.email, u.name as uname, u.id as uid, ps.id, ps.user_id, a.id AS aid FROM #__jblance_plan_subscr AS ps 
	 			LEFT JOIN #__jblance_expiry_alert AS a ON a.subscr_project_id = ps.id AND type='subscr'
				LEFT JOIN #__users AS u ON u.id = ps.user_id 
				WHERE ps.date_expire < CURDATE() + INTERVAL $alertbefore DAY AND ps.date_expire > CURDATE()
				GROUP BY ps.id";
	     $db->setQuery($query);//echo $query;
	     $rows = $db->loadObjectList(); */
	     
	     //send subscription expiry alert
	     /* if($alertsubscr){
		     if(is_array($rows)){
		        foreach($rows AS $row){
		            if(!$row->aid){
		            	$helper->sendExpiryEmail($row->email, $row->uname, 'subscr', $row->id, $this->params);
		                $query = "INSERT INTO #__jblance_expiry_alert (`subscr_project_id`, `user_id`, `type`, `sent_time`) VALUES ('{$row->id}', '{$row->uid}', 'subscr', NOW())";
		                $db->setQuery($query);
		                $db->query();
					}
				}
			}
		} */
		
		//get a list of project expiry records
		$query = "
				SELECT u.email, u.name as uname, u.id as uid, p.id, p.publisher_userid, a.id AS aid FROM #__jblance_project AS p
				LEFT JOIN #__jblance_expiry_alert AS a ON a.subscr_project_id = p.id AND type='project'
				LEFT JOIN #__users AS u ON u.id = p.publisher_userid 
				WHERE DATE_ADD(p.start_date, INTERVAL p.expires DAY) < DATE_ADD(NOW(), INTERVAL $alertbefore DAY) AND DATE_ADD(p.start_date, INTERVAL p.expires DAY) > NOW() AND p.STATUS='COM_JBLANCE_OPEN'
				GROUP BY p.id";//echo $query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		//send project expiry alert
		if($alertproject){
			if(is_array($rows)){
				foreach($rows AS $row){
					if(!$row->aid){
						$jbmail->sendExpiryEmail($row->email, $row->uname, 'project', $row->id);
						$query = "INSERT INTO #__jblance_expiry_alert (`subscr_project_id`, `user_id`, `type`, `sent_time`) VALUES ('{$row->id}', '{$row->uid}', 'project', NOW())";
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}
		
		// select the expired projects that are still open
	    //$query = "SELECT p.* FROM #__jblance_project p WHERE (TO_DAYS(p.start_date + INTERVAL p.expires DAY) - TO_DAYS(NOW())) < 0 AND p.status='COM_JBLANCE_OPEN'";
		$query = "SELECT p.* FROM #__jblance_project p WHERE (DATE_ADD(p.start_date, INTERVAL p.expires DAY) - NOW()) < 0 AND p.STATUS='COM_JBLANCE_OPEN'";
		$db->setQuery($query);//echo $query;
		$rows = $db->loadObjectList();
		
		// set the previous projects to 'COM_JBLANCE_EXPIRED'
		foreach($rows as $row){
			$query = "UPDATE #__jblance_project SET status = 'COM_JBLANCE_EXPIRED' WHERE id = '$row->id'";
			$db->setQuery($query);
			$db->Query();
		}
	}	//end of function
}	//end of class
?>