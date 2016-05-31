<?php

/**
 * 
 */
Class modRecentlySoldProductsHelper {

    /**
     * 
     * @param type $params
     * @return type
     */
    public static function getRecentlySoldProducts($params = null) {

        $db = JFactory::getDbo();
        $query = "SELECT  voi.`virtuemart_order_id` , voi.`virtuemart_product_id` , 
vo.modified_on, vo.virtuemart_order_id from #__virtuemart_order_items  voi left join #__virtuemart_orders 
vo on vo.`virtuemart_order_id`= voi.`virtuemart_order_id` group by voi.`virtuemart_product_id` desc limit 20 ";

        $db->setQuery($query);
        return  $db->loadAssocList();
        // echo '<pre>'; print_r($results); die;
//        if (!class_exists('shopFunctionsF'))
//            require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
//         if (!class_exists('shopFunctionsF'))
//            require(VMPATH_SITE . DS . 'helpers' . DS . 'vmmodel.php');
//        $categoryModel = VmModel::getModel('category');
//        $productModel = VmModel::getModel('product');
//        $ratingModel = VmModel::getModel('ratings');
//         $vendorId = vRequest::getInt('vendorid', 1);
//
//        $vendorModel = VmModel::getModel('vendor');
//
//        $vendorIdUser = VmConfig::isSuperVendor();
//        $vendorModel->setId($vendorId);
//        //$this->vendor = $vendorModel->getVendor();
//        $app = JFactory::getApplication();
//        $db = JFactory::getDbo();
//        $plans = array();
//        $user = JFactory::getUser();
//        $featured_products = array();
//        $categoryId = vRequest::getInt('catid', 0);
//
//        $categoryChildren = $categoryModel->getChildCategoryList($vendorId, $categoryId);
//
//        $categoryModel->addImages($categoryChildren, 1);
//
//       // $this->assignRef('categories', $categoryChildren);
//
//        if (!class_exists('CurrencyDisplay'))
//            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
//        //$this->currency = CurrencyDisplay::getInstance();
//
//        $products_per_row = VmConfig::get('homepage_products_per_row', 3);
//        
//        $featured_products_rows = VmConfig::get('latest_products_rows', 1);
//       $fproduct_rows =  $productModel->getProductListing('latest', 20);
//      
//        return $fproduct_rows;
    }

    public static function getRecentlySold($params = null) {
        $db = JFactory::getDbo();
        $query = "SELECT  voi.`virtuemart_order_id` , voi.`virtuemart_product_id` , 
vo.modified_on, vo.virtuemart_order_id from #__virtuemart_order_items  voi left join #__virtuemart_orders 
vo on vo.`virtuemart_order_id`= voi.`virtuemart_order_id` group by vo.modified_on desc limit 20 ";

        $db->setQuery($query);
        return $db->loadAssocList();
    }

//    public static function getProductImage($product_id = null) {
//        $db = JFactory::getDBO();
//        $qs = "SELECT file_url_thumb FROM #__virtuemart_medias WHERE virtuemart_media_id = (SELECT virtuemart_media_id FROM #__virtuemart_product_medias WHERE virtuemart_product_id =" . $product_id . ")"; //print_r($qs);
//        $db->setQuery($qs);
//        return $db->loadAssoc();
//        
//    }
}
