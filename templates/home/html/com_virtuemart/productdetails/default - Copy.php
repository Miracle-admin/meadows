<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8842 2015-05-04 20:34:47Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

//update the profile view
 		jimport( 'joomla.access.access' );
        $groups = JAccess::getGroupsByUser($this->product->created_by);
		
		 if(in_array(13,$groups))
   {
        $user=JFactory::getUser();
		JPluginHelper::importPlugin( 'appmeadows' );
		$dispatcher = JEventDispatcher::getInstance();
		$viewing=$this->product->created_by;
		$viewer=$user->id;
		$Viewtype="app";
		$dispatcher->trigger('onProfileviewed', array("viewtype"=>$Viewtype,"viewing"=>$viewing,"viewer"=>$viewer));
  }
echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));



if(vRequest::getInt('print',false)){ ?>
<body onload="javascript:print();">
<?php } ?>
<div class="mtb-40">
  <div class="productdetails-view productdetails">
    <div class="">
      <?php
    // Product Navigation
    if (VmConfig::get('product_navigation', 1)) {
	?>
      <?php } // Product Navigation END
    ?>
      <?php // Back To Category Button
	if ($this->product->virtuemart_category_id) {
		$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
		$categoryName = vmText::_($this->product->category_name) ;
	} else {
		$catURL =  JRoute::_('index.php?option=com_virtuemart');
		$categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME') ;
	}
	?>
      <?php // afterDisplayTitle Event
    echo $this->product->event->afterDisplayTitle ?>
      <?php
    // Product Edit Link
    //echo $this->edit_link;
    // Product Edit Link END
    ?>
      <?php
    // PDF - Print - Email Icon
    if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
	?>
      <div class="icons">
        <?php

	    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;

		echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
	    //echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
		echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon',false,true,false,'class="printModal"');
		$MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';
	    echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false,true,false,'class="recommened-to-friend"');
	    ?>
        <div class="clear"></div>
      </div>
      <?php } // PDF - Print - Email Icon END
    ?>
<<<<<<< .mine
      <div class="container">
        <div class="row">
          <?php // Product Title   ?>
          <h1 class="product-title"><?php echo $this->product->product_name ?></h1>
          <?php // Product Title END   ?>
        </div>
      </div>
      <div class="vm-product-container">
        <div class="vm-product-media-container col-md-8">
          <?php
echo $this->loadTemplate('images');
?>
        </div>
        <div class="vm-product-details-container col-md-4">
          <div class="products-info-lf">
            <div class="shopper-info ">
              <div class="col-md-3"></div>
              <div class="col-md-5">
                <h4>Fvimagination</h4>
                <p>Development Agency</p>
                <a href="">View Portfolio</a></div>
            </div>
            <div class="box-border-ct">
              <ul id="licence-wrapper">
                <li>
                  <label>
                    <input type="checkbox">
                    <span>Single license </span> </label>
                  <div class="">$111.00 <i class="fa fa-info-circle"></i>
</div>
                </li>
                <li>
                  <label>
                    <input type="checkbox">
                    <span>Multiple license </span> </label>
                  <div class="">$159.00 <i class="fa fa-info-circle"></i>
</div>
                </li>
                <li>
                  <label>
                    <input type="checkbox">
                    <span>Reskin & Launch Service </span> </label>
                  <div class="">$200.00 <i class="fa fa-info-circle"></i>
</div>
                </li>
              </ul>
              <div  class="addcart-wrapper">
                <div class="base-price-wrapper"> <span>Total Price</span> $159.00 </div>
                <div class="add-cart-btn-wrap">
                  <button class="abtn">Add to Cart</button>
