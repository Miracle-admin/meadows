<?php

/**
 * Controller for the cart
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 8847 2015-05-06 12:22:37Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * Controller for the cart view
 *
 * @package VirtueMart
 * @subpackage Cart
 */
class VirtueMartControllerCart extends JControllerLegacy {

    /**
     * Construct the cart
     *
     * @access public
     */
    public function __construct() {
        parent::__construct();
        if (VmConfig::get('use_as_catalog', 0)) {
            $app = JFactory::getApplication();
            $app->redirect('index.php');
        } else {
            if (!class_exists('VirtueMartCart'))
                require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
            if (!class_exists('calculationHelper'))
                require(VMPATH_ADMIN . DS . 'helpers' . DS . 'calculationh.php');
        }
        $this->useSSL = VmConfig::get('useSSL', 0);
        $this->useXHTML = false;
    }

    /**
     * Override of display
     *
     * @return  JController  A JController object to support chaining.
     * @since   11.1
     */
    public function display($cachable = false, $urlparams = false) {

        if (VmConfig::get('use_as_catalog', 0)) {
            // Get a continue link
            $virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId();
            $categoryLink = '';
            if ($virtuemart_category_id) {
                $categoryLink = '&virtuemart_category_id=' . $virtuemart_category_id;
            }
            $ItemId = shopFunctionsF::getLastVisitedItemId();
            $ItemIdLink = '';
            if ($ItemId) {
                $ItemIdLink = '&Itemid=' . $ItemId;
            }

            $continue_link = JRoute::_('index.php?option=com_virtuemart&view=category' . $categoryLink . $ItemIdLink, FALSE);
            $app = JFactory::getApplication();
            $app->redirect($continue_link, 'This is a catalogue, you cannot acccess the cart');
        }

        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $viewName = vRequest::getCmd('view', $this->default_view);
        $viewLayout = vRequest::getCmd('layout', 'default');

        $view = $this->getView($viewName, $viewType, '', array('layout' => $viewLayout));

        $view->assignRef('document', $document);

        $cart = VirtueMartCart::getCart();

        $cart->order_language = vRequest::getString('order_language', $cart->order_language);

        $cart->prepareCartData();
        $request = vRequest::getRequest();
        $task = vRequest::getCmd('task');
        if (($task == 'confirm' or isset($request['confirm'])) and ! $cart->getInCheckOut()) {

            $cart->confirmDone();
            $view = $this->getView('cart', 'html');
            $view->setLayout('order_done');
            $cart->_fromCart = false;
            $view->display();
            return true;
        } else {
            //$cart->_inCheckOut = false;
            $redirect = (isset($request['checkout']) or $task == 'checkout');
            $cart->_inConfirm = false;
            $cart->checkoutData($redirect);
        }

        $cart->_fromCart = false;
        $view->display();

        return $this;
    }

