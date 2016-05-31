<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8847 2015-05-06 12:22:37Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
//echo '<pre>'; print_r($this->products) ; die;

VmConfig::set('show_store_desc', 0);
VmConfig::set('display_stock', 0);
VmConfig::set('showRating', 0);
VmConfig::set('display_stock', 0);
VmConfig::get('show_store_desc', 0);



# Vendor Store Description
echo $this->add_product_link;
?>
<?php
# load categories from front_categories if exist
// if ($this->categories and VmConfig::get('show_categories', 1))
//  echo '<pre>';    print_r($this->categories); die;
//echo $this->renderVmSubLayout('categories',array('categories'=>$this->categories));
?>

<div class="row">
    <div class="col-md-3 product-list-lf-wrap">
        <form name="filter_form" id="submit_product_form" method="get" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=vmproducts'); ?>">
            <input type="hidden" name="option"  value="com_virtuemart" >
            <input type="hidden" name="view"  value="vmproducts" >
            <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid') ?>" >
            <input id="search_item_name" type="hidden" name="keyword"  value="" >
            <div class="lef-srch-app-wrap">
                <?php
                $subsubcatseven = array();
                $subsubcatodd = array();
                $printarr = array();
                $commasaperatedarr = array();
                $vendorId = vRequest::getInt('vendorid', 1);
                $categoryModel1 = VmModel::getModel('category');
                $categoryModel1->_noLimit = 1;
                ?>   
                <div class="srch-row">
                    <button id="clearall" class="btn btn-danger">Clear All</button>
                    <?php
                    foreach ($this->categories as $category) {
                        ?>
                        <div class="selct-cate">
                            <ul class="list-item-format">
                                <?php
                                echo '<li><h4>' . $category->category_name . '</h4></li>';
                                $subs = $categoryModel1->getChildCategoryListObject($vendorId, $category->virtuemart_category_id, 'virtuemart_category_id');
                                //if (JRequest::getVar('virtuemart_category_id') == $sub->virtuemart_category_id)
                                foreach ($subs as $sub) {
                                    if (JRequest::getVar('virtuemart_category_id')) {
                                        if ((JRequest::getVar('virtuemart_category_id') == $sub->virtuemart_category_id)) {
                                            ?>
                                            <li class="<?php echo (JRequest::getVar('virtuemart_category_id') == $sub->virtuemart_category_id) ? 'activecat' : '' ?>"><a  class="fgdkngd"  href="#" onclick="getcat('<?php echo $sub->virtuemart_category_id ?>')" ><?php echo $sub->category_name ?></a><i class="floatright icon-cancel">x</i></li>

                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="<?php echo (JRequest::getVar('virtuemart_category_id') == $sub->virtuemart_category_id) ? 'activecat' : '' ?>"><a  class="fgdkngd"  href="#" onclick="getcat('<?php echo $sub->virtuemart_category_id ?>')" ><?php echo $sub->category_name ?></a></li>
                                        <?php
                                    }
                                }
                                ?>

                                <input type="hidden" name="virtuemart_category_id" id="category_id" value="<?php echo (JRequest::getVar('virtuemart_category_id')) ? JRequest::getVar('virtuemart_category_id') : '' ?>">
                            </ul>
                        </div>

                    <?php } ?>
                </div>

                <div class="srch-row">
                    <?php
                    $catesss = $categoryModel1->getCategories(true, false, false, "", $vendorId);
                    $fmwrks = array();
                    $fmwrks2 = array();
                    $ios = 14;
                    $android = 30;
                    if (JRequest::getVar("virtuemart_category_id") == 18) {
                        $ios = 14;
                        $android = '';
                        $fmwrks = $categoryModel1->getChildCategoryListObject($vendorId, $ios, 'virtuemart_category_id');
                        ?>

                        <div class="selct-cate">
                            <ul class="list-item-format">
                                <?php
                                // echo '<pre>'; print_r($fmwrks); die;
                                echo '<li><h4>Frameworks </h4></li>';

                                foreach ($fmwrks as $category) {
                                    ?>
                                    <li class="<?php echo (JRequest::getVar('fm_cat') == $category->virtuemart_category_id) ? 'activecat' : ''; ?>">
                                        <a class="fgdkngd"  href="#" onclick="getfrmwrk('<?php echo $category->virtuemart_category_id ?>')" >
                                            <?php echo $category->category_name ?>
                                        </a></li>
                                <?php } ?> 
                                <input type="hidden" id="fm_cat" name="fm_cat"  value="<?php echo (JRequest::getVar('fm_cat')) ? JRequest::getVar('fm_cat') : '' ?>">
                            </ul>    
                        </div>
                        <?php
                    } elseif (JRequest::getVar("virtuemart_category_id") == 18) {
                        $ios = '';
                        $android = 30;
                        $fmwrks = $categoryModel1->getChildCategoryListObject($vendorId, $android, 'virtuemart_category_id');
                        ?>
                        <div class="selct-cate">
                            <ul class="list-item-format">
                                <?php
                                // echo '<pre>'; print_r($fmwrks); die;
                                echo '<li><h4>Frameworks </h4></li>';

                                foreach ($fmwrks as $category) {
                                    ?>
                                    <li class="<?php echo (JRequest::getVar('fm_cat') == $category->virtuemart_category_id) ? 'activecat' : ''; ?>">
                                        <a class="fgdkngd"  href="#" onclick="getfrmwrk('<?php echo $category->virtuemart_category_id ?>')" >
                                            <?php echo $category->category_name ?>
                                        </a></li>
                                <?php } ?> 
                                <input type="hidden" id="fm_cat" name="fm_cat"  value="<?php echo (JRequest::getVar('fm_cat')) ? JRequest::getVar('fm_cat') : '' ?>">
                            </ul>    
                        </div>
                        <?php
                    } else {
                        $fmwrks = $categoryModel1->getChildCategoryListObject($vendorId, $ios, 'virtuemart_category_id');
                        $fmwrks21 = array();
                        foreach ($fmwrks as $fmwrks2s) {
                            $fmwrks2[] = $fmwrks2s->category_name;
                            $fmwrks21[$fmwrks2s->category_name] = $fmwrks2s->virtuemart_category_id;
                        }

                        $fmwrks1 = $categoryModel1->getChildCategoryListObject($vendorId, $android, 'virtuemart_category_id');
                        foreach ($fmwrks1 as $frrrmwrks1) {
                            if (!in_array($frrrmwrks1->category_name, $fmwrks2)) {
                                $fmwrks[] = $frrrmwrks1;
                                $fmwrks21[$frrrmwrks1->category_name] = $frrrmwrks1->virtuemart_category_id;
                            } else
                                $fmwrks21[$frrrmwrks1->category_name] = $fmwrks21[$frrrmwrks1->category_name] . ',' . $frrrmwrks1->virtuemart_category_id;
                        }
//                        echo '<pre>';
//                        print_r($fmwrks21);
//                        die;
                        ?>
                        <div class="selct-cate">
                            <ul class="list-item-format">
                                <?php
                                // echo '<pre>'; print_r($fmwrks); die;
                                echo '<li><h4>Frameworks </h4></li>';

                                foreach ($fmwrks21 as $key => $category) {
                                    ?>
                                    <li class="<?php echo (JRequest::getVar('fm_cat') == $category) ? 'activecat' : ''; ?>">
                                        <a class="fgdkngd"  href="#" onclick="getfrmwrk('<?php echo $category ?>')" >
                                            <?php echo $key ?>
                                        </a></li>
                                <?php } ?> 
                                <input type="hidden" id="fm_cat" name="fm_cat"  value="<?php echo (JRequest::getVar('fm_cat')) ? JRequest::getVar('fm_cat') : '' ?>">
                            </ul>    
                        </div>
                    <?php }
                    ?>


                </div>

                <div class="srch-row">
                    <?php
                    $final_subcats = array();
                    $select_subcats = array();
                    // if (JRequest::getVar("fm_cat")) {
//                        $dummy = $categoryModel1->getChildCategoryListObject($vendorId, JRequest::getInt("fm_cat"), 'virtuemart_category_id');
//                        $final_subcats = $categoryModel1->getChildCategoryListObject($vendorId, $dummy->virtuemart_category_id, 'virtuemart_category_id');
                    //  echo '<pre>'; print_r($final_subcats); die;  


                    $categoryModel1 = VmModel::getModel('category');
                    $catesss = $categoryModel1->getCategories(true, false, false, "", $vendorId);
                    // echo '<pre>'; print_r($catesss); die;
                    $subcats = array();
                    if ($catesss)
                        foreach ($catesss as $cate) {
                            if (JRequest::getVar("fm_cat") != '') {
                                $subcats = $categoryModel1->getChildCategoryListObject($vendorId, JRequest::getInt("fm_cat"), 'virtuemart_category_id');
                                break;
                            } else {
                                if ($cate->category_name == "Subcategories") {
                                    $subcats[] = $cate;
                                }
                            }
                        }
//echo '<pre>'; print_r($subcats); die;
                    foreach ($subcats as $subcat) {
                        $subcategories = $categoryModel1->getChildCategoryListObject($vendorId, $subcat->virtuemart_category_id, 'virtuemart_category_id');
                        foreach ($subcategories as $aaaaa) {
                            $final_subcats[] = $aaaaa;
                        }
                    }
                    $final_subcategories = array();
                    $final = array();
                    foreach ($final_subcats as $final_s) {

                        if (!in_array($final_s->category_name, $final)) {
                            $final[] = $final_s->category_name;
                            $final_subcategories[$final_s->category_name] = $final_s->virtuemart_category_id;
                        } else
                            $final_subcategories[$final_s->category_name] = $final_subcategories[$final_s->category_name] . ',' . $final_s->virtuemart_category_id;
                    }
                    ?>
                    <div class="selct-cate">
                        <ul class="list-item-format">
                            <?php
                            //   echo '<pre>'; print_r($final_subcats); die;
                            $a = array();
                            echo '<li><h4>Subcategories </h4></li>';
                            foreach ($final_subcategories as $key1 => $category) {
                                ?>
                                <li class="<?php echo (JRequest::getVar('sub_cat') == $category) ? 'activecat' : '' ?>">
                                    <a class="fgdkngd"  href="#" onclick="getsubcat('<?php echo $category ?>')" >
                                        <?php echo $key1; ?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?> 
                            <input type="hidden" name="sub_cat" id="subcat"  value="<?php echo (JRequest::getVar('sub_cat')) ? JRequest::getVar('sub_cat') : '' ?>">
                        </ul>
                    </div>
                </div>
                <div class="srch-row">
                    <div class="selct-cate">
                        <h5>Sort by</h5>
                        <select id="grp_id" name="group_id" onchange="submit_form1()">
                            <option   value="">Select Group</option>
                            <option <?php echo (JRequest::getVar('group_id') == 'latest') ? "selected=selected" : ""; ?>  value="latest">Latest</option>
                            <option <?php echo (JRequest::getVar('group_id') == 'recent') ? "selected=selected" : ""; ?>  value="recent">Recent</option>
                            <option <?php echo (JRequest::getVar('group_id') == 'featured') ? "selected=selected" : ""; ?> value="featured">Featured</option>
                            <option <?php echo (JRequest::getVar('group_id') == 'topten') ? "selected=selected" : ""; ?>  value="topten">Top Ten</option>

                        </select>
                    </div>
                </div>
                <div class="srch-row">
                    <div class="selct-cate">
                        <h5>Price Range</h5>
<?php $pprice = JRequest::getVar("product_price") ?>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.0.2/css/bootstrap-slider.css" type="text/css" />
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.0.2/bootstrap-slider.min.js"></script>

                        <input id="ex12c" name="product_price" type="text" value="<?php echo ($pprice) ? $pprice : '' ?>" /><br/><br/>
                    </div>
                </div>   
            </div>
        </form>
    </div>
    <div class="col-md-9 product-list-rt-wrap">
<?php
# Show template for : topten,Featured, Latest Products if selected in config BE
if (!empty($this->products)) {
    $products_per_row = VmConfig::get('homepage_products_per_row', 3);
    echo $this->renderVmSubLayout($this->productsLayout, array('products' => $this->products, 'currency' => $this->currency, 'products_per_row' => $products_per_row, 'showRating' => 0)); //$this->loadTemplate('products');
}
?>
    </div>
</div>
<script>

                            jQuery(document).ready(function () {
                                jQuery('#slider12c').on('slideStop', function () {
                                    submit_form();
                                });
//                                jQuery(".slider-handle").click(function () {
//                                    submit_form();
//
//                                });
                                jQuery("#top_search_bar").keyup(function () {
                                    jQuery("#search_item_name").val(jQuery("#top_search_bar").val());
                                });

                                jQuery("#top_search_bar_button").click(function () {
                                    submit_form();
                                });
                            });


                            function submit_form() {
                                var form = document.getElementById("submit_product_form");
                                form.submit();
                            }
                            function submit_form1() {
                                var form = document.getElementById("submit_product_form");
                                form.submit();
                                //location.reload();
                            }
                            function getcat(id) {
                                jQuery("#category_id").val(id);
                                submit_form();
                            }
                            function getsubcat(id) {
                                jQuery("#subcat").val(id);
                                submit_form();
                            }
                            function getfrmwrk(id) {
                                jQuery("#fm_cat").val(id);
                                submit_form();
                            }

                            var aaa = '<?php echo $pprice; ?>';
                            var bb = [];
                            if (aaa) {
                                bb = aaa.split(',');
                            } else {
                                bb = '0, 10000';
                                bb = bb.split(',');

                            }
                            for (a in bb) {
                                bb[a] = parseInt(bb[a], 10); // Explicitly include base as per Álvaro's comment
                            }
                            jQuery("#ex12c").slider({id: "slider12c", min: 0, max: 10000, range: true, value: bb});
                            new Slider("#ex12c", {id: "slider12c", min: 0, max: 10000, range: true, value: bb});

                            jQuery("#clearall").click(function () {
                                jQuery("#subcat").val('');
                                jQuery("#category_id").val('');
                                jQuery("#fm_cat").val('');
                                jQuery("#grp_id").val('');
                                submit_form();

                            });

                            jQuery(".icon-cancel").click(function () {
                                jQuery("#subcat").val('');
                                jQuery("#category_id").val('');
                                jQuery("#fm_cat").val('');
                                //   jQuery("#grp_id").val('');
                                submit_form();
                            });
</script>
<style>

    .fgdkngd {
        line-height: 27px;
        padding: 10px;
    }

    #slider12c .slider-selection {
        background: green;
    } 

    .activecat{
        font-family: sans-serif;
        font-size: 12px;
        font-weight: bold;
    }
    .icon-cancel{
        cursor: pointer;
    }
</style>