=======
    <body onload="javascript:print();">
    <?php } ?>
    <div class="mtb-40">
        <div class="productdetails-view productdetails">
            <div class="">
                <?php
                // Product Navigation
                if (VmConfig::get('product_navigation', 1)) {
                    ?>
                <?php } // Product Navigation END
                ?>
                <?php
                // Back To Category Button
                if ($this->product->virtuemart_category_id) {
                    $catURL = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
                    $categoryName = vmText::_($this->product->category_name);
                } else {
                    $catURL = JRoute::_('index.php?option=com_virtuemart');
                    $categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME');
                }
                ?>
                <?php
                // afterDisplayTitle Event
                echo $this->product->event->afterDisplayTitle
                ?>
                <?php
                // Product Edit Link
                //echo $this->edit_link;
                // Product Edit Link END
                ?>
                <?php
                // PDF - Print - Email Icon
                if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
                    ?>
                    <div class="icons">
                        <?php
                        $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;

                        echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
                        //echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
                        echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon', false, true, false, 'class="printModal"');
                        $MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';
                        echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false, true, false, 'class="recommened-to-friend"');
                        ?>
                        <div class="clear"></div>
                    </div>
                <?php } // PDF - Print - Email Icon END
                ?>
                <div class="container">
                    <div class="row">
                        <?php // Product Title     ?>
                        <h1 class="product-title"><?php echo $this->product->product_name ?></h1>
                        <?php
                        // Product Title END   
//$user2 = JFactory::getUser($this->product->created_by);
                        //echo '<pre>'; print_r($user2); die;
                        ?>
                    </div>
>>>>>>> .r157
                </div>
<<<<<<< .mine
              </div>
            </div>
            <?php /*?>     <?php
=======
                <div class="vm-product-container">
                    <div class="vm-product-media-container col-md-8">
                        <?php
                        echo $this->loadTemplate('images');
                        ?>
                    </div>
                    <div class="vm-product-details-container col-md-4">
                        <div class="products-info-lf">
                            <div class="shopper-info ">
                                <div class="col-md-3">
                                    <?php
                                   // $product_user = JFactory::getUser($this->product->created_by);
                                    // Get a db connection.
                                    $db = JFactory::getDbo();

// Create a new query object.
                                    $puid = $this->product->created_by;
                                    $query = "Select * from #__jblance_user where user_id = 620"; //$db->getQuery(true);

                                    $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
                                    $product_user = $db->loadObject();
                                    if($product_user && $product_user->thumb){ ?>
                                    <img width="90px" src="<?php echo JUri::base().'/images/jblance/'.$product_user->thumb ?>" />
                                  <?php  }
                                    ?>
                                </div>
                                <div class="col-md-5">
                                    <h4>Fvimagination</h4>
                                    <p>Development Agency</p>
                                    <a href="<?php echo JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&Itemid=198&id=' . $this->product->created_by) ?>">View Portfolio</a>
                                </div>
                            </div>
                            <div class="box-border-ct">
                                <!--                                <ul id="licence-wrapper">-->
>>>>>>> .r157

<<<<<<< .mine
    if (!empty($this->product->product_s_desc)) {
	?>
          <div class="product-short-description">
            <?php
	  
	    echo nl2br($this->product->product_s_desc);
	    ?>
          </div>
          <?php
    } 
=======
<?php
echo shopFunctionsF::renderVmSubLayout('rating', array('showRating' => $this->showRating, 'product' => $this->product));
>>>>>>> .r157

<<<<<<< .mine
	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
    ?><?php */?>
            <?php
		// TODO in Multi-Vendor not needed at the moment and just would lead to confusion
		/* $link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&virtuemart_vendor_id='.$this->product->virtuemart_vendor_id);
		  $text = vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
		  echo '<span class="bold">'. vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
		 */
		?>
            <br>
            <?php
		echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));
=======
if (is_array($this->productDisplayShipments)) {
    foreach ($this->productDisplayShipments as $productDisplayShipment) {
        echo $productDisplayShipment . '<br />';
    }
}
if (is_array($this->productDisplayPayments)) {
    foreach ($this->productDisplayPayments as $productDisplayPayment) {
        echo $productDisplayPayment . '<br />';
    }
}
//In case you are not happy using everywhere the same price display fromat, just create your own layout
//in override /html/fields and use as first parameter the name of your file
?>
                                <div class="clear"></div>
                                <?php
                                echo shopFunctionsF::renderVmSubLayout('addtocart', array('product' => $this->product));
>>>>>>> .r157

		if (is_array($this->productDisplayShipments)) {
		    foreach ($this->productDisplayShipments as $productDisplayShipment) {
			echo $productDisplayShipment . '<br />';
		    }
		}
		if (is_array($this->productDisplayPayments)) {
		    foreach ($this->productDisplayPayments as $productDisplayPayment) {
			echo $productDisplayPayment . '<br />';
		    }
		}

