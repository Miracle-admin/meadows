<?php
/**
 *
 * Realex payment plugin
 *
 * @author Valerie Isaksen
 * @version $Id$
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
defined('JPATH_BASE') or die();

jimport('joomla.form.formfield');
class JFormFieldGetAlphauserpoints extends JFormField {

	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	var $type = 'getAlphauserpoints';

	protected function getInput() {

		JHtml::_('behavior.colorpicker');

		vmJsApi::addJScript( '/plugins/vmpayment/Alphauserpoints/Alphauserpoints/assets/js/admin.js');
		vmJsApi::css('Alphauserpoints', 'plugins/vmpayment/Alphauserpoints/Alphauserpoints/assets/css/');

		$url = "https://www.Alphauserpoints.com/us/webapps/mpp/referral/Alphauserpoints-payments-standard?partner_id=83EP5DJG9FU6L";
		$logo = '<img src="https://www.Alphauserpointsobjects.com/en_US/i/logo/Alphauserpoints_mark_60x38.gif" />';
		$html = '<p><a target="_blank" href="' . $url . '"  >' . $logo . '</a></p>';
		$html .= '<p><a target="_blank" href="' . $url . '" class="signin-button-link">' . vmText::_('VMPAYMENT_ALPHAUSERPOINTS_REGISTER') . '</a>';
		$html .= ' <a target="_blank" href="http://docs.virtuemart.net/manual/shop-menu/payment-methods/Alphauserpoints.html" class="signin-button-link">' . vmText::_('VMPAYMENT_ALPHAUSERPOINTS_DOCUMENTATION') . '</a></p>';

		return $html;
	}

}