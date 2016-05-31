<?php

defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/*
 * Module Helper
 * just for legacy, will be removed
 * @package VirtueMart
 * @copyright (C) 2011 - 2014 The VirtueMart Team
 * @Email: max@virtuemart.net
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 * www.virtuemart.net
 */

class mod_virtuemart_product {
    /*
     * @deprecated
     */

    static function addtocart($product) {

        echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $product));
    }

    public static function getRecentlySold($params = null) {
        $db = JFactory::getDbo();
        $query = "SELECT  voi.`virtuemart_order_id` , voi.`virtuemart_product_id` , 
vo.modified_on, vo.virtuemart_order_id from #__virtuemart_order_items  voi left join #__virtuemart_orders 
vo on vo.`virtuemart_order_id`= voi.`virtuemart_order_id` order by vo.modified_on desc limit 20";

        $db->setQuery($query);
        return $db->loadAssocList();
        
        
        
        
        
        
    }

    public static function getProductImage($product_id = null) {
        $db = JFactory::getDBO();
        $qs = "SELECT file_url_thumb FROM #__virtuemart_medias WHERE virtuemart_media_id = (SELECT virtuemart_media_id FROM #__virtuemart_product_medias WHERE virtuemart_product_id =" . $product_id . ")"; //print_r($qs);
        $db->setQuery($qs);
        return $db->loadAssoc();
        
    }

}