<<<<<<< .mine
		//In case you are not happy using everywhere the same price display fromat, just create your own layout
		//in override /html/fields and use as first parameter the name of your file
		echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
		?>
            <div class="clear"></div>
            <?php
		echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
=======
                                // Ask a question about this product
                                if (VmConfig::get('ask_question', 0) == 1) {
                                    $askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
                                    ?>
                                    <div class="ask-a-question"> <a class="ask-a-question" href="<?php echo $askquestion_url ?>" rel="nofollow" ><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a> </div>
                                    <?php
                                }
                                ?>
                                <div class="base-price-wrapper"> 
                                <?php
                                echo shopFunctionsF::renderVmSubLayout('customprices', array('product' => $this->product, 'currency' => $this->currency));
                                ?>
>>>>>>> .r157

		echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$this->product));

		// Ask a question about this product
		if (VmConfig::get('ask_question', 0) == 1) {
			$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
			?>
            <div class="ask-a-question"> <a class="ask-a-question" href="<?php echo $askquestion_url ?>" rel="nofollow" ><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a> </div>
            <?php
		}
		?>
            <?php /*?> <?php
		// Manufacturer of the Product
		if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
		    echo $this->loadTemplate('manufacturer');
		}
		?><?php */?>
          </div>
        </div>
        <div class="clear"></div>
        <div class="">
          <div class="col-md-8">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#description">Description</a></li>
              <li><a data-toggle="tab" href="#review">Review</a></li>
            </ul>
            <div class="description-detail box-border-ct tab-content">
              <div id="description" class="tab-pane fade in active">
                <?php
	$count_images = count ($this->product->images);
	if ($count_images > 1) {
		echo $this->loadTemplate('images_additional');
	}

	// event onContentBeforeDisplay
	echo $this->product->event->beforeDisplayContent; ?>
                <?php
	// Product Description
	if (!empty($this->product->product_desc)) {
	    ?>
                <div class="product-description">
                  <?php /** @todo Test if content plugins modify the product description */ ?>
                  <span class="title"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span> <?php echo $this->product->product_desc; ?> </div>
                <?php
    } // Product Description END

	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'normal'));

    // Product Packaging
    $product_packaging = '';
    if ($this->product->product_box) {
	?>
                <div class="product-box">
                  <?php
	        echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
	    ?>
                </div>
                <?php } // Product Packaging END ?>
                <?php 
	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'onbot'));

<<<<<<< .mine
    echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products','customTitle' => true ));
=======
                                    <!--                                    <div class="add-cart-btn-wrap">
                                                                            <button class="abtn">Add to Cart</button>
                                                                        </div>-->
                                </div>
                            </div>
<?php /* ?>     <?php
>>>>>>> .r157

<<<<<<< .mine
	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_categories','class'=> 'product-related-categories'));
=======
  if (!empty($this->product->product_s_desc)) {
  ?>
  <div class="product-short-description">
  <?php
>>>>>>> .r157

<<<<<<< .mine
	?>
              </div>
              <div id="review" class="tab-pane fade">
                <?php // onContentAfterDisplay event
echo $this->product->event->afterDisplayContent;
=======
  echo nl2br($this->product->product_s_desc);
  ?>
  </div>
  <?php
  }
>>>>>>> .r157

<<<<<<< .mine
echo $this->loadTemplate('reviews');
=======
  echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
  ?><?php */ ?>
                            <?php
                            // TODO in Multi-Vendor not needed at the moment and just would lead to confusion
                            /* $link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&virtuemart_vendor_id='.$this->product->virtuemart_vendor_id);
                              $text = vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
                              echo '<span class="bold">'. vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
                             */
                            ?>
                            <br>
>>>>>>> .r157

<<<<<<< .mine
=======
<?php /* ?> <?php
  // Manufacturer of the Product
  if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
  echo $this->loadTemplate('manufacturer');
  }
  ?><?php */ ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="">
                        <div class="col-md-8">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#description">Description</a></li>
                                <li><a data-toggle="tab" href="#review">Review</a></li>
                            </ul>
                            <div class="description-detail box-border-ct tab-content">
                                <div id="description" class="tab-pane fade in active">
