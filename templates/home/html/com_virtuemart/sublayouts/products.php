<?php
/**
 * sublayout products
 *
 * @package	VirtueMart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */
defined('_JEXEC') or die('Restricted access');
$products_per_row = $viewData['products_per_row'];
$currency = $viewData['currency'];
$showRating = $viewData['showRating'];
$verticalseparator = " vertical-separator";
echo shopFunctionsF::renderVmSubLayout('askrecomjs');

$ItemidStr = '';
$Itemid = shopFunctionsF::getLastVisitedItemId();
if (!empty($Itemid)) {
    $ItemidStr = '&Itemid=' . $Itemid;
}

foreach ($viewData['products'] as $type => $products) {

    $rowsHeight = shopFunctionsF::calculateProductRowsHeights($products, $currency, $products_per_row);
    if (count($products) < 1) {
        echo '<h4>No product(s) found....! </h4>';
    }
    if (!empty($type) and count($products) > 0) {
        $productTitle = vmText::_('COM_VIRTUEMART_' . strtoupper($type) . '_PRODUCT');
        ?>

        <div class="<?php echo $type ?>-view">
            <h4><?php echo $productTitle ?></h4>
            <?php
            // Start the Output
        }

        // Calculating Products Per Row
        $cellwidth = ' width' . floor(100 / $products_per_row);

        $BrowseTotalProducts = count($products);

        $col = 1;
        $nb = 1;
        $row = 1;

        foreach ($products as $product) {

            // Show the horizontal seperator
            if ($col == 1 && $nb > $products_per_row) {
                ?>
                <?php
            }

            // this is an indicator wether a row needs to be opened or not
            if ($col == 1) {
                ?>
                <div class="row">
                    <?php
                }

                // Show the vertical seperator
                if ($nb == $products_per_row or $nb % $products_per_row == 0) {
                    $show_vertical_separator = ' ';
                } else {
                    $show_vertical_separator = $verticalseparator;
                }

                // Show Products 
                ?>
                <div class="product col-md-4">
                    <div class="spacer">
                        <div class="vm-product-media-container"> 
                        <a title="<?php echo $product->product_name ?>" href="<?php echo $product->link . $ItemidStr; ?>">
                                <?php
                                if (!empty($product->images[0]) && is_a($product->images[0],'VmImage')) {
                                    echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false);
                                }else{
                                    echo '<img src="images/stories/virtuemart/product/blank_img_166x1179.jpg" class="browseProductImage" alt="'.$product->product_name.'">';
                                }
                                ?>
                            </a> </div>
                        <div class="vm-product-rating-container"> <?php
                            echo shopFunctionsF::renderVmSubLayout('rating', array('showRating' => $showRating, 'product' => $product));
                            if (VmConfig::get('display_stock', 1)) {
                                ?> <span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
                                <?php
                            }
                            echo shopFunctionsF::renderVmSubLayout('stockhandle', array('product' => $product));
                            ?>
                        </div>
                        <div class="pro-nm-row vm-product-descr-container-<?php echo $rowsHeight[$row]['product_s_desc'] ?>">
                            <h2><?php echo JHtml::link($product->link . $ItemidStr, $product->product_name); ?></h2>
                            <?php
                            foreach ($product->categoryItem as $key => $caat) {
                                if ($key == 0)
                                    echo $caat['category_name'];
                                else {
                                    echo ' >> ' . $caat['category_name'];
                                }
                            }

                            ?>
                            <?php //echo '<pre>'; print_r($product->categoryItem[0]['category_name']); die; //$this->product[0]->category_name ?>
                            <?php /* ?><?php if(!empty($rowsHeight[$row]['product_s_desc'])){
                              ?>
                              <p>
                              <?php // Product Short Description
                              if (!empty($product->product_s_desc)) {
                              echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 30, ' ...') ?>
                              <?php } ?>
                              </p>
                              <?php  } ?><?php */ ?>
                        </div>
                        <div class="rate"><?php
                            echo shopFunctionsF::renderVmSubLayout('rating', array('showRating' => $this->showRating, 'product' => $this->product));
                            ?>
                        </div>
                        <?php //echo $rowsHeight[$row]['price'] ?>
                        <div class="vm3pr-<?php echo $rowsHeight[$row]['price'] ?>">
                            <?php echo shopFunctionsF::renderVmSubLayout('customprices', array('product' => $product, 'currency' => $currency)); ?>
                        </div>
                        <?php //echo $rowsHeight[$row]['customs'] ?>
                        <?php /* ?><div class="vm3pr-<?php echo $rowsHeight[$row]['customfields'] ?>"> <?php
                          echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'rowHeights'=>$rowsHeight[$row])); ?>
                          </div><?php */ ?>
                        <div class="vm-details-button display-none">
                            <?php
                            // Product Details Button
                            $link = empty($product->link) ? $product->canonical : $product->link;
                            echo JHtml::link($link . $ItemidStr, vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS'), array('title' => $product->product_name, 'class' => 'product-details'));
                            //echo JHtml::link ( JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id , FALSE), vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ), array ('title' => $product->product_name, 'class' => 'product-details' ) );
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                $nb ++;

                // Do we need to close the current row now?
                if ($col == $products_per_row || $nb > $BrowseTotalProducts) {
                    ?>
                    <div class="clear"></div>
                </div>
                <?php
                $col = 1;
                $row++;
            } else {
                $col ++;
            }
        }

        if (!empty($type)and count($products) > 0) {
            // Do we need a final closing row tag?
            //if ($col != 1) {
            ?>
            <div class="clear"></div>
        </div>
        <?php
        // }
    }
    echo $this->vmPagination->getListFooter();
}
?>

<style>
    .ratingbox {
        display: table;
    }
    .product-list-lf-wrap .slider.slider-horizontal {
  
    width: 95%;
}
</style>



