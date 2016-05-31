<?php
defined('_JEXEC') or die('Restricted access');
modVirtuemartProducthomeHelper::VmProductJs();
$app = JFactory::getApplication();
$menu = $app->getMenu();
if ($menu->getActive() == $menu->getDefault()) {
    ?>


    <div class="container featured_apps_wrapper" id="vm-content" class="vmgroup<?php echo $params->get('moduleclass_sfx') ?>">

        <div class="vm_controles row">
            <h3 class="col-md-12 text-center">Featured Apps</h3>
            <p class="p-caption">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
            <div class="top_new col-md-12">
                <!--                <ul>
                                    <li class="active"><a href="#" id="top-selling" class="cf" filter-data="topten">Top Selling</a></li>
                                    <li><a href="#" id="new-releases" class="cf"  filter-data="latest">New Releases</a></li>
                                </ul>
                            </div>
                            <div class="cat_filter col-md-6">-->
                <ul>
                    <?php
                    $vendorId = vRequest::getInt('vendorid', 1);
                    $categoryModel1 = VmModel::getModel('category');
                    $categoryModel1->_noLimit = 1;
                    $categories = $categoryModel1->getCategories(true, false, false, "", $vendorId);
//                    echo '<pre>';
//                    print_r($categories);
//                    die;
                    $cats = array();
                    $dupicate = array();
                    foreach ($categories as $category) {
                        if ($category->category_name != 'Subcategories' && $category->category_name != 'Frameworks') {
                            if (!in_array($category->category_name, $dupicate)) {
                                $dupicate[] = $category->category_name;
                                $cats[$category->category_name] = $category->virtuemart_category_id;
                            } else {
                                $cats[$category->category_name] = $cats[$category->category_name] . ',' . $category->virtuemart_category_id;
                            }
                        }
                    }
                    foreach ($cats as $key => $category) {
                        if ($category->category_name != 'Subcategories' && $category->category_name != 'Frameworks') {
                            ?>
                            <li><a href="#" id="filter-<?php echo $key ?>" class="cfc"  filter-child-data="<?php echo $category; ?>">
                                    <?php echo $key; ?></a></li>
                            <!--                    <li><a href="#" id="filter-android" class="cfc"  filter-child-data="14">Android</a></li>
                                                <li><a href="#" id="filter-window" class="cfc"  filter-child-data="15">Window</a></li>
                                                <li><a href="#" id="filter-xbox" class="cfc"  filter-child-data="16">Xbox</a></li>-->
                        <?php }
                    }
                    ?>
                </ul>
            </div>
            <input type="hidden" id="main-cat" value="topten"/>
            <input type="hidden" id="child-cat" value=""/>
        </div>
        <div  id="vm-product-list" class="row" > 
            <!--content appears here--> 

        </div>
    </div>

<?php } ?>