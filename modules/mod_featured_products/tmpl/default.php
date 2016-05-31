<?php
   defined('_JEXEC') or die('Restricted access');
   
   defined('_JEXEC') or die('');
   
   defined('DS') or define('DS', DIRECTORY_SEPARATOR);
   
   
   if (!class_exists('VmConfig'))
       require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
   
   VmConfig::loadConfig();
   VmConfig::loadJLang('mod_virtuemart_product', true);
   
   // Setting
   $max_items = $params->get('max_items', 2); //maximum number of items to display
   //$category_id = $params->get('virtuemart_category_id', null); // Display products from this category only
   //$filter_category = (bool) $params->get('filter_category', 0); // Filter the category
   //$display_style = $params->get('display_style', "div"); // Display Style
   //$products_per_row = $params->get('products_per_row', 4); // Display X products per Row
   //$show_price = (bool) $params->get('show_price', 1); // Display the Product Price?
   $show_addtocart = (bool) $params->get('show_addtocart', 1); // Display the "Add-to-Cart" Link?
   
   
   $fproducts = modFeaturedProductsHelper::getFeaturedProducts();
   
   //echo '<pre>'; print_r($fproducts); die;
   if ($fproducts) {
       
       ?>
<div class=" featured-products-carousel ">
   <?php foreach ($fproducts as $key => $product) {
      ?>
   <div class="product  vertical-separator">
      <!--                   <div class="vm-product-descr-container-1">
         <h2><a href="<?php //echo JRoute::_($product->link);    ?>">
         <?php //echo $product->product_name;   ?></a></h2>
         <p class="product_s_desc">
         <?php
            //  echo substr($product->product_s_desc, 0, 30) . '...';
            ?>                                                       </p>
         </div>     -->
      <div class="spacer">
         <div class="vm-product-media-container ">
            <?php
               $db = JFactory::getDBO();
               $qs = "SELECT file_url_thumb FROM #__virtuemart_medias WHERE virtuemart_media_id in (SELECT virtuemart_media_id FROM #__virtuemart_product_medias WHERE virtuemart_product_id =" . $product->virtuemart_product_id . ") limit 1"; //print_r($qs);
               $db->setQuery($qs);
               $item2 = $db->loadAssocList();
               ?>
            <a href="<?php echo JRoute::_($product->link); ?>" title="<?php echo $product->product_name; ?>">
            <?php
               foreach ($item2 as $file_thumb) {
                   if ($file_thumb && $file_thumb['file_url_thumb'] != '' && file_exists($file_thumb['file_url_thumb'])) {
                       ?>
            <img src="<?php echo JURI::root() . $file_thumb['file_url_thumb'] ?>" />
            <?php } else { ?>
            <img src="<?php echo JURI::root() . '/images/stories/virtuemart/product/blank_img_166x1179.jpg' ?>" />
            <?php
               }
               }
               ?>
            </a>
            
            
            <div class="btn">
            <a href="<?php echo JRoute::_($product->link); ?>">See Details</a>
         </div>
            
         </div>
         <div class="pro-nm-row">
            <h2><a href="<?php echo JRoute::_($product->link); ?>">
               <?php echo $product->product_name; ?></a>
            </h2>
            
                  
                 <p class="cate">Android</p>
                  
                 
                  
                  
                  
                  <strong>Price : $<?php echo $product->prices['basePrice'] ?></strong>
                                                                       
            </p>
         </div>
         <?php /*?>
         <!--                    <div class="vm3pr-5"> 
            <div id="productPrice<?php echo $product->virtuemart_product_id; ?>" class="product-price">
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
            </div>-->
         <?php
            if ($show_addtocart) {
                echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $product));
            }
            ?>
         <?php */?>
         
      </div>
   </div>
   <?php } ?>
</div>
<?php
   } else {
       echo '<h2> No Project(s) found...!</h2>';
   }
   ?>
<script>
   jQuery(document).ready(function () {
       jQuery('.featured-products-carousel').bxSlider({
		slideWidth: 275,
		minSlides: 1,
		controls: false,
		maxSlides: 4,
		slideMargin: 25,
		moveSlides : 3
       });
   });
</script>