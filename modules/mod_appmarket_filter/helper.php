<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 * Created    6/7/13 1:51 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */
class modHelloAjaxWorldHelper
{
	public static function getAjax()
	{
		$input = JFactory::getApplication()->input;
		$data = (array)$input->get('data', array(), 'array');
		//return 'Hello Ajax World, ' . $data['filter1'] .'Second: '. $data['filtercategory']. '!';
		$filtercat=$data['filtercategory'];
		$filter1=$data['filter1'];
		$db = JFactory::getDbo();
		$query_new = $db->getQuery(true);
		$query_new->select('p.pid,p.vendid,p.price,vt.name as vendor_name,vn.namekey AS venname,ju.thumb,mn.id,vn.filid AS venfldid,p.alias,t.name AS itemname,p.curid,cn.symbol,t.description,pm.filid,pm.premium,fl.name,fl.type,pc.catid,pcn.name AS catalias');
		$query_new->from('#__product_node AS p');
		$query_new->join('inner', '#__product_trans AS t ON t.pid = p.pid');
		$query_new->join('inner', '#__product_images AS pm ON pm.pid = p.pid');
		$query_new->join('inner', '#__joobi_files AS fl ON pm.filid = fl.filid');
		$query_new->join('inner', '#__product_node AS pn ON pn.pid = p.pid');
		$query_new->join('inner', '#__productcat_product AS pc ON pc.pid = p.pid');
		$query_new->join('inner', '#__currency_node AS cn ON cn.curid = p.curid');
		$query_new->join('inner', '#__productcat_trans AS pcn ON pcn.catid = pc.catid');
		$query_new->join('inner', '#__vendor_node AS vn ON vn.vendid = pn.vendid');
		$query_new->join('inner', '#__vendor_trans AS vt ON vt.vendid = pn.vendid');
		
		$query_new->join('inner', '#__members_node AS mn ON mn.uid = vn.uid');
		$query_new->join('inner', '#__jblance_user AS ju ON ju.user_id = mn.id');
		$query_new->where('pm.premium = 1 AND pc.catid > 12 AND pcn.name="'.$filtercat.'"');
		
		if($filter1=='New Releases')
		{
			$query_new->order('pn.created DESC');
		}
		$query_new->setLimit(8);
		$db->setQuery($query_new);
		$result = $db->loadObjectList();
		$html='';
		$html.="<div class='top-sales' id='top_selling'>";
		
		foreach($result as $results)
		{
				$html.="<div class='prod_price_cont col-md-3 col-sm-3'>";
					$html.='<a href="'.JRoute::_('index.php?option=com_jmarket&controller=catalog&task=show&eid='.$results->pid.'&Itemid=238').'">';
					$html.="<div class='pro_img_thumb'><img src='".JURI::root()."joobi/user/media/images/products/".$results->name.'.'.$results->type."'></div>";
					$html.="<div class='pro_img_name'>".substr($results->itemname,0,8). "<span> ".$results->catalias."</span></div>";
					$html.="<div class='prod_price'>";
						$html.="Price: ".$results->symbol.number_format($results->price,2);
					$html.="</div>";
					$html.='</a>';
				$html.="<div class='zoom'>";
					//$html.="<img src='".JURI::root()."joobi/user/media/images/products/".$results->name.'.'.$results->type."'>";
					$html.=$results->itemname.'  '.$results->catalias;
					$query2 = $db->getQuery(true);
					$query2="select name,type,twidth,theight from #__joobi_files WHERE filid=".$results->venfldid;
					$db->setQuery($query2);
					$result2=$db->loadRow();
					$html.="<img height='".$result2[2]."' width='".$result2[3]."' src='".JURI::root()."images/jblance/".$results->thumb."'>";
				$html.="<div class='prod_price'>";
					$html.="Price:<br>".$results->symbol.number_format($results->price,2);
				$html.="</div>";
				$html.="<div class='vendor_name'>";
					$html.=$results->venname;
				$html.="</div>";
				$html.="</div>";
				$html.="</div>";
			}
		$html.="</div>";
		return $html;
	}
}
?>

