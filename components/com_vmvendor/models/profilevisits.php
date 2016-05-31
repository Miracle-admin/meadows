<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class VmvendorModelProfilevisits extends JModelItem
{
	public function getVisits()
	{
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$daystolog 		= $cparams->get('daystolog', '30');
		$exclude_users	= $cparams->get('exclude_users');
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT DISTINCT(DATE(date)) AS thedate, count( * ) AS count
		FROM #__vmvendor_profilevisits
		WHERE profile_userid='".$user->id."' 
		AND date>CURDATE() - INTERVAL ".$daystolog." DAY 
		AND visitor_userid NOT IN ('".$exclude_users."') 
		GROUP BY thedate
		ORDER BY thedate ASC";
		$db->setQuery($q);
		$visitz = $db->loadObjectList();
		$visits = array();
		foreach ($visitz as $visit)
		{
			$visits[$visit->thedate] = $visit->count;
		}
		return $visits;
	}
	
	public function getVisitors()
	{
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$profileman		= $cparams->get('profileman');
		$daystolog 		= $cparams->get('daystolog', '30');
		$exclude_users	= $cparams->get('exclude_users');
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$q = "SELECT DISTINCT(epv.visitor_userid) ,epv.date , 
		 u.username , u.name ";
		 
		 if($profileman=='cb')
			$q .= ', c.avatar AS thumb , c.avatarapproved  ';
		elseif($profileman=='js')
			$q .= ',c.thumb';

		$q .= " FROM #__vmvendor_profilevisits epv 
		JOIN #__users u ON u.id = epv.visitor_userid ";
		if($profileman=='cb')
			$q .= ' LEFT JOIN #__comprofiler c ON c.user_id = epv.visitor_userid   ';
		elseif($profileman=='js')
			$q .= ' LEFT JOIN #__community_users c ON c.userid = epv.visitor_userid  ';
		
		$q .= " WHERE epv.profile_userid='".$user->id."' 
		AND epv.date>CURDATE() - INTERVAL ".$daystolog." DAY 
		AND epv.visitor_userid NOT IN ('".$exclude_users."') 
		GROUP BY epv.visitor_userid 
		ORDER BY epv.date DESC" ;
		$db->setQuery($q);
		$visitors = $db->loadObjectList();
		return $visitors; //
	}

	

	public function getUserprofileItemid()
	{
		$cparams 		= JComponentHelper::getParams('com_vmvendor');
		$profileman	= $cparams->get('profileman');
		if($profileman=='cb')
			$link = " `link` LIKE 'index.php?option=com_comprofiler&task=userProfile%' OR  `link` LIKE 'index.php?option=com_comprofiler%' ";
		elseif($profileman=='js')
			$link = " `link` LIKE 'index.php?option=com_community&view=profile%' OR  `link` LIKE 'index.php?option=com_community%' ";
		elseif($profileman=='es')
			$link = " `link` LIKE 'index.php?option=com_easysocial&view=profile%' OR  `link` LIKE 'index.php?option=com_easysocial%' ";
		$lang = JFactory::getLanguage();
		$db 	= JFactory::getDBO();
		$q = "SELECT `id` FROM `#__menu` WHERE (".$link.") AND `type`='component'  AND ( language ='".$lang->getTag()."%' OR language='*') AND published='1'  AND access='1' ";
		$db->setQuery($q);
		$profile_itemid = $db->loadResult();
		return $profile_itemid ;
	
	}
}