<?php /*
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

if(JRequest::getInt("Itemid") == 199){
if (!class_exists('VmConfig'))
    require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');

VmConfig::loadConfig();
VmConfig::loadJLang('mod_virtuemart_product', true);

// Setting
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
$rows = mod_virtuemart_product::getRecentlySold();
$model = VmModel::getModel("product");
?>
<div class="container">
    <div class="row">
        <h4>Recently Sold</h4>
        <?php if ($rows) { ?>
            <div class="col-md-12 recently_sold bxslider">

                <?php foreach ($rows as $row) { ?>
                    <div class="product vm-col vm-col-3 vertical-separator">
                        <?php
                        $product = $model->getProduct($row['virtuemart_product_id']);
                        $product_image = mod_virtuemart_product::getProductImage($row['virtuemart_product_id']);
                        ?>
                        <div class="spacer">
                            <div class="vm-product-media-container">
                                <a href="<?php echo JRoute::_($product->link); ?>" title="<?php echo $product->product_name; ?>">
                                    <img class="browseProductImage" alt="" src="<?php echo JRoute::_($product_image['file_url_thumb']); ?>">         
                                </a>
                            </div>
                            <div class="vm-product-descr-container-1">
                                <h2><a href="<?php echo JRoute::_($product->link); ?>"><?php echo $product->product_name; ?></a></h2>
                                <p class="product_s_desc">
                                    <?php
                                    echo substr($product->product_s_desc, 0, 30) . '...';
                                    ?>                                                       </p>
                            </div>

                            <div class="vm3pr-5"> 
                                <div id="productPrice<?php echo $row['virtuemart_product_id']; ?>" class="product-price">
                                    <div class="PricebasePriceVariant vm-display vm-price-value">
                                        <span class="vm-price-desc">Base price for variant: </span>
                                        <span class="PricebasePriceVariant">$<?php echo $product->prices['basePrice'] ?></span>
                                    </div>

                                    <div class="PricesalesPrice vm-display vm-price-value">
                                        <span class="vm-price-desc">Sales price: </span>
                                        <span class="PricesalesPrice">$<?php echo $product->prices['salesPrice'] ?></span>
                                    </div>
                                    <div class="PricepriceWithoutTax vm-display vm-price-value">
                                        <span class="vm-price-desc">Sales price without tax: </span>
                                        <span class="PricepriceWithoutTax">$<?php echo $product->prices['priceWithoutTax'] ?></span>
                                    </div>


                                    <div class="PriceunitPrice vm-nodisplay">
                                        <span class="vm-price-desc">Price / <?php echo $product->product_unit ?>: </span>
                                        <span class="PriceunitPrice"></span>
                                    </div>
                                </div>

                                <div class="clear"></div>
                            </div>
                            <?php
                            if ($show_addtocart) {
                              echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $product));
                            }
                            ?>
                            <div class="vm-details-button">
                                <a href="<?php echo JRoute::_($product->link); ?>"><?php echo $product->product_name; ?></a>
                            </div>

                        </div>
                    </div>
            <?php } ?>
            </div>
            <?php
        } else {
            echo '<h2> No Project(s) found...!</h2>';
        }
        ?>
    </div>
</div>
<?php } */ ?>
<!--<script>
    jQuery(document).ready(function () {
        jQuery('.bxslider').bxSlider({
            infiniteLoop: true,
            hideControlOnEnd: true
        });
    });
</script>-->
 
 