<?php

/**
 * 
 */
Class modLatestProductsHelper {

    /**
     * 
     * @param type $params
     * @return type
     */
    
    
    public static function getLatestProducts($params = null) {
        
        if (!class_exists('shopFunctionsF'))
            require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
         if (!class_exists('shopFunctionsF'))
            require(VMPATH_SITE . DS . 'helpers' . DS . 'vmmodel.php');
        $categoryModel = VmModel::getModel('category');
        $productModel = VmModel::getModel('product');
        $ratingModel = VmModel::getModel('ratings');
         $vendorId = vRequest::getInt('vendorid', 1);

        $vendorModel = VmModel::getModel('vendor');

        $vendorIdUser = VmConfig::isSuperVendor();
        $vendorModel->setId($vendorId);
        //$this->vendor = $vendorModel->getVendor();
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $plans = array();
        $user = JFactory::getUser();
        $featured_products = array();
        $categoryId = vRequest::getInt('catid', 0);

        $categoryChildren = $categoryModel->getChildCategoryList($vendorId, $categoryId);

        $categoryModel->addImages($categoryChildren, 1);

       // $this->assignRef('categories', $categoryChildren);

        if (!class_exists('CurrencyDisplay'))
            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
        //$this->currency = CurrencyDisplay::getInstance();

        $products_per_row = VmConfig::get('homepage_products_per_row', 3);
        
        $featured_products_rows = VmConfig::get('latest_products_rows', 1);
       $fproduct_rows =  $productModel->getProductListing('latest', 20);
      
        return $fproduct_rows;
    }

}