    public function updatecart($html = true, $force = null) {

        $cart = VirtueMartCart::getCart();
        $cart->_fromCart = true;
        $cart->_redirected = false;
        if (vRequest::get('cancel', 0)) {
            $cart->_inConfirm = false;
        }
        if ($cart->getInCheckOut()) {
            vRequest::setVar('checkout', true);
        }
        $cart->saveCartFieldsInCart();

        if ($cart->updateProductCart()) {
            vmInfo('COM_VIRTUEMART_PRODUCT_UPDATED_SUCCESSFULLY');
        }

        $STsameAsBT = vRequest::getInt('STsameAsBT', vRequest::getInt('STsameAsBTjs', false));
        if ($STsameAsBT) {
            $cart->STsameAsBT = $STsameAsBT;
        }

        $currentUser = JFactory::getUser();
        if (!$currentUser->guest) {
            $cart->selected_shipto = vRequest::getVar('shipto', $cart->selected_shipto);
            if (!empty($cart->selected_shipto)) {
                $userModel = VmModel::getModel('user');
                $stData = $userModel->getUserAddressList($currentUser->id, 'ST', $cart->selected_shipto);

                if (isset($stData[0]) and is_object($stData[0])) {
                    $stData = get_object_vars($stData[0]);
                    $cart->ST = $stData;
                    $cart->STsameAsBT = 0;
                } else {
                    $cart->selected_shipto = 0;
                }
            }
            if (empty($cart->selected_shipto)) {
                $cart->STsameAsBT = 1;
                $cart->selected_shipto = 0;
                //$cart->ST = $cart->BT;
            }
        } else {
            $cart->selected_shipto = 0;
            if (!empty($cart->STsameAsBT)) {
                //$cart->ST = $cart->BT;
            }
        }

        if (!isset($force))
            $force = VmConfig::get('oncheckout_opc', true);
        $cart->setShipmentMethod($force, !$html);
        $cart->setPaymentMethod($force, !$html);

        $cart->prepareCartData();

        $coupon_code = trim(vRequest::getString('coupon_code', ''));
        if (!empty($coupon_code)) {
            $msg = $cart->setCouponCode($coupon_code);
            if ($msg)
                vmInfo($msg);
        }


        if ($html) {
            $this->display();
        } else {
            $json = new stdClass();
            ob_start();
            $this->display();
            $json->msg = ob_get_clean();
            echo json_encode($json);
            jExit();
        }
    }

    public function updatecartJS() {
        $this->updatecart(false);
    }

    /**
     * legacy
     * @deprecated
     */
    public function confirm() {
        $this->updatecart();
    }

    public function setshipment() {
        $this->updatecart(true, true);
    }

    public function setpayment() {
        $this->updatecart(true, true);
    }

