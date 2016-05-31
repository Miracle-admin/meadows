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
    <li><a id="top-selling" data-tab="#top-selling" class="pageTabs_link"><span>Top Selling</span></a></li>
	<li><a id="new-releases" data-tab="#new-releases" class="pageTabs_link"><span>New Releases</span></a></li>
</ul>
</div>

<?php
echo '<div class="col-md-4"> <div class="categories_prod"> ';
$i=0;
foreach($appmarketcat as $appcat)
{
	
	echo '<div class="ajax_prod_cat '.$classel.'">';
	echo '<a>';
	echo '<span class="new_name">'.$appcat->name.'</span>';
	echo '</a>';
	echo '</div>';
	$i++;
}
echo '</div>';
echo '</div></div></div>';


echo '<div class="featured_games_wrapper container"> <div class="row">';

echo "<div class='product_container col-md-12' id='product_containder'>";
echo "<div class='top-sales' id='top_selling'>";
if(empty($appmarket))
{
	echo "<div class='prod_price_cont col-md-3 col-sm-3'>";
		echo "There is no product to match your query";
	echo "</div>";
}
foreach($appmarket as $results)
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

