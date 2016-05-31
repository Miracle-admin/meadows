<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('p.pid,p.vendid,p.price,p.alias,t.name AS itemname,p.curid,cn.symbol,t.description,pm.filid,pm.premium,fl.name,fl.type,pc.catid,pcn.name AS catalias');
$query->from('#__product_node AS p');
$query->join('inner', '#__product_trans AS t ON t.pid = p.pid');
$query->join('inner', '#__product_images AS pm ON pm.pid = p.pid');
$query->join('inner', '#__joobi_files AS fl ON pm.filid = fl.filid');
$query->join('inner', '#__product_node AS pn ON pn.pid = p.pid');
$query->join('inner', '#__productcat_product AS pc ON pc.pid = p.pid');
$query->join('inner', '#__currency_node AS cn ON cn.curid = p.curid');
$query->join('inner', '#__productcat_trans AS pcn ON pcn.catid = pc.catid');
$query->where('pm.premium = 1 AND pc.catid > 12 AND pn.blocked=0');
$query->order('pn.created DESC');
$query->setLimit(4);
$db->setQuery($query);
$result = $db->loadObjectList();
?>
<div class="row">
          <div class="col-md-12 buy-wrap">
            <h2>Buy Source <br>
              Code And Templates</h2>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem Sed ut perspiciatis unde</p>
          </div>
        </div>
<div class="row game-wrapper">
          <?php
		  foreach($result as $resultres)
			{
				  ?>
			<div class="col-md-6 game-wrap">
                <div class="img-wrap"> 
                <img src="<?php echo JURI::root(); ?>joobi/user/media/images/products/<?php echo $resultres->name.'.'.$resultres->type ?>" alt="" />
                </div>
                <h2><?php echo $resultres->itemname; ?> </h2>
                <p><?php echo $resultres->catalias; ?></p>
          </div>
				  <?php
			} ?>
          <a class="start_wrap"href="<?php echo JRoute::_('index.php?option=com_jmarket&view=list_of_all_items&controller=catalog-items&Itemid=200') ?>">Check Out the App Market</a> 
          </div>