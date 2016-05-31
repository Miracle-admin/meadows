<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
echo "<div class='container'><div class='col-md-4'><h1>APP Market</h1></div>";
?>

<div class="col-md-4 ">
<ul class="releases_wrapper">
    <li><a id="top-selling" data-tab="#top-selling" class="pageTabs_link active"><span>Top Selling</span></a></li>
	<li><a id="new-releases" data-tab="#new-releases" class="pageTabs_link"><span>New Releases</span></a></li>
</ul>
</div>

<?php
$db = JFactory::getDbo();
$sql = $db->getQuery(true);
$sql->select('catid,name');
$sql->from('#__productcat_trans');
$sql->where('catid > 12');
$db->setQuery($sql);	
$resultsql = $db->loadObjectList();

	echo '<div class="col-md-4"> <div class="categories_prod"> ';
	$i=0;
foreach($resultsql as $resultres)
{
	if($i==0)
	{
		$classel='active2';
	}
	else
	{
			$classel='';
		}
	echo '<div class="ajax_prod_cat '.$classel.'">';
	echo '<a>';
	echo '<span class="new_name">'.$resultres->name.'</span>';
	echo '</a>';
	echo '</div>';
	$i++;
}
echo '</div></div></div>';


echo '<div class="featured_games_wrapper container"> <div class="row">';
$query = $db->getQuery(true);
$query->select('p.pid,p.vendid,p.price,vt.name as vendor_name,vn.namekey AS venname,ju.thumb,mn.id,vn.filid AS venfldid,p.alias,t.name AS itemname,p.curid,cn.symbol,t.description,pm.filid,pm.premium,fl.name,fl.type,pc.catid,pcn.name AS catalias');
$query->from('#__product_node AS p');
$query->join('inner', '#__product_trans AS t ON t.pid = p.pid');
$query->join('inner', '#__product_images AS pm ON pm.pid = p.pid');
$query->join('inner', '#__joobi_files AS fl ON pm.filid = fl.filid');
$query->join('inner', '#__product_node AS pn ON pn.pid = p.pid');
$query->join('inner', '#__productcat_product AS pc ON pc.pid = p.pid');
$query->join('inner', '#__currency_node AS cn ON cn.curid = p.curid');
$query->join('inner', '#__productcat_trans AS pcn ON pcn.catid = pc.catid');
$query->join('inner', '#__vendor_node AS vn ON vn.vendid = pn.vendid');
$query->join('inner', '#__vendor_trans AS vt ON vt.vendid = pn.vendid');

$query->join('inner', '#__members_node AS mn ON mn.uid = vn.uid');
$query->join('inner', '#__jblance_user AS ju ON ju.user_id = mn.id');

$query->where('pm.premium = 1 AND pc.catid > 12 AND pn.blocked=0 AND pcn.name="Android"');
$query->setLimit(8);
$db->setQuery($query);
$result = $db->loadObjectList();
echo "<div class='product_container col-md-12' id='product_containder'>";
echo "<div class='top-sales' id='top_selling'>";

foreach($result as $results)
{
	
	
		echo "<div class='prod_price_cont col-md-3 col-sm-3'>";
			echo '<a href="'.JRoute::_('index.php?option=com_jmarket&controller=catalog&task=show&eid='.$results->pid.'&Itemid=238').'">';
			echo "<div class='pro_img_thumb'><img src='".JURI::root()."joobi/user/media/images/products/".$results->name.'.'.$results->type."'></div>";
			echo "<div class='pro_img_name'>".substr($results->itemname,0,8). "<span> ".$results->catalias."</span></div>";
			echo "<div class='prod_price'>";
				echo "Price: ".$results->symbol.number_format($results->price,2);
			echo "</div>";
			echo '</a>';
			
			
		echo "<div class='zoom'>";
			//echo "<img src='".JURI::root()."joobi/user/media/images/products/".$results->name.'.'.$results->type."'>";
			echo $results->itemname.'  '.$results->catalias;
			$query2 = $db->getQuery(true);
			$query2="select name,type,twidth,theight from #__joobi_files WHERE filid=".$results->venfldid;
			$db->setQuery($query2);
			$result2=$db->loadRow();
			
			echo "<img src='".JURI::root()."images/jblance/".$results->thumb."'>";
		echo "<div class='prod_price'>";
			echo "Price:<br>".$results->symbol.number_format($results->price,2);
		echo "</div>";
		echo "<div class='vendor_name'>";
			echo $results->venname;
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}
echo "</div>";


echo '</div>';
echo '</div></div>';

?>

