<?php
/**
 * Vertical scroll recent article
 *
 * @package Vertical scroll recent article
 * @subpackage Vertical scroll recent article
 * @version   3.4
 * @author    Gopi Ramasamy
 * @copyright Copyright (C) 2010 - 2015 www.gopiplus.com, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
 
// Lide Demo : http://www.gopiplus.com/extensions/2011/06/vertical-scroll-recent-article-joomla-module/
// Technical Support : http://www.gopiplus.com/extensions/2011/06/vertical-scroll-recent-article-joomla-module/

// no direct access
defined('_JEXEC') or die;

require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');


class modVerticalScrollRecentArticleHelper
{
	public static function loadScripts(&$params)
	{
		$doc = JFactory::getDocument();
		$doc->addScript(JURI::Root(true).'/modules/mod_vertical_scroll_recent_article/mod_vertical_scroll_recent_article.js');
	} 
	
	public static function getArticleList(&$params)
	{
		$db			= JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');
		
		$option		= JRequest::getCmd('option');
		$view		= JRequest::getCmd('view');
		
		$temp		= JRequest::getString('id');
		$temp		= explode(':', $temp);
		$id			= $temp[0];
		
		$vspost_count 		= (int) $params->get('vspost_count', 5);
		$vspost_ordering 	= $params->get('vspost_ordering');
		$vspost_user_id 	= $params->get('vspost_user_id', 0);
		$vspost_show_front 	= $params->get('vspost_show_front', 1);
		$vspost_recent 		= $params->get('vspost_recent');
		$vspost_cat 		= $params->get('vspost_cat', 1);
		$vspost_only		= $params->get('vspost_only', 0);
		$vspost_sccart		= $params->get('vspost_show_child_category_articles', 0);
		$vspost_levels 		= $params->get('vspost_levels', 0); 
		$vspost_catexc		= $params->get('vspost_catexc', '');
		$current			= 1;

		$access 	= !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		
		$app = JFactory::getApplication();
		
		$concats = ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,' .
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug';
			
		$nullDate = $db->getNullDate();

		$date = JFactory::getDate();
		$now = $date->toSql();
		
		$where		= 'a.state = 1'
			. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;
		
		if ( $vspost_recent ) :
			$where .= ' AND DATEDIFF('.$db->Quote($now).', a.created) < ' . $vspost_recent;
		endif;
		
		if ($app->getLanguageFilter()) 
		{
			$where .= ' AND a.language in ('.$db->quote(JFactory::getLanguage()->getTag()).','.$db->quote('*').')';
		}

		if ( $vspost_user_id > 1 && $userId ) 
		{
			switch ($vspost_user_id)
			{
				case '2':
					$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
					break;
				case '3':
					$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
					break;
			}
		}
		
		switch ($vspost_ordering)
		{
			case 'random':
				$ordering		= ' ORDER BY rand()';
				break;
			case 'h_asc':
				$ordering		= ' ORDER BY a.hits ASC';
				break;
			case 'h_dsc':
				$ordering		= ' ORDER BY a.hits DESC';
				break;
			case 'm_dsc':
				$ordering		= ' ORDER BY a.modified DESC, a.created DESC';
				break;
			case 'order':
				$ordering		= ' ORDER BY a.ordering ASC';
				break;
			case 'c_dsc':
			default:
				$ordering		= ' ORDER BY a.created DESC';
				break;
		}
		
		$joins = ' INNER JOIN #__categories AS cc ON cc.id = a.catid';
		
		switch ( $vspost_show_front )
		{
			case 1:
				$joins .= ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id';
				$where .= ' AND f.content_id IS NULL';
				break;
			case 2:
				$joins .= ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id';
				$where .= ' AND f.content_id = a.id';
				break;
		}
		
        $catid = $the_id = $catCondition = '';
		
		if ( $vspost_only == 0 && $option == 'com_content' && $view == 'category' && $vspost_cat ==1 ) 
		{
	    	$catid = $id;
		}
				   
        if ( $option == 'com_content' && $view == 'article' && $id ) 
		{
                $the_id = $id;	
				$article = JTable::getInstance('content');
				$article->load( $id );
   
                if ($current == 0) 
				{
                    $where .= ' AND a.id!='.$the_id;
                }
				if ( $vspost_cat == 1 )
				{
					$catid = $article->catid;
				}
				
                if ($vspost_user_id == 1)
				{
		            $where .= ' AND created_by = ' . $article->created_by;
				}
        }
		if ($catid) 
		{
			$catCondition .= ' AND (cc.id='. $catid;
			if ($vspost_sccart && $vspost_levels > 0) 
			{
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $app->getParams());
				$levels = $params->get('vspost_levels', 1) ? $params->get('vspost_levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();
				$categories->setState('filter.parentId', $catid);
				$recursive = true;
				$items = $categories->getItems($recursive);
	
				if ($items)
				{
					foreach($items as $category)
					{
						$condition = (($category->level - $categories->getParent()->level) <= $levels);
						if ($condition) {
							$catCondition .= ' OR cc.id='. $category->id;
						}
	
					}
				}
			}
			$catCondition .= ')';
		}
		
		if ( !empty($vspost_catexc[0]) ) 
		{
                //$catCondition .= ' AND (cc.id!=' . implode( ' AND cc.id!=', $vspost_catexc ) . ')';
				$catCondition .= ' AND (cc.id =' . implode( ' AND cc.id =', $vspost_catexc ) . ')';
        }
		
		$query = 'SELECT a.*, cc.title AS catname,' .
			$concats  .
			' FROM #__content AS a' .
			$joins  .
			' WHERE '. $where .
			($access ? ' AND a.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')' : '') .
			$catCondition .
			' AND cc.published = 1' .
			$ordering;
			
		$db->setQuery($query, 0, $vspost_count);
		$rows = $db->loadObjectList();
		
		$items	= array();
		$i = 0;
		foreach ( $rows as &$row ) 
		{
		    $link = '';
		    $row->title = htmlspecialchars( $row->title );
            if ( $the_id != $row->id or $current != 2 ) 
			{
				$link 				= JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug));
				$items[$i]			= new stdClass;
				$items[$i]->links 	=  $link;
				$items[$i]->title	= $row->title;
				$i++;
			}
		}
     	return $items;
    }	
}
?>