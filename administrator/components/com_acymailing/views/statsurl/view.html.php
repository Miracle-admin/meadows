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


class StatsurlViewStatsurl extends acymailingView
{
	var $searchFields = array('b.subject','a.mailid','a.urlid','c.name','c.url','a.click');
	var $selectFields = array('b.subject','a.mailid','a.urlid','c.name','c.url','COUNT(a.click) as uniqueclick','SUM(a.click) as totalclick');
	var $detailSearchFields = array('b.subject','a.mailid','a.urlid','a.subid','c.name','c.url','d.name','d.email');
	var $detailSelectFields = array('d.*','a.*','b.subject','c.name as urlname','c.url');

	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function listing(){
		$app = JFactory::getApplication();
		$config = acymailing_config();

		JHTML::_('behavior.modal','a.modal');

		$pageInfo = new stdClass();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->elements = new stdClass();

		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName().$this->getLayout();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'desc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower(trim($pageInfo->search));
		$selectedMail = $app->getUserStateFromRequest( $paramBase."filter_mail",'filter_mail',0,'int');
		$selectedUrl = $app->getUserStateFromRequest( $paramBase."filter_url",'filter_url',0,'int');

		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );

		$database	= JFactory::getDBO();

		$filters = array();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.acymailing_getEscaped($pageInfo->search,true).'%\'';
			$filters[] = implode(" LIKE $searchVal OR ",$this->searchFields)." LIKE $searchVal";
		}

		if(!empty($selectedMail)) $filters[] = 'a.mailid = '.$selectedMail;
		if(!empty($selectedUrl)) $filters[] = 'a.urlid = '.$selectedUrl;

		$query = 'SELECT SQL_CALC_FOUND_ROWS '.implode(' , ',$this->selectFields);
		$query .= ' FROM '.acymailing_table('urlclick').' as a';
		$query .= ' JOIN '.acymailing_table('mail').' as b on a.mailid = b.mailid';
		$query .= ' JOIN '.acymailing_table('url').' as c on a.urlid = c.urlid';
		if(!empty($filters)) $query .= ' WHERE ('.implode(') AND (',$filters).')';
		$query .= ' GROUP BY a.mailid,a.urlid';
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}

		$database->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $database->loadObjectList();

		$database->setQuery('SELECT FOUND_ROWS()');
		$pageInfo->elements->total = $database->loadResult();

		$pageInfo->elements->page = count($rows);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );

		$filtersType = new stdClass();
		$mailType = acymailing_get('type.urlmail');
		$urlType = acymailing_get('type.url');
		$filtersType->mail = $mailType->display('filter_mail',$selectedMail);
		$filtersType->url = $urlType->display('filter_url',$selectedUrl);

		acymailing_setTitle(JText::_('CLICK_STATISTICS'),'stats','statsurl');

		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Link', 'cancel', JText::_('GLOBAL_STATISTICS'), acymailing_completeLink('stats') );

		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','statsurl-listing');
		if(acymailing_isAllowed($config->get('acl_cpanel_manage','all'))) $bar->appendButton( 'Link', 'acymailing', JText::_('ACY_CPANEL'), acymailing_completeLink('dashboard') );

		$this->assignRef('filters',$filtersType);
		$this->assignRef('rows',$rows);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);

	}

	function form(){
		$urlid = acymailing_getCID('urlid');
		$urlClass = acymailing_get('class.url');
		$this->assign('url',$urlClass->get($urlid));
	}

	function detaillisting(){
		require(dirname(__FILE__).DS.'view.detaillisting.php');
	}

}
