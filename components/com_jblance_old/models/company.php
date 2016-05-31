<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	models/user.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 

 class JblanceModelCompany extends JModelLegacy {
	 
	  var $_total = null;
     function __construct()
     {
     parent::__construct();
     // Set the pagination request variables
     $this->setState('limit', JRequest::getVar('limit', 5, '', 'int'));
     $this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
    
     }
	 function getTotal()
     {
		$db 	= JFactory::getDbo();
     $query = "SELECT count(ju.id),loc.title,us.* FROM #__jblance_user ju INNER JOIN #__users us ON ju.user_id=us.id INNER JOIN #__jblance_location loc ON loc.id=ju.id_location WHERE us.block=0";
		$db->setQuery($query);
		$this->_total = $db->loadResult();
     return $this->_total;
     }
	 public function getPagination()
     {
     jimport('joomla.html.pagination');
     $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
     return $this->_pagination;
     }
	 
	 function getfilters()
	 {
	 $filters=JRequest::getVar('filterunchecked');
		
		if(!empty($filters))
		{
			$app=JFactory::getApplication();
			$filters=explode(' ',$filters);
			$filtern='';
			foreach($filters as $filter)
			{
				$filtern.=$filter.',';
			}
 			$nonselfil=rtrim($filtern,",");

			//$app->redirect(JRoute::_('index.php?option=com_jblance&view=company'));
			return $nonselfil;
	    }
	 }
	 
	function getCompany(){
		
		
		$db 	= JFactory::getDbo();
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit') ;
		$query = "SELECT ju.*,loc.title,us.* FROM #__jblance_user ju INNER JOIN #__users us ON ju.user_id=us.id INNER JOIN #__jblance_location loc ON loc.id=ju.id_location WHERE us.block=0 LIMIT ".$limitstart.",".$limit;
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
		}
		
		
		function getParents(){
		$db 	= JFactory::getDbo();
		$sql	= "SELECT * FROM #__jblance_category WHERE parent=0";
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		return $rows;	 
		}
		
		function getChild($parentid){
		$db 	= JFactory::getDbo();
		$sql	= "SELECT * FROM #__jblance_category WHERE parent=".$parentid;
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		return $rows;	 
		}
 }