<?php
$count_images = count($this->product->images);
if ($count_images > 1) {
    echo $this->loadTemplate('images_additional');
}
>>>>>>> .r157

<<<<<<< .mine
=======
// event onContentBeforeDisplay
echo $this->product->event->beforeDisplayContent;
?>
                                    <?php
                                    // Product Description
                                    if (!empty($this->product->product_desc)) {
                                        ?>
                                        <div class="product-description">
                                        <?php /** @todo Test if content plugins modify the product description */ ?>
                                            <span class="title"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span> <?php echo $this->product->product_desc; ?> </div>
                                            <?php
                                        } // Product Description END

                                        //echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'normal'));

                                        // Product Packaging
                                        $product_packaging = '';
                                        if ($this->product->product_box) {
                                            ?>
                                        <div class="product-box">
                                        <?php
                                        echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') . $this->product->product_box;
                                        ?>
                                        </div>
                                        <?php } // Product Packaging END ?>
                                    <?php
                                    echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'onbot'));

                                    echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'related_products', 'class' => 'product-related-products', 'customTitle' => true));

                                    echo shopFunctionsF::renderVmSubLayout('customfields', array('product' => $this->product, 'position' => 'related_categories', 'class' => 'product-related-categories'));
                                    ?>
                                </div>
                                <div id="review" class="tab-pane fade">
<?php
// onContentAfterDisplay event
echo $this->product->event->afterDisplayContent;

echo $this->loadTemplate('reviews');
//echo '<pre>'; print_r($this->product); die;

>>>>>>> .r157
// Show child categories
<<<<<<< .mine
if (VmConfig::get('showCategory', 1)) {
	echo $this->loadTemplate('showcategory');
}
=======
if (VmConfig::get('showCategory', 1)) {
    echo $this->loadTemplate('showcategory');
}
>>>>>>> .r157

$j = 'jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});
});';
//vmJsApi::addJScript('recalcReady',$j);

<<<<<<< .mine
/** GALT
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
$j = "Virtuemart.container = jQuery('.productdetails-view');
=======
/** GALT
 * Notice for Template Developers!
 * Templates must set a Virtuemart.container variable as it takes part in
 * dynamic content update.
 * This variable points to a topmost element that holds other content.
 */
$j = "Virtuemart.container = jQuery('.productdetails-view');
>>>>>>> .r157
Virtuemart.containerSelector = '.productdetails-view';";

<<<<<<< .mine
vmJsApi::addJScript('ajaxContent',$j);
=======
vmJsApi::addJScript('ajaxContent', $j);
>>>>>>> .r157

<<<<<<< .mine
echo vmJsApi::writeJS();
?>
              </div>
=======
echo vmJsApi::writeJS();
?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-border-ct products-tags">
                                <h5> Categories</h5>
                                <p>App Templates</p>
                                <hr>
                                <h5>Operating system</h5>
                                <p>iOS 8.0.x, iOS 8.1.x, iOS 9.0.x, iOS 9.1.x, iOS 9.2.x</p>
                                <hr>
                                <h5>Platform</h5>
                                <p>iOS</p>
                                <hr>
                                <h5>Subcategories</h5>
                                <p>Photography</p>
                                <hr>
                                <h5>Files included</h5>
                                <p>Layered PSD, .xib, .pch, .m, .h</p>
                                <hr>
                                <h5>Integration time:</h5>
                                <p>01:00</p>
                                <hr>
                                <h5>Video</h5>
                                <p>View Video</p>
                            </div>
                        </div>
                    </div>
                </div>
>>>>>>> .r157
            </div>
          </div>
          <div class="col-md-4">
            <div class="box-border-ct products-tags">
              <h5> Categories</h5>
              <p>App Templates</p>
              <hr>
              <h5>Operating system</h5>
              <p>iOS 8.0.x, iOS 8.1.x, iOS 9.0.x, iOS 9.1.x, iOS 9.2.x</p>
              <hr>
              <h5>Platform</h5>
              <p>iOS</p>
              <hr>
              <h5>Subcategories</h5>
              <p>Photography</p>
              <hr>
              <h5>Files included</h5>
              <p>Layered PSD, .xib, .pch, .m, .h</p>
              <hr>
              <h5>Integration time:</h5>
              <p>01:00</p>
              <hr>
              <h5>Video</h5>
              <p>View Video</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