    /**
     * Add the product to the cart
     * @access public
     */
    public function add() {
        $mainframe = JFactory::getApplication();
        if (VmConfig::get('use_as_catalog', 0)) {
            $msg = vmText::_('COM_VIRTUEMART_PRODUCT_NOT_ADDED_SUCCESSFULLY');
            $type = 'error';
            $mainframe->redirect('index.php', $msg, $type);
        }
        $cart = VirtueMartCart::getCart();
        if ($cart) {
            $virtuemart_product_ids = vRequest::getInt('virtuemart_product_id');
            $error = false;
            $cart->add($virtuemart_product_ids, $error);
            if (!$error) {
                $msg = vmText::_('COM_VIRTUEMART_PRODUCT_ADDED_SUCCESSFULLY');
                $type = '';
            } else {
                $msg = vmText::_('COM_VIRTUEMART_PRODUCT_NOT_ADDED_SUCCESSFULLY');
                $type = 'error';
            }

            $mainframe->enqueueMessage($msg, $type);
            $mainframe->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE));
        } else {
            $mainframe->enqueueMessage('Cart does not exist?', 'error');
        }
    }

    /**
     * Add the product to the cart, with JS
     * @access public
     */
    public function addJS() {

        $this->json = new stdClass();

//        $api_AUP = JPATH_SITE . DS . 'components' . DS . 'com_alphauserpoints' . DS . 'helper.php';
//        if (file_exists($api_AUP)) {
//            require_once ($api_AUP);
//        }
//        $user = JFactory::getUser();
//        $userid = $user->id;
//        $aupids = AlphaUserPointsHelper::getAnyUserReferreID($userid);
//
//        $pro_model = VmModel::getModel("product");
//        $product1 = $pro_model->getProduct(JRequest::getInt("pid"));
//
//        $expiry = '+' . $product1->customfieldsSorted['addtocart'][0]->downloadable_expires_quantity . ' ' . $product1->customfieldsSorted['addtocart'][0]->downloadable_expires_period;
//        $time = strtotime($product->created_on);
//        $expiry_date = date("Y-m-d h:i:s", strtotime("$expiry", $time));
//        $today = date("Y-m-d h:i:s");
//
//        if (($expiry_date <= $today) && $aupids && $product->virtuemart_vendor_id) {
            $cart = VirtueMartCart::getCart(false);
            if ($cart) {
                $view = $this->getView('cart', 'json');
                $virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId();
                $categoryLink = '';
                if ($virtuemart_category_id) {
                    $categoryLink = '&view=category&virtuemart_category_id=' . $virtuemart_category_id;
                }

                $continue_link = JRoute::_('index.php?option=com_virtuemart' . $categoryLink);

                $virtuemart_product_ids = vRequest::getInt('virtuemart_product_id');

                $view = $this->getView('cart', 'json');
                $errorMsg = 0;

                $products = $cart->add($virtuemart_product_ids, $errorMsg);


                $view->setLayout('padded');
                $this->json->stat = '1';

                if (!$products or count($products) == 0) {
                    $product_name = vRequest::get('pname');
                    $virtuemart_product_id = vRequest::getInt('pid');
                    if ($product_name && $virtuemart_product_id) {
                        $view->product_name = $product_name;
                        $view->virtuemart_product_id = $virtuemart_product_id;
                    } else {
                        $this->json->stat = '2';
                    }
                    $view->setLayout('perror');
                }

                $view->assignRef('products', $products);
                $view->assignRef('errorMsg', $errorMsg);

                ob_start();
                $view->display();
                $this->json->msg = ob_get_clean();
            } else {
                $this->json->msg = '<a href="' . JRoute::_('index.php?option=com_virtuemart', FALSE) . '" >' . vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';
                $this->json->msg .= '<p>' . vmText::_('COM_VIRTUEMART_MINICART_ERROR') . '</p>';
                $this->json->stat = '0';
            }
//        } else {
//            $view = $this->getView('cart', 'json');
//            $view->setLayout('perror');
//            $this->json->msg = '<span class="error_class"> This product is purchased and not expired! Please download..!</span>';
//            $this->json->stat = '1';
//            ob_start();
//            $view->display();
//            ob_get_clean();
//        }
        echo json_encode($this->json);
        jExit();
    }

    /**
     * Add the product to the cart, with JS
     *
     * @access public
     */
    public function viewJS() {

        if (!class_exists('VirtueMartCart'))
            require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
        $cart = VirtueMartCart::getCart(false);
        $cart->prepareCartData();
        $data = $cart->prepareAjaxData(true);

        echo json_encode($data);
        Jexit();
    }

    /**
     * For selecting couponcode to use, opens a new layout
     */
    public function edit_coupon() {

        $view = $this->getView('cart', 'html');
        $view->setLayout('edit_coupon');

        // Display it all
        $view->display();
    }

    /**
     * Store the coupon code in the cart
     * @author Max Milbers
     */
    public function setcoupon() {

        /* Get the coupon_code of the cart */
        $coupon_code = vRequest::getString('coupon_code', '');

        $cart = VirtueMartCart::getCart();
        if ($cart) {
            $this->couponCode = '';

            if (!empty($coupon_code)) {
                $app = JFactory::getApplication();
                $msg = $cart->setCouponCode($coupon_code);
                $cart->setOutOfCheckout();
                $app->redirect(JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE), $msg);
            }
        }
    }

    /**
     * For selecting shipment, opens a new layout
     */
    public function edit_shipment() {


        $view = $this->getView('cart', 'html');
        $view->setLayout('select_shipment');

        // Display it all
        $view->display();
    }

    /**
     * To select a payment method
     */
    public function editpayment() {

        $view = $this->getView('cart', 'html');
        $view->setLayout('select_payment');

        // Display it all
        $view->display();
    }

    /**
     * Delete a product from the cart
     * @access public
     */
    public function delete() {
        $mainframe = JFactory::getApplication();
        /* Load the cart helper */
        $cart = VirtueMartCart::getCart();
        if ($cart->removeProductCart())
            $mainframe->enqueueMessage(vmText::_('COM_VIRTUEMART_PRODUCT_REMOVED_SUCCESSFULLY'));
        else
            $mainframe->enqueueMessage(vmText::_('COM_VIRTUEMART_PRODUCT_NOT_REMOVED_SUCCESSFULLY'), 'error');

        $this->display();
    }

    public function getManager() {
        $adminID = JFactory::getSession()->get('vmAdminID', false);
        if ($adminID) {
            if (!class_exists('vmCrypt'))
                require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmcrypt.php');
            $adminID = vmCrypt::decrypt($adminID);
            return JFactory::getUser($adminID);
        } else {
            return JFactory::getUser();
        }
    }

    /**
     * Change the shopper
     *
     * @author Maik Künnemann
     */
    public function changeShopper() {
        JSession::checkToken() or jexit('Invalid Token');
        $current = $this->getManager();
        $manager = false;

        $redirect = vRequest::getString('redirect', false);
        if ($redirect) {
            $red = $redirect;
        } else {
            $red = JRoute::_('index.php?option=com_virtuemart&view=cart');
        }

        $app = JFactory::getApplication();
        if ($current->authorise('core.admin', 'com_virtuemart')) {
            $admin = true;
        } else if ($current->authorise('vm.user', 'com_virtuemart')) {
            $manager = true;
        } else {

            $app->enqueueMessage(vmText::sprintf('COM_VIRTUEMART_CART_CHANGE_SHOPPER_NO_PERMISSIONS', $current->name . ' (' . $current->username . ')'), 'error');
            $app->redirect($red);
            return false;
        }

        $userID = vRequest::getCmd('userID');
        $newUser = JFactory::getUser($userID);

        if ($manager and ! empty($userID) and $userID != $current->id) {
            if ($newUser->authorise('core.admin', 'com_virtuemart') or $newUser->authorise('vm.user', 'com_virtuemart')) {
                $app->enqueueMessage(vmText::sprintf('COM_VIRTUEMART_CART_CHANGE_SHOPPER_NO_PERMISSIONS', $current->name . ' (' . $current->username . ')'), 'error');
                $app->redirect($red);
            }
        }

        $searchShopper = vRequest::getString('searchShopper');

        if (!empty($searchShopper)) {
            $this->display();
            return false;
        }

        //update session
        $session = JFactory::getSession();
        $adminID = $session->get('vmAdminID');
        if (!isset($adminID)) {
            if (!class_exists('vmCrypt'))
                require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmcrypt.php');
            $session->set('vmAdminID', vmCrypt::encrypt($current->id));
        }
        $session->set('user', $newUser);

        //update cart data
        $cart = VirtueMartCart::getCart();
        $usermodel = VmModel::getModel('user');
        $data = $usermodel->getUserAddressList(vRequest::getCmd('userID'), 'BT');
        foreach ($data[0] as $k => $v) {
            $data[$k] = $v;
        }
        $cart->BT['email'] = $newUser->email;

        $cart->ST = 0;
        $cart->STsameAsBT = 1;
        $cart->selected_shipto = 0;
        $cart->virtuemart_shipmentmethod_id = 0;
        $cart->saveAddressInCart($data, 'BT');

        $msg = vmText::sprintf('COM_VIRTUEMART_CART_CHANGED_SHOPPER_SUCCESSFULLY', $newUser->name . ' (' . $newUser->username . ')');

        if (empty($userID)) {
            $red = JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT');
            $msg = vmText::sprintf('COM_VIRTUEMART_CART_CHANGED_SHOPPER_SUCCESSFULLY', '');
        }

        $app->enqueueMessage($msg, 'info');
        $app->redirect($red);
    }

    function cancel() {

        $cart = VirtueMartCart::getCart();
        if ($cart) {
            $cart->setOutOfCheckout();
        }
        $this->display();
    }

}

//pure php no Tag
