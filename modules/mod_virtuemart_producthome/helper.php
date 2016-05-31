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

class modVirtuemartProducthomeHelper {
    /*
     * @deprecated
     */

    static function addtocart($product) {

        echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $product));
    }

    static function productList($params) {
        $max_items = $params->get('max_items', 2); //maximum number of items to display

        $category_id = $params->get('virtuemart_category_id', null); // Display products from this category only

        $filter_category = (bool) $params->get('filter_category', 0); // Filter the category

        $display_style = $params->get('display_style', "div"); // Display Style

        $products_per_row = $params->get('products_per_row', 1); // Display X products per Row

        $show_price = (bool) $params->get('show_price', 1); // Display the Product Price?

        $show_addtocart = (bool) $params->get('show_addtocart', 1); // Display the "Add-to-Cart" Link?

        $headerText = $params->get('headerText', ''); // Display a Header Text

        $footerText = $params->get('footerText', ''); // Display a footerText

        $Product_group = $params->get('product_group', 'featured'); // Display a footerText

        $mainframe = Jfactory::getApplication();

        $virtuemart_currency_id = $mainframe->getUserStateFromRequest("virtuemart_currency_id", 'virtuemart_currency_id', vRequest::getInt('virtuemart_currency_id', 0));

        if ($show_addtocart) {
            vmJsApi::jPrice();
            vmJsApi::cssSite();
            echo vmJsApi::writeJS();
        }

        if (!class_exists('CurrencyDisplay'))
            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
        $currency = CurrencyDisplay::getInstance();

        $vendorId = vRequest::getInt('vendorid', 1);

        if ($filter_category)
            $filter_category = TRUE;

        $productModel = VmModel::getModel('Product');

        $products = $productModel->getProductListing($Product_group, $max_items, $show_price, true, false, $filter_category, $category_id);

        $productModel->addImages($products);
        ?>
        <?php
        foreach ($products as $product) {
            echo"<pre>";
            print_r($product);
            ?>

            <div class="pwidth">
                <div class="spacer">
                    <?php
                    if (!empty($product->images[0])) {
                        $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" border="0"', FALSE);
                    } else {
                        $image = '';
                    }
                    echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
                    echo '<div class="clear"></div>';
                    $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                                    $product->virtuemart_category_id);
                    ?>
                    <a href="<?php echo $url ?>"><?php echo $product->product_name ?></a>
                    <?php
                    echo '<div class="clear"></div>';

                    if ($params->get('show_price')) {

                        // 		echo $currency->priceDisplay($product->prices['salesPrice']);
                        if (!empty($product->prices['salesPrice'])) {
                            echo $currency->createPriceDiv('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                        }
                        // 		if ($product->prices['salesPriceWithDiscount']>0) echo $currency->priceDisplay($product->prices['salesPriceWithDiscount']);
                        if (!empty($product->prices['salesPriceWithDiscount'])) {
                            echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                        }
                    }

                    //product category
                    echo vmText::_($product->category_name);


                    //$rating=modVirtuemartProducthomeHelper::getRating($product->virtuemart_product_id);


                    if ($params->get('show_addtocart')) {
                        echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $product));
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }

    //function to get rating of product
    static function getRating($pid) {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);

        if (!class_exists('VmConfig'))
            require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');

        VmConfig::loadConfig();

        $rating_model = VmModel::getModel('ratings');

        $rating = $rating_model->getRatingByProduct($pid);

        return $rating->rating;
    }

    //function to receive ajax call and return response
    public static function getProductsAjax() {
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);

        if (!class_exists('VmConfig'))
            require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');



        VmConfig::loadConfig();

        VmConfig::loadJLang('mod_virtuemart_producthome', true);


        $max_items = 12; //maximum number of items to display

        $category_id = JRequest::getVar('virtuemart_category_id', null); // Display products from this category only

        $filter_category = JRequest::getVar('filter_category', 1); // Filter the category

        $show_price = 1; // Display the Product Price?

        $show_addtocart = 0; // Display the "Add-to-Cart" Link?

        $Product_group = JRequest::getVar('product_group', 'topten'); // Display a footerText


        $vendor_model = VmModel::getModel('vendor');

        $mainframe = Jfactory::getApplication();

        $virtuemart_currency_id = $mainframe->getUserStateFromRequest("virtuemart_currency_id", 'virtuemart_currency_id', vRequest::getInt('virtuemart_currency_id', 0));



        if (!class_exists('CurrencyDisplay'))
            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
        $currency = CurrencyDisplay::getInstance();

        $vendorId = vRequest::getInt('vendorid', 1);

        if ($filter_category)
            $filter_category = TRUE;

        $productModel = VmModel::getModel('Product');

        $products = $productModel->getProductListing($Product_group, $max_items, $show_price, true, false, $filter_category, $category_id);

        $productModel->addImages($products);
        ?>
        <?php
        if (empty($products)) {

            echo "<div class='no_products_found'>No Products Found</div>";
        } else {

            foreach ($products as $product) {
                $vendorUserid = $vendor_model->getUserIdByVendorId($product->virtuemart_vendor_id);
                ?>
                <div class="col-md-3">
                    <div class="feat_img_wrap">
                        <div class="apps_thumb_wrap">  


                            <?php
                            $full_image = $product->images[0]->file_url;
                            if (!empty($product->images[0])) {
                                $image = $product->images[0]->displayMediaThumb('class="feat_img" border="0"', FALSE);
                            } else {
                                $image = '';
                            }
                            echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
                            //echo '<div class="clear"></div>';
                            $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                                            $product->virtuemart_category_id);
                            ?>

                        </div>
                        <a class="feat_pro_nme" href="<?php echo $url ?>"><?php echo $product->product_name ?></a>


                        <!--//product name-->

                        <div class="price_apps"> Price 
                            <?php
                            // 		echo $currency->priceDisplay($product->prices['salesPrice']);
                            if (!empty($product->prices['salesPrice'])) {
                                echo $currency->createPriceDiv('salesPrice', '', $product->prices, '', '', 1.0, TRUE);
                            }
                            // 		if ($product->prices['salesPriceWithDiscount']>0) echo $currency->priceDisplay($product->prices['salesPriceWithDiscount']);
                            if (!empty($product->prices['salesPriceWithDiscount'])) {
                                echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                            }


                            //product category
                            echo "<p class='cate'>Category: ".vmText::_($product->category_name).'</p>'; 


                            $rating = modVirtuemartProducthomeHelper::getRating($product->virtuemart_product_id);

                            //rating count
                            ?>
                        </div>

                        <div class="rating_vm"> <?php echo empty($rating) ? "Rating : 0" : "Rating : " . $rating . ''; ?> </div>

                        <!--//product rating-->

                        <?php
                        $vendorthumb = modVirtuemartProducthomeHelper::getVendorThumb($product->virtuemart_vendor_id);

                        //vendor image
                        //echo"vendor: <a target='_blank' href='".JURI::base()."index.php?option=com_vmvendor&view=vendorprofile&userid=".$vendorUserid."'><img style='width:50px;' src='".$vendorthumb."' /></a>";
                        ?>
                    </div>
                </div>
                <?php
            }
        }
    }

    //function to return the vendor thumb image.
    static function getVendorThumb($vid) {

        $db = JFactory::getDBO();

        $q = "SELECT vm.`file_url` 
			FROM `#__virtuemart_medias` vm 
			LEFT JOIN `#__virtuemart_vendor_medias` vvm ON vvm.`virtuemart_media_id` = vm.`virtuemart_media_id` 
			WHERE vvm.`virtuemart_vendor_id` = '" . $vid . "' 
			AND vm.`file_type`='vendor' ORDER BY `file_is_product_image` DESC ";

        $db->setQuery($q);
        $juri = JURI::base();
        $vendor_thumb_url = $db->loadResult();

        if ($vendor_thumb_url)
            $vendor_thumb_url = $juri . $vendor_thumb_url;
        else
            $vendor_thumb_url = $juri . 'components/com_vmvendor/assets/img/noimage.gif';

        return $vendor_thumb_url;
    }

    static function VmProductJs() {
        $document = JFactory::getDocument();

        $document->addStyleSheet($vmratystyle);
        $document->addScript($vmratyjs);
        $document->addScriptDeclaration('
    jQuery(function(){
    jaxVm(\'topten\',14,0);
    var childF=jQuery("#child-cat");
    var mainF=jQuery("#main-cat");
    jQuery(\'.cfc\').on(\'click\',function(e){
    e.preventDefault();
    jQuery("#vm-product-list").html(\'<div class="text-center"><img class="loader" style="width:200px;" src="' . JUri::base() . 'images/loading.gif"/></div>\');
    childF.empty();
    var datafc=jQuery(this).attr(\'filter-child-data\');
    childF.val(datafc);
    getProductList();
    })
    jQuery(\'.cf\').on(\'click\',function(e){
    e.preventDefault();
    jQuery("#vm-product-list").html(\'<div class="text-center"><img class="loader" style="width:200px;" src="' . JUri::base() . 'images/loading.gif"/></div>\');
    mainF.empty();
    var datafm=jQuery(this).attr(\'filter-data\');
    mainF.val(datafm);
    getProductList();
    })
    })
    function getProductList()
    {
    var mainFilter=jQuery("#main-cat").val();
    var childFilter=jQuery("#child-cat").val();
    jaxVm(mainFilter,childFilter,1);
    }
    function jaxVm(fmain,fchild,fcat)
    {
    data={"product_group":fmain,"virtuemart_category_id":fchild,"filter_category":fcat}
    jQuery.ajax({
    type: "POST",
    url: "index.php?option=com_ajax&module=virtuemart_producthome&method=getProducts&format=debug",
    data: data,
   success: function(data)
   {
   var container= jQuery("#vm-product-list");
   container.empty();
   container.append(data);
   }
   });
   }
   ');
    }

    static function getRatingStars($pid) {
        $document = JFactory::getDocument();
        $rating = self::getRating($pid);
        $document->addScriptDeclaration('
	jQuery("#rating-168").raty({
     readOnly:  true,
     start:     2
     });
	
	');
    }

}
