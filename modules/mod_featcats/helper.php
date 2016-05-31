<?php
/*------------------------------------------------------------------------
# mod_featcats - Featured Categories
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# Copyright (C) 2010 www.joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

$com_path = JPATH_SITE.'/components/com_content/';
require_once $com_path.'router.php';
require_once $com_path.'helpers/route.php';

JModelLegacy::addIncludePath($com_path.'/models', 'ContentModel');

abstract class modFeatcatsHelper
{
	public static function getList(&$params)
	{
		$start     = (int)JRequest::getVar('fc_start', 0);
		$count     = (int)$params->get('count', 3);
		$subcount  = (int)$params->get('subcount', 0);
		$colcount  = (int)$params->get('colcount', 3);
		$ajaxed    = (int)$params->get('ajaxed', '');
		$pag       = $params->get('pag_show', 0);
		$cat_image = $params->get('cat_image', 0);
		
		$colBootstrapClasses = array(1=>'col-lg-12 col-md-12 col-sm-12 span12', 2=>'col-lg-6 col-md-6 col-sm-12 span6', 3=>'col-lg-4 col-md-6 col-sm-12 span4', 4=>'col-lg-3 col-md-6 col-sm-12 span3', 6=>'col-lg-2 col-md-3 col-sm-12 span2');
		
		$groups     = array();
		$col_class  = 'featcat';
		$categories = JCategories::getInstance('Content');
		
		if ($pag) :
			$limit = (int)$params->get('limit', '');
			$col_class .= ' pag';	
		else :
			$limit = $count+$subcount;
		endif;
		
		if (!$ajaxed) :
			$catids = $params->get('catid');
		else :
		    $catids = array();
			if (JRequest::getVar('catid')) :
				$catids[] = (int)JRequest::getVar('catid');
			else :
				$catids[] = '';
			endif;	
		endif;
		
		$col = 0;
		if ( $catids ) :
			foreach($catids as $catid)
			{	
				$col++;
			
				$groups[$catid] = new stdClass;
				$groups[$catid]->col_class = $col_class.' col'.$col;
				if ( $col == 1  ) :
  					$groups[$catid]->col_class .= ' firstcol featcat_clr';
				elseif ( $col == $colcount  ) :
  					$groups[$catid]->col_class .= ' lastcol';
  					$col = 0;
  				endif;
				
				if(isset($colBootstrapClasses[$colcount])) {
					$groups[$catid]->col_class .= ' '.$colBootstrapClasses[$colcount];	
				}
			
				$category = $categories->get($catid);
				$groups[$catid]->category_title = $category->title;
				$groups[$catid]->category_link  = JRoute::_(ContentHelperRoute::getCategoryRoute($catid));
				
				if ($cat_image) :
					$catParams = $category->params;
					$registry = new JRegistry;
					$registry->loadString($category->params);
					$groups[$catid]->category_image = $registry->get('image');
				endif;
				
				$groups[$catid]->articles = array();		
				
				$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
				
				$articles->setState(
					'list.select',
					'a.id, a.title, a.alias, a.introtext, a.fulltext, a.images, ' .
					'a.catid, a.created, a.created_by, a.created_by_alias, ' .
					'a.modified, a.publish_up, ' .
					'a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, '.
					'a.hits, a.xreference, a.featured,'.' LENGTH(a.fulltext) AS readmore '
				);
		
				$app = JFactory::getApplication();
				$appParams = $app->getParams('com_content');
				$articles->setState('params', $appParams);
		
				$articles->setState('list.start', 0);
				$articles->setState('list.limit', $limit);
				$articles->setState('filter.published', 1);
		
				$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
				$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
				$articles->setState('filter.access', $access);
		
				$articles->setState('filter.category_id.include', 1);
		
				if ($params->get('show_child_category_articles', 0) && (int)$params->get('levels', 0) > 0) {
					$subcategories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
					$subcategories->setState('params', $appParams);
					$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
					$subcategories->setState('filter.get_children', $levels);
					$subcategories->setState('filter.published', 1);
					$subcategories->setState('filter.access', $access);
					$include_subcategories = array();
					$include_subcategories[] = $catid;	
					
					$subcategories->setState('filter.parentId', $catid);
					$recursive = true;
					$items = $subcategories->getItems($recursive);
	
					if ($items)
					{
						foreach($items as $subcategory)
						{
							$condition = (($subcategory->level - $subcategory->getParent()->level) <= $levels);
							if ($condition) {
								$include_subcategories[] = $subcategory->id;
							}
	
						}
					}	
					$articles->setState('filter.category_id', $include_subcategories);				
				} else {
					$articles->setState('filter.category_id', $catid);
				}
		
				$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
				$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));
		
				$articles->setState('filter.featured', $params->get('show_front', 'show'));
				$articles->setState('filter.author_id', $params->get('created_by', ""));
				$articles->setState('filter.author_id.include', $params->get('author_filtering_type', 1));
				$articles->setState('filter.author_alias', $params->get('created_by_alias', ""));
				$articles->setState('filter.author_alias.include', $params->get('author_alias_filtering_type', 1));
				$excluded_articles = $params->get('excluded_articles', '');
		
				if ($excluded_articles) {
					$excluded_articles = explode("\r\n", $excluded_articles);
					$articles->setState('filter.article_id', $excluded_articles);
					$articles->setState('filter.article_id.include', false); // Exclude
				}
		
				$date_filtering = $params->get('date_filtering', 'off');
				if ($date_filtering !== 'off') {
					$articles->setState('filter.date_filtering', $date_filtering);
					$articles->setState('filter.date_field', $params->get('date_field', 'a.created'));
					$articles->setState('filter.start_date_range', $params->get('start_date_range', '1000-01-01 00:00:00'));
					$articles->setState('filter.end_date_range', $params->get('end_date_range', '9999-12-31 23:59:59'));
					$articles->setState('filter.relative_date', $params->get('relative_date', 30));
				}
		
				$articles->setState('filter.language',$app->getLanguageFilter());
		
				$items = $articles->getItems();
		
				$show_date = $params->get('show_date', 0);
				$show_date2 = $params->get('show_date2', 0);
				$show_date_field = $params->get('show_date_field', 'created');
				$show_date_format = $params->get('show_date_format', 'Y-m-d H:i:s');
				$show_hits = $params->get('show_hits', 0);
				$show_author = $params->get('show_author', 0);
				$show_author2 = $params->get('show_author2', 0);
				$show_introtext = $params->get('show_introtext', 1);
				$show_introtext2 = $params->get('show_introtext2', 0);
				$show_image = $params->get('show_image', 0);
				$yt_image = $params->get('yt_image', 0);
				$introtext_limit = $params->get('introtext_limit', 100);
				$introtext_limit2 = $params->get('introtext_limit2', 100);
				$title_limit = $params->get('title_limit', '');
				$title_limit2 = $params->get('title_limit2', '');
		
				$option = JRequest::getCmd('option');
				$view = JRequest::getCmd('view');
		
				if ($option === 'com_content' && $view === 'article') {
					$active_article_id = JRequest::getInt('id');
				}
				else {
					$active_article_id = 0;
				}
		
				$groups[$catid]->pages = array(
					'items' => $count+$subcount,
					'total' => ceil(count($items)/($count+$subcount)),
					'current' => $start/($count+$subcount)
				);
				$groups[$catid]->articles = array();
				$groups[$catid]->subarticles = array();

				for ($j=$start;$j<($start+$count+$subcount);$j++)
				{
					if (isset($items[$j])) {
						$items[$j]->slug = $items[$j]->id.':'.$items[$j]->alias;
						$items[$j]->catslug = $items[$j]->catid ? $items[$j]->catid .':'.$items[$j]->category_alias : $items[$j]->catid;
			
						if ($access || in_array($items[$j]->access, $authorised)) {
							$items[$j]->link = JRoute::_(ContentHelperRoute::getArticleRoute($items[$j]->slug, $items[$j]->catslug));
						}
						 else {
							$app	= JFactory::getApplication();
							$menu	= $app->getMenu();
							$menuitems	= $menu->getItems('link', 'index.php?option=com_users&view=login');
						if(isset($menuitems[0])) {
								$Itemid = $menuitems[0]->id;
							} else if (JRequest::getInt('Itemid') > 0) {
								$Itemid = JRequest::getInt('Itemid');
							}
			
							$items[$j]->link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid);
							}
			
						$items[$j]->active = $items[$j]->id == $active_article_id ? ' active' : '';
						
						$items[$j]->displayHits = $show_hits ? $items[$j]->hits : '';
						$items[$j]->displayDate = '';
						$items[$j]->displayAuthorName = '';
								
						if ( $j < $count+$start ) 
						{
							if ( $title_limit && strlen($items[$j]->title)>$title_limit) {
								$items[$j]->title = mb_substr($items[$j]->title,0,$title_limit) . '...';		
							}
							if ($show_date) {
								$items[$j]->displayDate = JHTML::_('date', $items[$j]->$show_date_field, $show_date_format);
							}
							if ($show_author) {
								$items[$j]->displayAuthorName = $items[$j]->author;
							}
				
							$items[$j]->image = "";
							$content = $items[$j]->introtext.$items[$j]->fulltext;
							if ($show_image) :
								$img = "";
								$alt = $items[$j]->title;
								$def_image = $params->get('def_image','');
								if ($yt_image != 0) : 
									$regex   = '/\/\/www.youtube.com\/embed\/([A-Za-z0-9_-]+)["\']/';
									if ($video_id = modFeatcatsHelper::srch_first_occ($regex,$content)) :
										$img  = 'http://i1.ytimg.com/vi/'.$video_id.'/0.jpg';
										$url = "http://gdata.youtube.com/feeds/api/videos/". $video_id;
										$doc = new DOMDocument;
										$doc->load($url);
										$alt = htmlspecialchars($doc->getElementsByTagName("title")->item(0)->nodeValue);										
									endif;
								endif;
								
								if (!$img && $yt_image != 2) :								
									$images = json_decode($items[$j]->images);
									if (isset($images->image_intro) and !empty($images->image_intro)) :
										$img = $images->image_intro;
										$alt = htmlspecialchars($images->image_intro_alt);
									elseif (isset($images->image_fulltext) and !empty($images->image_fulltext)) :
										$img = $images->image_fulltext;
										$alt = htmlspecialchars($images->image_fulltext_alt);
									else :
										$regex   = "/<img[^>]+src\s*=\s*[\"']\/?([^\"']+)[\"'][^>]*\>/";
										$img = modFeatcatsHelper::srch_first_occ($regex,$content);
									endif;
								endif;
							
								if ($img or $def_image) {
									if ($params->get('thumb_image', 1)) {
										$img = str_replace(JURI::base(false),'',$img);
										$items[$j]->image = modFeatcatsHelper::getThumbnail($img,$params,$items[$j]->id,$alt); 
									} else {
										$img_base = !modFeatcatsHelper::is_absolute($img)?str_replace('modules/mod_featcats/assets/','',JURI::base(false)):'';
										$width  = $params->get('thumb_width', 90);
										$height = $params->get('thumb_height', 90);
										if ( $width ) {
											$attribs['width'] = $width;
										}
										if ( $height ) {
											$attribs['height'] = $height;
										}
										$items[$j]->image  = JHTML::_('image', $img_base.$img, $alt, $attribs);
									}
								}
							endif;
				
							if ($show_introtext) {
								$items[$j]->introtext = JHtml::_('content.prepare', $items[$j]->introtext);
							}
							
							switch ($show_introtext) {
								case 1:
									$items[$j]->introtext = self::_cleanIntrotext($items[$j]->introtext);
									$items[$j]->displayIntrotext = self::truncate($items[$j]->introtext, $introtext_limit);
									break;
								case 2:
									$items[$j]->displayIntrotext = $items[$j]->introtext;	
									break;
								default:
									$items[$j]->displayIntrotext = '';
									break;	
							}
							
							$items[$j]->displayReadmore = $items[$j]->alternative_readmore;
							
							$groups[$catid]->articles[] = $items[$j];	
						} else {
							if ( $title_limit2 && strlen($items[$j]->title)>$title_limit2) {
								$items[$j]->title = mb_substr($items[$j]->title,0,$title_limit2) . '...';		
							}
							if ($show_date2) {
								$items[$j]->displayDate = JHTML::_('date', $items[$j]->$show_date_field, $show_date_format);
							}
							if ($show_author2) {
								$items[$j]->displayAuthorName = $items[$j]->author;
							}
							if ($show_introtext2) {
								$items[$j]->introtext = JHtml::_('content.prepare', $items[$j]->introtext);
							}
							
							switch ($show_introtext2) {
								case 1:
									$items[$j]->introtext = self::_cleanIntrotext($items[$j]->introtext);
									$items[$j]->displayIntrotext = self::truncate($items[$j]->introtext, $introtext_limit);
									break;
								case 2:
									$items[$j]->displayIntrotext = $items[$j]->introtext;	
									break;
								default:
									$items[$j]->displayIntrotext = '';
									break;	
							}
							
							$groups[$catid]->subarticles[] = $items[$j];	
						}
					}
				}	
			}
			
		endif;

		return $groups;
	}
	
	public static function getThumbnail($img,&$params,$item_id,$alt) 
	{
		
		$width      = $params->get('thumb_width',90);
		$height     = $params->get('thumb_height',90);
		$proportion = $params->get('thumb_proportions','bestfit');
		$img_type   = $params->get('thumb_type','');
		$bgcolor    = hexdec($params->get('thumb_bg','#FFFFFF'));
		
		$def_image  = $params->get('def_image', '');
		$item_img   = $img?$img:$def_image;
		
		$img_name   = pathinfo($item_img, PATHINFO_FILENAME);
		$img_ext    = pathinfo($item_img, PATHINFO_EXTENSION);
		$img_path   = JPATH_BASE  . '/' . $item_img;		
		$img_base   = str_replace('modules/mod_featcats/assets/','',JURI::base(false));
		
		if (modFeatcatsHelper::is_absolute($item_img)) {
			$img_path = $item_img;
		}
		
		$size = @getimagesize($img_path);
		
		$errors = array();
		
		if(!$size) 
		{
			$errors[] = 'There was a problem loading image ' . $img_name . '.' . $img_ext . ' in mod_featcats';
		
		} else {
							
			$sub_folder = $img ? '0' . substr($item_id, -1) : 'default';
							
			if ( $img_type ) {
				$img_ext = $img_type;
			}
	
			$origw = $size[0];
			$origh = $size[1];
			if( ($origw<$width && $origh<$height)) {
				$width = $origw;
				$height = $origh;
			}
			
			$prefix = substr($proportion,0,1) . "_".$width."_".$height."_".($proportion=='fill'?$bgcolor."_":"").($img?$item_id."_":"");
	
			$thumb_file = $prefix . str_replace(array( JPATH_ROOT, ':', '/', '\\', '?', '&', '%20', ' '),  '_' ,urlencode($img_name) . '.' . $img_ext);		
			
			//$thumb_path = __DIR__ . '/thumbs/' . $sub_folder . '/' . $thumb_file;
			$thumb_path = dirname(__FILE__).'/thumbs/' . $sub_folder . '/' . $thumb_file;
			
			$attribs = array();
			
			if(file_exists($thumb_path))	{
				$size = @getimagesize($thumb_path);
				if($size) {
					$attribs['width']  = $size[0];
					$attribs['height'] = $size[1];
				}
			} else {
		
				modFeatcatsHelper::calculateSize($origw, $origh, $width, $height, $proportion, $newwidth, $newheight, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	
				switch(strtolower($size['mime'])) {
					case 'image/png':
						$imagecreatefrom = "imagecreatefrompng";
						break;
					case 'image/gif':
						$imagecreatefrom = "imagecreatefromgif";
						break;
					case 'image/jpeg':
						$imagecreatefrom = "imagecreatefromjpeg";
						break;
					default:
						$errors[] = "Unsupported image type $img_name.$img_ext ".$size['mime'];
				}
	
				
				if ( !function_exists ( $imagecreatefrom ) ) {
					$errors[] = "Failed to process $img_name.$img_ext in mod_featcats. Function $imagecreatefrom doesn't exist.";
				}
				
				$src_img = $imagecreatefrom($img_path);
				
				if (!$src_img) {
					$errors[] = "There was a problem to process image $img_name.$img_ext ".$size['mime'] . 'in mod_featcats';
				}
				
				$dst_img = ImageCreateTrueColor($width, $height);
				
				// $bgcolor = imagecolorallocatealpha($image, 200, 200, 200, 127);
				
				imagefill( $dst_img, 0,0, $bgcolor);
				if ( $proportion == 'transparent' ) {
					imagecolortransparent($dst_img, $bgcolor);
				}
				
				imagecopyresampled($dst_img,$src_img, $dst_x, $dst_y, $src_x, $src_y, $newwidth, $newheight, $src_w, $src_h);		
				
				switch(strtolower($img_ext)) {
					case 'png':
						$imagefunction = "imagepng";
						break;
					case 'gif':
						$imagefunction = "imagegif";
						break;
					default:
						$imagefunction = "imagejpeg";
				}
				
				if($imagefunction=='imagejpeg') {
					$result = @$imagefunction($dst_img, $thumb_path, 80 );
				} else {
					$result = @$imagefunction($dst_img, $thumb_path);
				}
	


				imagedestroy($src_img);
				if(!$result) {				
					if(!isset($disablepermissionwarning)) {
					$errors[] = 'Could not create image:<br />' . $thumb_path . ' in mod_featcats.<br /> Check if the folder exists and if you have write permissions:<br /> ' . dirname(__FILE__) . '/thumbs/' . $sub_folder;
					}
					$disablepermissionwarning = true;
				} else {
					imagedestroy($dst_img);
				}
			}
		}
		
		if (count($errors)) {
			JError::raiseWarning(404, implode("\n", $errors));
			return false;
		}
				
		$image = $img_base."modules/mod_featcats/thumbs/$sub_folder/" . urlencode(basename($thumb_path));
		
		return  JHTML::_('image', $image, $alt, $attribs);
    }
	
	public static function calculateSize($origw, $origh, &$width, &$height, &$proportion, &$newwidth, &$newheight, &$dst_x, &$dst_y, &$src_x, &$src_y, &$src_w, &$src_h) {
		
		if(!$width ) {
			$width = $origw;
		}

		if(!$height ) {
			$height = $origh;
		}

		if ( $height > $origh ) {
			$newheight = $origh;
			$height = $origh;
		} else {
			$newheight = $height;
		}
		
		if ( $width > $origw ) {
			$newwidth = $origw;
			$width = $origw;
		} else {
			$newwidth = $width;
		}
		
		$dst_x = $dst_y = $src_x = $src_y = 0;

		switch($proportion) {
			case 'fill':
			case 'transparent':
				$xscale=$origw/$width;
				$yscale=$origh/$height;

				if ($yscale<$xscale){
					$newheight =  round($origh/$origw*$width);
					$dst_y = round(($height - $newheight)/2);
				} else {
					$newwidth = round($origw/$origh*$height);
					$dst_x = round(($width - $newwidth)/2);

				}

				$src_w = $origw;
				$src_h = $origh;
				break;

			case 'crop':

				$ratio_orig = $origw/$origh;
				$ratio = $width/$height;
				if ( $ratio > $ratio_orig) {
					$newheight = round($width/$ratio_orig);
					$newwidth = $width;
				} else {
					$newwidth = round($height*$ratio_orig);
					$newheight = $height;
				}
					
				$src_x = ($newwidth-$width)/2;
				$src_y = ($newheight-$height)/2;
				$src_w = $origw;
				$src_h = $origh;				
				break;
				
 			case 'only_cut':
				// }
				$src_x = round(($origw-$newwidth)/2);
				$src_y = round(($origh-$newheight)/2);
				$src_w = $newwidth;
				$src_h = $newheight;
				
				break; 
				
			case 'bestfit':
				$xscale=$origw/$width;
				$yscale=$origh/$height;

				if ($yscale<$xscale){
					$newheight = $height = round($width / ($origw / $origh));
				}
				else {
					$newwidth = $width = round($height * ($origw / $origh));
				}
				$src_w = $origw;
				$src_h = $origh;	
				
				break;
			}

	}

	public static function _cleanIntrotext($introtext)
	{
		$introtext = str_replace('<p>', ' ', $introtext);
		$introtext = str_replace('</p>', ' ', $introtext);
		$introtext = strip_tags($introtext, '<a><em><strong>');

		$introtext = trim($introtext);

		return $introtext;
	}

	public static function truncate($html, $maxLength = 0)
	{
		$baseLength = strlen($html);
		$diffLength = 0;

		$ptString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = false);

		for ($maxLength; $maxLength < $baseLength;)
		{
			$htmlString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = true);

			$htmlStringToPtString = JHtml::_('string.truncate', $htmlString, $maxLength, $noSplit = true, $allowHtml = false);

			if ($ptString == $htmlStringToPtString)
			{
				return $htmlString;
			}
			$diffLength = strlen($ptString) - strlen($htmlStringToPtString);

			$maxLength += $diffLength;
			if ($baseLength <= $maxLength || $diffLength <= 0)
			{
				return $htmlString;
			}
		}
		return $html;
	}
	
	public static function srch_first_occ($regex,$text)
	{
		preg_match ($regex, $text, $matches);
		$images = (count($matches)) ? $matches : array();
		if ( count($images) ) :
		  return $images[1];
		endif;	
		return false;
	}
	
	public static function is_absolute($url)
	{
		if ( strpos($url, "http://") === 0 || strpos($url, "https://") === 0 ){
			return true;
		}
		return false;
	}

}
