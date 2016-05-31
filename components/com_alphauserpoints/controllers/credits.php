<?php

/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

/**
 * @package AlphaUserPoints
 */
class alphauserpointsControllerCredits extends JControllerLegacy {

    public function processPayment() {
        JSession::checkToken() or die('Invalid Token');
        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();


        $amt = $post["amt"];

        $nonce = $post["payment_method_nonce"];

        $model = $this->getModel('credits');

        $model->processPayment($amt, $nonce);
    }

    public function processCartPayment() {
        JSession::checkToken() or die('Invalid Token');
        $user = JFactory::getUser();

        $uKey = $user->id . "." . $user->name;

        $app = JFactory::getApplication();

        $state = $app->getUserState($uKey, 0);

        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();

        if (!empty($state)) {
            $orderId = $state['virtuemart_order_id'];

            $amt = $post["amt"];

            $nonce = $post["payment_method_nonce"];

            $model = $this->getModel('credits');

            $model->processPaymentCart($amt, $nonce, $orderId);

            $state = $app->setUserState($uKey, null);
        } else {
            $app->redirect(JRoute::_(JUri::root() . "index.php?option=com_virtuemart&view=virtuemart&productsublayout=products_horizon&Itemid=199"), "Error processing order.", "error");
        }
    }

}

?>