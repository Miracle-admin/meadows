<?php

/**
 *
 * View for the shopping cart
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers
 * @author Oscar van Eijk
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 8832 2015-04-15 16:05:49Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView'))
    require(VMPATH_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * View for the shopping cart
 * @package VirtueMart
 * @author Max Milbers
 * @author Patrick Kohl
 */
class VirtueMartViewCart extends VmView {

    var $pointAddress = false;

    public function display($tpl = null) {


        $app = JFactory::getApplication();

        $this->prepareContinueLink();
        if (VmConfig::get('use_as_catalog', 0)) {
            vmInfo('This is a catalogue, you cannot access the cart');
            $app->redirect($this->continue_link);
        }
        $isloggedin = 0;
        if ($user->id) {
            $isloggedin = 1;
        }
        $pathway = $app->getPathway();
        $document = JFactory::getDocument();
        $document->setMetaData('robots', 'NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET');

        $this->layoutName = $this->getLayout();
        
        if (!$this->layoutName)
            $this->layoutName = vRequest::getCmd('layout', 'default');

        $format = vRequest::getCmd('format');

        if (!class_exists('VirtueMartCart'))
            require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
        $this->cart = VirtueMartCart::getCart();

        $this->cart->prepareVendor();

        //Why is this here, when we have view.raw.php
        if ($format == 'raw') {
            vRequest::setVar('layout', 'mini_cart');
            $this->setLayout('mini_cart');
            $this->prepareContinueLink();
        }
        //  echo '<pre>';  print_r($this->layoutName); die;
//        switch ($this->layoutName) {
//            case 'select_shipment':
//                $this->cart->prepareCartData();
//                $this->lSelectShipment();
//
//                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'), JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE));
//                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_SELECTSHIPMENT'));
//                $document->setTitle(vmText::_('COM_VIRTUEMART_CART_SELECTSHIPMENT'));
//                break;
//            case 'select_payment':
//                $this->cart->prepareCartData();
//
//                $this->lSelectPayment();
//
//                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'), JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE));
//                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_SELECTPAYMENT'));
//                $document->setTitle(vmText::_('COM_VIRTUEMART_CART_SELECTPAYMENT'));
//                break;
//            case 'order_done':
//                VmConfig::loadJLang('com_virtuemart_shoppers', true);
//                $this->lOrderDone();
//
//                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_THANKYOU'));
//                $document->setTitle(vmText::_('COM_VIRTUEMART_CART_THANKYOU'));
//                break;
//            default:
//                VmConfig::loadJLang('com_virtuemart_shoppers', true);
//
//                $this->renderCompleteAddressList();
//
//                if (!class_exists('VirtueMartModelUserfields')) {
//                    require(VMPATH_ADMIN . DS . 'models' . DS . 'userfields.php');
//                }
//
//                $userFieldsModel = VmModel::getModel('userfields');
//
//                $userFieldsCart = $userFieldsModel->getUserFields(
//                        'cart'
//                        , array('captcha' => true, 'delimiters' => true) // Ignore these types
//                        , array('delimiter_userinfo', 'user_is_vendor', 'username', 'password', 'password2', 'agreed', 'address_type') // Skips
//                );
//
//                $this->userFieldsCart = $userFieldsModel->getUserFieldsFilled(
//                        $userFieldsCart
//                        , $this->cart->cartfields
//                );
//
//                if (!class_exists('CurrencyDisplay'))
//                    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
//
//                $this->currencyDisplay = CurrencyDisplay::getInstance($this->cart->pricesCurrency);
//
//                $customfieldsModel = VmModel::getModel('Customfields');
//                $this->assignRef('customfieldsModel', $customfieldsModel);
//
//                $this->lSelectCoupon();
//
//                $totalInPaymentCurrency = $this->getTotalInPaymentCurrency();
//
//                $checkoutAdvertise = $this->getCheckoutAdvertise();
//
//                if ($this->cart->getDataValidated()) {
//                    if ($this->cart->_inConfirm) {
//                        $pathway->addItem(vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM_MNU'));
//                        $document->setTitle(vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM_MNU'));
//                        $text = vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM');
//                        $this->checkout_task = 'cancel';
//                    } else {
//                        $pathway->addItem(vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU'));
//                        $document->setTitle(vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU'));
//                        $text = vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');
//                        $this->checkout_task = 'confirm';
//                    }
//                } else {
//                    $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'));
//                    $document->setTitle(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'));
//                    $text = vmText::_('COM_VIRTUEMART_CHECKOUT_TITLE');
//                    $this->checkout_task = 'checkout';
//                }
//                $this->checkout_link_html = '<button type="submit"  id="checkoutFormSubmit" name="' . $this->checkout_task . '" value="1" class="vm-button-correct" ><span>' . $text . '</span> </button>';
//
//
//                if (VmConfig::get('oncheckout_opc', 1)) {
//                    if (!class_exists('vmPSPlugin'))
//                        require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
//                    JPluginHelper::importPlugin('vmshipment');
//                    JPluginHelper::importPlugin('vmpayment');
//                    //vmdebug('cart view oncheckout_opc ');
//                    if (!$this->lSelectShipment() or ! $this->lSelectPayment()) {
//                        vmInfo('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST');
//                        $this->pointAddress = true;
//                    }
//                } else {
//                    $this->checkPaymentMethodsConfigured();
//                    $this->checkShipmentMethodsConfigured();
//                }
//
//                if ($this->cart->virtuemart_shipmentmethod_id) {
//                    $shippingText = vmText::_('COM_VIRTUEMART_CART_CHANGE_SHIPPING');
//                } else {
//                    $shippingText = vmText::_('COM_VIRTUEMART_CART_EDIT_SHIPPING');
//                }
//                $this->assignRef('select_shipment_text', $shippingText);
//
//                if ($this->cart->virtuemart_paymentmethod_id) {
//                    $paymentText = vmText::_('COM_VIRTUEMART_CART_CHANGE_PAYMENT');
//                } else {
//                    $paymentText = vmText::_('COM_VIRTUEMART_CART_EDIT_PAYMENT');
//                }
//                $this->assignRef('select_payment_text', $paymentText);
//
//                $this->cart->prepareAddressFieldsInCart();
//
//                $this->layoutName = $this->cart->layout;
//                if (empty($this->layoutName))
//                    $this->layoutName = 'default';
//
//                if ($this->cart->layoutPath) {
//                    $this->addTemplatePath($this->cart->layoutPath);
//                }
//
//                if (!empty($this->layoutName) and $this->layoutName != 'default') {
//                    $this->setLayout(strtolower($this->layoutName));
//                }
//                //set order language
//                $lang = JFactory::getLanguage();
//                $user = JFactory::getUser();
//                $this->assignRef('isloggedin', $isloggedin);
//                $order_language = $lang->getTag();
//                $this->assignRef('order_language', $order_language);
//
//                break;
//        }
        if ($this->layoutName == 'select_shipment') {

            $this->cart->prepareCartData();
            $this->lSelectShipment();

            $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'), JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE));
            $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_SELECTSHIPMENT'));
            $document->setTitle(vmText::_('COM_VIRTUEMART_CART_SELECTSHIPMENT'));
        } else if ($this->layoutName == 'select_payment') {
            $this->cart->prepareCartData();

            $this->lSelectPayment();

            $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'), JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE));
            $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_SELECTPAYMENT'));
            $document->setTitle(vmText::_('COM_VIRTUEMART_CART_SELECTPAYMENT'));
        } else if ($this->layoutName == 'order_done') {
            VmConfig::loadJLang('com_virtuemart_shoppers', true);
            $this->lOrderDone();

            $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_THANKYOU'));
            $document->setTitle(vmText::_('COM_VIRTUEMART_CART_THANKYOU'));
        } else {
            VmConfig::loadJLang('com_virtuemart_shoppers', true);

            $this->renderCompleteAddressList();

            if (!class_exists('VirtueMartModelUserfields')) {
                require(VMPATH_ADMIN . DS . 'models' . DS . 'userfields.php');
            }

            $userFieldsModel = VmModel::getModel('userfields');

            $userFieldsCart = $userFieldsModel->getUserFields(
                    'cart'
                    , array('captcha' => true, 'delimiters' => true) // Ignore these types
                    , array('delimiter_userinfo', 'user_is_vendor', 'username', 'password', 'password2', 'agreed', 'address_type') // Skips
            );

            $this->userFieldsCart = $userFieldsModel->getUserFieldsFilled(
                    $userFieldsCart
                    , $this->cart->cartfields
            );

            if (!class_exists('CurrencyDisplay'))
                require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');

            $this->currencyDisplay = CurrencyDisplay::getInstance($this->cart->pricesCurrency);

            $customfieldsModel = VmModel::getModel('Customfields');
            $this->assignRef('customfieldsModel', $customfieldsModel);

            $this->lSelectCoupon();

            $totalInPaymentCurrency = $this->getTotalInPaymentCurrency();

            $checkoutAdvertise = $this->getCheckoutAdvertise();
            $user = JFactory::getUser();
            if ($this->cart->getDataValidated() && $user->id) { 
                if ($this->cart->_inConfirm) {
                    $pathway->addItem(vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM_MNU'));
                    $document->setTitle(vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM_MNU'));
                    $text = vmText::_('COM_VIRTUEMART_CANCEL_CONFIRM');
                    $this->checkout_task = 'cancel';
                } else {
                    $pathway->addItem(vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU'));
                    $document->setTitle(vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU'));
                    $text = vmText::_('COM_VIRTUEMART_ORDER_CONFIRM_MNU');
                    $this->checkout_task = 'confirm';
                }
            } else {
                $pathway->addItem(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'));
                $document->setTitle(vmText::_('COM_VIRTUEMART_CART_OVERVIEW'));
                $text = vmText::_('COM_VIRTUEMART_CHECKOUT_TITLE');
                $this->checkout_task = 'checkout';
            }
          
            $this->checkout_link_html = '<button type="submit"  id="checkoutFormSubmit" name="' . $this->checkout_task . '" value="1" class="vm-button-correct" ><span>' . $text . '</span> </button>';


            if (VmConfig::get('oncheckout_opc', 1)) {
                if (!class_exists('vmPSPlugin'))
                    require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
                JPluginHelper::importPlugin('vmshipment');
                JPluginHelper::importPlugin('vmpayment');
                //vmdebug('cart view oncheckout_opc ');
                if (!$this->lSelectShipment() or ! $this->lSelectPayment()) {
                    vmInfo('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST');
                    $this->pointAddress = true;
                }
            } else {
                $this->checkPaymentMethodsConfigured();
                $this->checkShipmentMethodsConfigured();
            }

            if ($this->cart->virtuemart_shipmentmethod_id) {
                $shippingText = vmText::_('COM_VIRTUEMART_CART_CHANGE_SHIPPING');
            } else {
                $shippingText = vmText::_('COM_VIRTUEMART_CART_EDIT_SHIPPING');
            }
            $this->assignRef('select_shipment_text', $shippingText);

            if ($this->cart->virtuemart_paymentmethod_id) {
                $paymentText = vmText::_('COM_VIRTUEMART_CART_CHANGE_PAYMENT');
            } else {
                $paymentText = vmText::_('COM_VIRTUEMART_CART_EDIT_PAYMENT');
            }
            $this->assignRef('select_payment_text', $paymentText);

            $this->cart->prepareAddressFieldsInCart();

            $this->layoutName = $this->cart->layout;
            if (empty($this->layoutName))
                $this->layoutName = 'default';

            if ($this->cart->layoutPath) {
                $this->addTemplatePath($this->cart->layoutPath);
            }

            if (!empty($this->layoutName) and $this->layoutName != 'default') {
                $this->setLayout(strtolower($this->layoutName));
            }
            //set order language
            $lang = JFactory::getLanguage();
            $order_language = $lang->getTag();
            $this->assignRef('order_language', $order_language);
        }



        $this->useSSL = VmConfig::get('useSSL', 0);
        $this->useXHTML = false;

        $this->assignRef('totalInPaymentCurrency', $totalInPaymentCurrency);
        $this->assignRef('checkoutAdvertise', $checkoutAdvertise);

        //echo '<pre>'; print_r($this); die;
        //We never want that the cart is indexed
        $document->setMetaData('robots', 'NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET');

        if ($this->cart->_inConfirm)
            vmInfo('COM_VIRTUEMART_IN_CONFIRM');

        $current = JFactory::getUser();
        $this->allowChangeShopper = false;
        $this->adminID = false;
        if (VmConfig::get('oncheckout_change_shopper')) {
            if ($current->authorise('core.admin', 'com_virtuemart') or $current->authorise('vm.user', 'com_virtuemart')) {
                $this->allowChangeShopper = true;
            } else {
                $this->adminID = JFactory::getSession()->get('vmAdminID', false);
                if ($this->adminID) {
                    if (!class_exists('vmCrypt'))
                        require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmcrypt.php');
                    $this->adminID = vmCrypt::decrypt($this->adminID);
                    $adminIdUser = JFactory::getUser($this->adminID);
                    if ($adminIdUser->authorise('core.admin', 'com_virtuemart') or $adminIdUser->authorise('vm.user', 'com_virtuemart')) {
                        $this->allowChangeShopper = true;
                    }
                }
            }
        }
        if ($this->allowChangeShopper) {
            $this->userList = $this->getUserList();
        }

        parent::display($tpl);
    }

    private function lSelectCoupon() {

        $this->couponCode = (!empty($this->cart->couponCode) ? $this->cart->couponCode : '');
        $this->coupon_text = $this->cart->couponCode ? vmText::_('COM_VIRTUEMART_COUPON_CODE_CHANGE') : vmText::_('COM_VIRTUEMART_COUPON_CODE_ENTER');
    }

    /**
     * lSelectShipment
     * find al shipment rates available for this cart
     *
     * @author Valerie Isaksen
     */
    private function lSelectShipment() {
        $found_shipment_method = false;
        $shipment_not_found_text = vmText::_('COM_VIRTUEMART_CART_NO_SHIPPING_METHOD_PUBLIC');
        $this->assignRef('shipment_not_found_text', $shipment_not_found_text);
        $this->assignRef('found_shipment_method', $found_shipment_method);

        $shipments_shipment_rates = array();
        if (!$this->checkShipmentMethodsConfigured()) {
            $this->assignRef('shipments_shipment_rates', $shipments_shipment_rates);
            return;
        }

        $selectedShipment = (empty($this->cart->virtuemart_shipmentmethod_id) ? 0 : $this->cart->virtuemart_shipmentmethod_id);

        $shipments_shipment_rates = array();
        if (!class_exists('vmPSPlugin'))
            require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
        JPluginHelper::importPlugin('vmshipment');
        $dispatcher = JDispatcher::getInstance();

        $returnValues = $dispatcher->trigger('plgVmDisplayListFEShipment', array($this->cart, $selectedShipment, &$shipments_shipment_rates));
        // if no shipment rate defined
        $found_shipment_method = count($shipments_shipment_rates);

        $ok = true;
        if ($found_shipment_method == 0) {
            $validUserDataBT = $this->cart->validateUserData();

            if ($validUserDataBT === -1) {
                if (VmConfig::get('oncheckout_opc', 1)) {
                    vmdebug('lSelectShipment $found_shipment_method === 0 show error');
                    $ok = false;
                } else {
                    $mainframe = JFactory::getApplication();
                    $mainframe->redirect(JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT'), vmText::_('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST'));
                }
            }
        }
        if (empty($selectedShipment)) {
            if ($s_id = VmConfig::get('set_automatic_shipment', false)) {
                $j = 'radiobtn = document.getElementById("shipment_id_' . $s_id . '");
				if(radiobtn!==null){ radiobtn.checked = true;}';
                vmJsApi::addJScript('autoShipment', $j);
            }
        }

        $shipment_not_found_text = vmText::_('COM_VIRTUEMART_CART_NO_SHIPPING_METHOD_PUBLIC');
        $this->assignRef('shipment_not_found_text', $shipment_not_found_text);
        $this->assignRef('shipments_shipment_rates', $shipments_shipment_rates);
        $this->assignRef('found_shipment_method', $found_shipment_method);

        return $ok;
    }

    /*
     * lSelectPayment
     * find al payment available for this cart
     *
     * @author Valerie Isaksen
     */

    private function lSelectPayment() {

        $this->payment_not_found_text = '';
        $this->payments_payment_rates = array();

        $this->found_payment_method = 0;
        $selectedPayment = empty($this->cart->virtuemart_paymentmethod_id) ? 0 : $this->cart->virtuemart_paymentmethod_id;

        $this->paymentplugins_payments = array();
        if (!$this->checkPaymentMethodsConfigured()) {
            return;
        }

        if (!class_exists('vmPSPlugin'))
            require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
        JPluginHelper::importPlugin('vmpayment');
        $dispatcher = JDispatcher::getInstance();

        $returnValues = $dispatcher->trigger('plgVmDisplayListFEPayment', array($this->cart, $selectedPayment, &$this->paymentplugins_payments));

        $this->found_payment_method = count($this->paymentplugins_payments);
        if (!$this->found_payment_method) {
            $link = ''; // todo
            $this->payment_not_found_text = vmText::sprintf('COM_VIRTUEMART_CART_NO_PAYMENT_METHOD_PUBLIC', '<a href="' . $link . '" rel="nofollow">' . $link . '</a>');
        }

        $ok = true;
        if ($this->found_payment_method == 0) {
            $validUserDataBT = $this->cart->validateUserData();
            if ($validUserDataBT === -1) {
                if (VmConfig::get('oncheckout_opc', 1)) {
                    $ok = false;
                } else {
                    $mainframe = JFactory::getApplication();
                    $mainframe->redirect(JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT'), vmText::_('COM_VIRTUEMART_CART_ENTER_ADDRESS_FIRST'));
                }
            }
        }

        if (empty($selectedPayment)) {
            if ($p_id = VmConfig::get('set_automatic_payment', false)) {
                $j = 'radiobtn = document.getElementById("payment_id_' . $p_id . '");
				if(radiobtn!==null){ radiobtn.checked = true;}';
                vmJsApi::addJScript('autoPayment', $j);
            }
        }
        return $ok;
    }

    private function getTotalInPaymentCurrency() {

        if (empty($this->cart->virtuemart_paymentmethod_id)) {
            return null;
        }

        if (!$this->cart->paymentCurrency or ( $this->cart->paymentCurrency == $this->cart->pricesCurrency)) {
            return null;
        }

        $paymentCurrency = CurrencyDisplay::getInstance($this->cart->paymentCurrency);
        $totalInPaymentCurrency = $paymentCurrency->priceDisplay($this->cart->cartPrices['billTotal'], $this->cart->paymentCurrency);
        $this->currencyDisplay = CurrencyDisplay::getInstance($this->cart->pricesCurrency);

        return $totalInPaymentCurrency;
    }

    /*
     * Trigger to place Coupon, payment, shipment advertisement on the cart
     */

    private function getCheckoutAdvertise() {
        $checkoutAdvertise = array();
        JPluginHelper::importPlugin('vmextended');
        JPluginHelper::importPlugin('vmcoupon');
        JPluginHelper::importPlugin('vmshipment');
        JPluginHelper::importPlugin('vmpayment');
        JPluginHelper::importPlugin('vmuserfield');
        $dispatcher = JDispatcher::getInstance();
        $returnValues = $dispatcher->trigger('plgVmOnCheckoutAdvertise', array($this->cart, &$checkoutAdvertise));
        return $checkoutAdvertise;
    }

    private function lOrderDone() {
        $this->display_title = vRequest::getBool('display_title', true);
        //Do not change this. It contains the payment form
        $this->html = vRequest::get('html', vmText::_('COM_VIRTUEMART_ORDER_PROCESSED'));
        //Show Thank you page or error due payment plugins like paypal express
    }

    private function checkPaymentMethodsConfigured() {

        //For the selection of the payment method we need the total amount to pay.
        $paymentModel = VmModel::getModel('Paymentmethod');
        $payments = $paymentModel->getPayments(true, false);
        if (empty($payments)) {

            $text = '';
            $user = JFactory::getUser();
            if ($user->authorise('core.admin', 'com_virtuemart') or $user->authorise('core.manage', 'com_virtuemart') or VmConfig::isSuperVendor()) {
                $uri = JFactory::getURI();
                $link = $uri->root() . 'administrator/index.php?option=com_virtuemart&view=paymentmethod';
                $text = vmText::sprintf('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED_LINK', '<a href="' . $link . '" rel="nofollow">' . $link . '</a>');
            }

            vmInfo('COM_VIRTUEMART_NO_PAYMENT_METHODS_CONFIGURED', $text);
            $this->cart->virtuemart_paymentmethod_id = 0;
            return false;
        }
        return true;
    }

    private function checkShipmentMethodsConfigured() {

        //For the selection of the shipment method we need the total amount to pay.
        $shipmentModel = VmModel::getModel('Shipmentmethod');
        $shipments = $shipmentModel->getShipments();
        if (empty($shipments)) {

            $text = '';
            $user = JFactory::getUser();
            if ($user->authorise('core.admin', 'com_virtuemart') or $user->authorise('core.manage', 'com_virtuemart') or VmConfig::isSuperVendor()) {
                $uri = JFactory::getURI();
                $link = $uri->root() . 'administrator/index.php?option=com_virtuemart&view=shipmentmethod';
                $text = vmText::sprintf('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED_LINK', '<a href="' . $link . '" rel="nofollow">' . $link . '</a>');
            }

            vmInfo('COM_VIRTUEMART_NO_SHIPPING_METHODS_CONFIGURED', $text);

            $tmp = 0;
            $this->assignRef('found_shipment_method', $tmp);
            $this->cart->virtuemart_shipmentmethod_id = 0;
            return false;
        }
        return true;
    }

    /**
     * Todo, works only for small stores, we need a new solution there with a bit filtering
     * For example by time, if already shopper, and a simple search
     * @return object list of users
     */
    function getUserList() {

        $result = false;

        if ($this->allowChangeShopper) {
            $this->adminID = JFactory::getSession()->get('vmAdminID', false);
            if ($this->adminID) {
                if (!class_exists('vmCrypt'))
                    require(VMPATH_ADMIN . DS . 'helpers' . DS . 'vmcrypt.php');
                $this->adminID = vmCrypt::decrypt($this->adminID);
            }
            $superVendor = VmConfig::isSuperVendor($this->adminID);
            if ($superVendor) {
                $uModel = VmModel::getModel('user');
                $result = $uModel->getSwitchUserList($superVendor, $this->adminID);
            }
        }
        if (!$result)
            $this->allowChangeShopper = false;
        return $result;
    }

    function renderCompleteAddressList() {

        $addressList = false;

        if ($this->cart->user->virtuemart_user_id) {
            $addressList = array();
            $newBT = '<a href="index.php'
                    . '?option=com_virtuemart'
                    . '&view=user'
                    . '&task=editaddresscart'
                    . '&addrtype=BT'
                    . '">' . vmText::_('COM_VIRTUEMART_ACC_BILL_DEF') . '</a></br>';
            foreach ($this->cart->user->userInfo as $userInfo) {
                $address = $userInfo->loadFieldValues(false);
                if ($address->address_type == 'BT') {
                    $address->virtuemart_userinfo_id = 0;
                    $address->address_type_name = $newBT;
                    array_unshift($addressList, $address);
                } else {
                    $address->address_type_name = '<a href="index.php'
                            . '?option=com_virtuemart'
                            . '&view=user'
                            . '&task=editaddresscart'
                            . '&addrtype=ST'
                            . '&virtuemart_userinfo_id=' . $address->virtuemart_userinfo_id
                            . '" rel="nofollow">' . $address->address_type_name . '</a></br>';
                    $addressList[] = $address;
                }
            }
            if (count($addressList) == 0) {
                $addressList[0] = new stdClass();
                $addressList[0]->virtuemart_userinfo_id = 0;
                $addressList[0]->address_type_name = $newBT;
            }

            $_selectedAddress = (
                    empty($this->cart->selected_shipto) ? $addressList[0]->virtuemart_userinfo_id // Defaults to 1st BillTo
                            : $this->cart->selected_shipto
                    );

            $this->cart->lists['shipTo'] = JHtml::_('select.radiolist', $addressList, 'shipto', null, 'virtuemart_userinfo_id', 'address_type_name', $_selectedAddress);
            $this->cart->lists['billTo'] = empty($addressList[0]->virtuemart_userinfo_id) ? 0 : $addressList[0]->virtuemart_userinfo_id;
        } else {
            $this->cart->lists['shipTo'] = false;
            $this->cart->lists['billTo'] = false;
        }
    }

    static public function addCheckRequiredJs() {
        $j = 'jQuery(document).ready(function(){

    jQuery(".output-shipto").find(":radio").change(function(){
        var form = jQuery("#checkoutFormSubmit");
        jQuery(this).vm2front("startVmLoading");
		document.checkoutForm.submit();
    });
    jQuery(".required").change(function(){
    	var count = 0;
    	var hit = 0;
    	jQuery.each(jQuery(".required"), function (key, value){
    		count++;
    		if(jQuery(this).attr("checked")){
        		hit++;
       		}
    	});
        if(count==hit){
        	jQuery(this).vm2front("startVmLoading");
        	var form = jQuery("#checkoutFormSubmit");
        	//document.checkoutForm.task = "checkout";
			document.checkoutForm.submit();
        }
    });
});';
        vmJsApi::addJScript('autocheck', $j);
    }

}

//no closing tag
