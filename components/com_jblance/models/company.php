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

    function __construct() {
        parent::__construct();
        // Set the pagination request variables
        $this->setState('limit', JRequest::getVar('limit', 5, '', 'int'));
        $this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
    }

    function getTotal() {
        $db = JFactory::getDbo();
        $query = "SELECT count(ju.id),loc.title,us.* FROM #__jblance_user ju INNER JOIN #__users us ON ju.user_id=us.id INNER JOIN #__jblance_location loc ON loc.id=ju.id_location WHERE us.block=0";
        $db->setQuery($query);
        $this->_total = $db->loadResult();
        return $this->_total;
    }

    public function getPagination() {
        jimport('joomla.html.pagination');
        $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        return $this->_pagination;
    }

    function getfilters() {
        $filters = JRequest::getVar('filterunchecked');

        if (!empty($filters)) {
            $app = JFactory::getApplication();
            $filters = explode(' ', $filters);
            $filtern = '';
            foreach ($filters as $filter) {
                $filtern.=$filter . ',';
            }
            $nonselfil = rtrim($filtern, ",");

            //$app->redirect(JRoute::_('index.php?option=com_jblance&view=company'));
            return $nonselfil;
        }
    }

    function getCompany() {


        $db = JFactory::getDbo();
        $limitstart = JRequest::getVar('limitstart');
        $limit = JRequest::getVar('limit');
        $query = "SELECT ju.*,loc.title,us.* FROM #__jblance_user ju INNER JOIN #__users us ON ju.user_id=us.id INNER JOIN #__jblance_location loc ON loc.id=ju.id_location WHERE us.block=0 LIMIT " . $limitstart . "," . $limit;
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    function getCompanies() {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $where = array();

        $keyword = $app->input->get('keyword', '', 'string');
        $id_categ = $app->input->get('id_categ', array(), 'array');

        $text = $db->quote('%' . $db->escape($keyword, true) . '%', false);
        $wheres2 = array();
//        $wheres2[] = 'u.name LIKE ' . $text;
//        $wheres2[] = 'u.username LIKE ' . $text;
//        $wheres2[] = 'ju.biz_name LIKE ' . $text;
//        $wheres2[] = 'cv.value LIKE ' . $text;
//        $queryStrings[] = '(' . implode(') OR (', $wheres2) . ')';

        if (count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))) {
            if (is_array($id_categ)) {
                $miniquery = array();
                foreach ($id_categ as $cat) {
                    $miniquery[] = "FIND_IN_SET($cat, ju.id_category)";
                }
                $querytemp = '(' . implode(' OR ', $miniquery) . ')';
            }
            $queryStrings[] = $querytemp;
        }

        // Load the parameters.
        $params = $app->getParams();
        $ugids = $params->get('ug_id', '');
        if (!empty($ugids))
            $queryStrings[] = 'ju.ug_id IN (' . $ugids . ')';

        $letter = $app->input->get('letter', '', 'string');
       // $queryStrings[] = " u.name LIKE '$letter%'";

        $queryStrings[] = "u.block=0";

        $queryStrings[] = "u.emailvalid=''";
        $where = (count($queryStrings) ? ' WHERE (' . implode(') AND (', $queryStrings) . ') ' : '');

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $query = "SELECT DISTINCT ju.*,u.username,u.emailvalid,u.name,ug.name AS grpname FROM #__jblance_user ju " .
                "LEFT JOIN #__users u ON ju.user_id=u.id " .
                "LEFT JOIN #__jblance_usergroup ug ON ju.ug_id=ug.id " .
                "LEFT JOIN #__jblance_custom_field_value cv ON cv.userid=u.id " .
                $where .
                "ORDER BY u.name"; 
//echo $query; die;
        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        $return[2] = $params;
        $return[3] = $total;
        return $return;
    }

    function getParents() {
        $db = JFactory::getDbo();
        $sql = "SELECT * FROM #__jblance_category WHERE parent=0";
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function getChild($parentid) {
        $db = JFactory::getDbo();
        $sql = "SELECT * FROM #__jblance_category WHERE parent=" . $parentid;
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        return $rows;
    }
    function AjaxGetCompany($arr){
        //print_r($arr);
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $where = array();

        $keyword = $app->input->get('keyword', '', 'string');
        //$id_categ = $app->input->get('id_categ', array(), 'array');
        $id_categ = $arr['id_categ'];

        $text = $db->quote('%' . $db->escape($keyword, true) . '%', false);
        $wheres2 = array();
//        $wheres2[] = 'u.name LIKE ' . $text;
//        $wheres2[] = 'u.username LIKE ' . $text;
//        $wheres2[] = 'ju.biz_name LIKE ' . $text;
//        $wheres2[] = 'cv.value LIKE ' . $text;
//        $queryStrings[] = '(' . implode(') OR (', $wheres2) . ')';

        if (count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))) {
            if (is_array($id_categ)) {
                $miniquery = array();
                foreach ($id_categ as $cat) {
                    $miniquery[] = "FIND_IN_SET($cat, ju.id_category)";
                }
                $querytemp = '(' . implode(' OR ', $miniquery) . ')';
            }
            $queryStrings[] = $querytemp;
        }

        // Load the parameters.
        $params = $app->getParams();
        $ugids = $params->get('ug_id', '');
        if (!empty($ugids))
            $queryStrings[] = 'ju.ug_id IN (' . $ugids . ')';

        $letter = $app->input->get('letter', '', 'string');
       // $queryStrings[] = " u.name LIKE '$letter%'";

        $queryStrings[] = "u.block=0";

        $queryStrings[] = "u.emailvalid=''";
        $where = (count($queryStrings) ? ' WHERE (' . implode(') AND (', $queryStrings) . ') ' : '');

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
        //$limitstart = $app->input->get('limitstart', 0, 'int');
        $limitstart = $arr['limit'];

        $query = "SELECT DISTINCT ju.*,u.username,u.emailvalid,u.name,ug.name AS grpname FROM #__jblance_user ju " .
                "LEFT JOIN #__users u ON ju.user_id=u.id " .
                "LEFT JOIN #__jblance_usergroup ug ON ju.ug_id=ug.id " .
                "LEFT JOIN #__jblance_custom_field_value cv ON cv.userid=u.id " .
                $where .
                "ORDER BY u.name"; 
//echo $query; die;
        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        $return[2] = $params;
        $return[3] = $total;
        return $return;
    }

}
