<?php
/*
 * @component AlphaUserPoints, Copyright (C) 2008-2015 Bernard Gilly, http://www.alphaplug.com
 * Extension menu created by Mike Gusev (migus)
 * @copyright Copyright (C) 2011 Mike Gusev (migus) - Updated by Bernard Gilly for full compatibility with Joomla 3.1.x on June 2013
 * @license : GNU/GPL
 * @Website : http://migusbox.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class alphauserpointsModelrules extends JmodelLegacy {

	function __construct(){
		parent::__construct();
		
	}
	
	function _getRulesList() {
	
		$app = JFactory::getApplication();
		$db			    = JFactory::getDBO();
		$total 			= 0;

		$menus 		= $app->getMenu();
		$menu       = $menus->getActive();
		$menuid     = $menu->id;
		$params     = $menus->getParams($menuid);
		
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest('com_alphauserpoints.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = JFactory::getApplication()->input->get('limitstart', 0, 'int');
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor( $limitstart / $limit ) * $limit) : 0);

		// hide defined rules from list
		//$hiderule = array();
		$hiderules = "";
		$hide_rules = $params->get('hide_rule_byid');
		if ( $hide_rules ) {
			/*
			$hiderule = explode( ",", $hide_rules);
			for ($i=0, $n=count($hiderule); $i < $n; $i++) {
				$hiderules = " AND id!='" . trim($hiderule[$i]) . "'";
			}
			*/
			$hiderules = " AND id NOT IN (" . trim($hide_rules) . ")";
		}

		//$access = 2;  //$params->get('show_access');
		$pub_state = "";
		$show_order = "";
		
		if ($params->get('show_published', 1)) {
			$pub_state = " published='1'";
		} else {
			$pub_state = " published>='0'";
		}

		if ( $params->get('order_by')==2) {
			$show_order = " ORDER BY points DESC, id ASC";
		} elseif ( $params->get('order_by')==1) {
			$show_order = " ORDER BY category ASC, points DESC";
		} else {
			$show_order = " ORDER BY id ASC";
		}
			$query = "SELECT *, '' AS numrules FROM #__alpha_userpoints_rules "
			//. "WHERE access<='".$access."'"
			. "WHERE " . $pub_state
			. $hiderules
			. $show_order;
		$total = @$this->_getListCount($query);
		$results = $this->_getList($query, $limitstart, $limit);

		for ($i=0, $n=count( $results ); $i < $n; $i++) {
			$row   = $results[$i];
			$query = "SELECT COUNT(*) FROM #__alpha_userpoints_rules "
			. "WHERE rule_name='".$row->id."'";
			$db->setQuery($query);
			$row->numrules = $db->loadResult();
		}
		
		return array($results, $total, $limit, $limitstart);
		
	}
}
?>