<?php
/*
 * @title		VM - Custom, Downloadable Products
 * @version		3.3d
 * @package		Joomla
 * @author		ekerner@ekerner.com.au
 * @website		http://www.ekerner.com.au
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * @copyright		Copyright (C) 2012 - 2014 eKerner.com.au All rights reserved.
 */

defined('_JEXEC') or die('Permission denied!') ;
class_exists('vmCustomPlugin') or require(JPATH_VM_PLUGINS.DS.'vmcustomplugin.php');

class plgVmCustomDownloadable extends vmCustomPlugin 
{

	protected $debug = false;
	protected $is_VM3 = false;

	public function __construct(& $subject, $config) 
	{

		parent::__construct($subject, $config);
		$varsToPush = array(
			'downloadable_media_id' => array(0, 'int'),
			'downloadable_order_states' => array(array('C','S'), 'char'),
			'downloadable_expires_quantity' => array(0, 'int'),
			'downloadable_expires_period' => array('years', 'string')
		);
		$this->is_VM3 = (float)substr(VmConfig::getInstalledVersion(), 0, 3) > 2.6;
		//$this->setConfigParameterable($this->is_VM3 ? 'customfield_params' : 'custom_params',$varsToPush);
		$this->setConfigParameterable('custom_params',$varsToPush);
		if(JFactory::getApplication()->input->get('view')=='productdetails')
		if ($this->params->get('show_in_cart_attrs', 1) && JFactory::getApplication()->input->get('view') == 'productdetails')
			JFactory::getDocument()->addScriptDeclaration("
				window.addEvent('domready', function(){
					jQuery('.vmshipment_downloadable_delivery_div').parent().parent().clone().css('margin-top', '10px').appendTo('.spacer-buy-area');
				});
			");
	}

	protected function makeErrorDiv($msg)
	{
		return '<div style="color:#FF0000">'.$msg.'</div>';
	}

	public function plgVmOnProductEdit($field, $product_id, &$row, &$retValue) 
	{
		if ($field->custom_element != $this->_name) return '';
		if (!$this->is_VM3)
			$this->parseCustomParams($field);
		// make sure vm config is OK ...
		if (!VmConfig::get('oncheckout_only_registered')) {
			$retValue .= $this->makeErrorDiv(JText::_('VMCUSTOM_DOWNLOADABLE_SET_ONLYREGISTERED'));
			return true;
		}
		// load the downloadable media ...
		$db = JFactory::getDBO();
		$db->setQuery('SELECT virtuemart_media_id, file_title, file_url FROM #__virtuemart_medias WHERE file_is_forSale = 1 AND published = 1');
		$result = $db->loadAssocList();
		$downloadable_files = array();
		foreach ($result as $file)
			if (file_exists($file['file_url']))
				$downloadable_files[] = $file;
		if (!$downloadable_files) {
			$retValue .= $this->makeErrorDiv(JText::_('VMCUSTOM_DOWNLOADABLE_NO_MEDIA'));
			return true;
		}
		// load the order ststuses ...
		$db->setQuery('SELECT order_status_code, order_status_name FROM #__virtuemart_orderstates');
		$order_states = $db->loadAssocList();
		if (!$order_states) {
			$retValue .= $this->makeErrorDiv(JText::_('VMCUSTOM_DOWNLOADABLE_NO_STATUS'));
			return true;
		}
		// build the html ....
		jimport('joomla.html.html.select');
		$name_prefix = (!$this->is_VM3) ? 'custom_param['.$row.']' : 'customfield_params['.$row.']';
		$retValue .= '<div>';
		// the media select elem ...
		$label = JText::_('VMCUSTOM_DOWNLOADABLE_SELECT_MEDIA');
		$retValue .= '<div>'.$label.': -<br />';
		$options = array(JHtml::_('select.option', 0, $label));
		foreach ($downloadable_files as $file)
			$options[] = JHtml::_('select.option', $file['virtuemart_media_id'], $file['file_title']);
		$retValue .= JHtml::_('select.genericlist', $options, $name_prefix.'[downloadable_media_id]', null, 'value', 'text', $field->downloadable_media_id);
		$retValue .= '</div>';
		// the status select elem ...
		$label = JText::_('VMCUSTOM_DOWNLOADABLE_SELECT_STATUS');
		$retValue .= '<div style="margin-top:10px;">'.$label.': -<br />';
		$options = array();
		foreach ($order_states as $status)
			$options[] = JHtml::_('select.option', $status['order_status_code'], JText::_($status['order_status_name']));
		$selected = !$field->downloadable_order_states ? array('C', 'S') : $field->downloadable_order_states;
		$retValue .= JHtml::_('select.genericlist', $options, $name_prefix.'[downloadable_order_states][]', 'multiple="multiple"', 'value', 'text', $selected);
		$retValue .= '</div>';
		// the expiry select elems ...
		$retValue .= '<div style="margin-top:10px;">'.JText::_('VMCUSTOM_DOWNLOADABLE_SELECT_EXPIRY').': -<br />';
		$options = array(JHtml::_('select.option', 0, '&infin;'));
		foreach (range(1, 11) as $quantity)
			$options[] = JHtml::_('select.option', $quantity, $quantity);
		$retValue .= JHtml::_('select.genericlist', $options, $name_prefix.'[downloadable_expires_quantity]', null, 'value', 'text', $field->downloadable_expires_quantity);
		$retValue .= '<br />';
		$options = array();
		foreach (array('days', 'weeks', 'months', 'years') as $period)
			$options[] = JHtml::_('select.option', $period, $period);
		$retValue .= JHtml::_('select.genericlist', $options, $name_prefix.'[downloadable_expires_period]', null, 'value', 'text', $field->downloadable_expires_period);
		$retValue .= '</div></div>';
		return true;
	}

        public function plgVmOnDisplayProductFEVM3(&$product, &$group) 
	{
		$idx = 0;
                $this->plgVmOnDisplayProductFE($product, $idx, $group);
        }

	public function plgVmOnDisplayProductFE($product, &$idx, &$group) 
	{
		if ($group->custom_element != $this->_name) return '';
		if (!empty($group->custom_params) && substr($group->custom_params, 0, 1) != '{')
			$group->custom_params = '';
		$this->getCustomParams($group);
		$user = JFactory::getUser();
		$group->display .=  '<div class="vmshipment_downloadable_delivery_div">';
		// is the user logged ...
		if ($user->guest)
			$group->display .= JText::_('VMCUSTOM_DOWNLOADABLE_NOT_LOGGED');
		else {
			$db = JFactory::getDBO();
			// is the media still available ...
			$db->setQuery("SELECT file_url FROM #__virtuemart_medias WHERE virtuemart_media_id = {$this->params->downloadable_media_id} AND file_is_forSale = 1 AND published = 1 LIMIT 1");
			$file_url = $db->loadResult();
			if (!($file_url && file_exists($file_url)))
				// media no longer availbale ...
				$group->display .= JText::_('VMCUSTOM_DOWNLOADABLE_NOT_AVAILABLE');
			else {
				// has the user purchased this product ...
				$order_states = is_array($this->params->downloadable_order_states) ? implode("', '", $this->params->downloadable_order_states) : $this->params->downloadable_order_states;
				$db->setQuery("
					SELECT	o.created_on
					FROM 	#__virtuemart_orders as o,
						#__virtuemart_order_items as i
					WHERE	o.virtuemart_user_id = {$user->id}
					AND	o.order_status IN ('{$order_states}')
					AND	i.virtuemart_order_id = o.virtuemart_order_id
					AND	i.virtuemart_product_id = {$product->virtuemart_product_id}
					ORDER	BY o.created_on DESC
					LIMIT	1
				");
				$purchased = $db->loadResult();
				if (!$purchased)
					// user has not purchased product ...
					$group->display .= JText::_('VMCUSTOM_DOWNLOADABLE_NOT_PURCHASED');
				else {
					// user has purchased product, has the link expired ...
					$expiry_date = null;
					$purchased_time = strtotime($purchased);
					if ($this->debug) $group->display .= '<br/>purchased = ' . $purchased. '<br/>purchased_time = ' . $purchased_time;
					if ($this->params->downloadable_expires_quantity && $purchased_time > 0) {
						// is expirable ...
						$expiry_str = "+{$this->params->downloadable_expires_quantity} {$this->params->downloadable_expires_period}";
						$expiry_time = strtotime($expiry_str, $purchased_time);
						if (time() < $expiry_time)
							$expiry_date = date('Y-m-d H:i:s', $expiry_time);
						if ($this->debug) $group->display .= '<br/>expiry_str = ' . $expiry_str. '<br/>expiry_time = ' . $expiry_time . '<br/>expiry_date = ' . $expiry_date . '<br/>time() = ' . time() . '<br/>';

					}
					if ($this->params->downloadable_expires_quantity && !$expiry_date)
						// link has expired ...
						$group->display .= JText::_('VMCUSTOM_DOWNLOADABLE_LINK_EXPIRED');
					else {
						// render per-session download link ...
						$session = JFactory::getSession();
						$session_key = 'vmdownloadable_'.$user->id.'_'.$product->virtuemart_product_id;
						$session->set($session_key, $file_url);
						$plugin_url = JURI::root() . 'plugins/vmcustom/downloadable/';
						$url = $plugin_url . 'deliver_media.php?uid='.$user->id.'&amp;pid='.$product->virtuemart_product_id;
						$lbl = JText::_('VMCUSTOM_DOWNLOADABLE_DOWNLOAD_LINK');
						$img_url = $plugin_url . 'images/download_button.png';
						$group->display .= '<a title="'.$lbl.'" href="'.$url.'" onclick="window.open(this.href); return false;"><img alt="' . $lbl . '" src="' . $img_url . '" style="border:0; display:block;" /></a>';
						if ($expiry_date) {
							$expires_period = ($this->params->downloadable_expires_quantity == 1) ? substr($this->params->downloadable_expires_period, 0, -1) : $this->params->downloadable_expires_period;
							$group->display .= JText::sprintf('VMCUSTOM_DOWNLOADABLE_DOWNLOAD_EXPIRY', $expiry_date, $this->params->downloadable_expires_quantity, $expires_period);
						}
					}
				}
			}
		}
		$group->display .= '</div>';
		return true;
	}

	public function plgVmOnStoreInstallPluginTable($psType,$name) 
	{
		return $this->onStoreInstallPluginTable($psType,$name);
	}
	/*
	public function plgVmOnStoreInstallPluginTable($psType,$data,$table) {
		if(empty($table->custom_element) or (!empty($table->custom_element) and $table->custom_element!=$this->_name) ){
			return false;
		}
		if(empty($table->is_input)){
			vmInfo('COM_VIRTUEMART_CUSTOM_IS_CART_INPUT_SET');
			$table->is_input = 1;
			$table->store();
		}
		//Should the textinput use an own internal variable or store it in the params?
		//Here is no getVmPluginCreateTableSQL defined
 		//return $this->onStoreInstallPluginTable($psType);
	}
	*/

	public function plgVmDeclarePluginParamsCustomVM3(&$data)
	{
		return $this->declarePluginParams('custom', $data);
	}

	public function plgVmGetTablePluginParams($psType, $name, $id, &$xParams, &$varsToPush){
		return $this->getTablePluginParams($psType, $name, $id, $xParams, $varsToPush);
	}

	public function plgVmSetOnTablePluginParamsCustom($name, $id, &$table,$xParams){
		return $this->setOnTablePluginParams($name, $id, $table,$xParams);
	}

	public function plgVmOnDisplayEdit($virtuemart_custom_id,&$customPlugin)
	{
		return $this->onDisplayEditBECustom($virtuemart_custom_id,$customPlugin);
	}

	/* the following only applied when the plugin was a cart variant
	public function plgVmDisplayInOrderFE($item, $row, &$html) 
	{
		if (empty($item->productCustom->custom_element) || $item->productCustom->custom_element != $this->_name) 
			return '';
		$url = JURI::root() . 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $item->virtuemart_product_id;
		$attrs = array(
			'id'=> 'ek_download_link_' . $item->virtuemart_product_id, 
			'class'=> 'ek_download_link',
			'title' => JText::_('VMCUSTOM_DOWNLOADABLE_VISIT_DOWNLOAD_PAGE')
		);
		$html .= '<div class="ek_download_div"><b>' . JText::_('VMCUSTOM_DOWNLOADABLE_DOWNLOADABLE') . '</b><br/>' .
			 JHTML::link($url, JText::_('VMCUSTOM_DOWNLOADABLE_DOWNLOAD_PAGE'), $attrs) . '</div>';
		return true;
	}

	public function plgVmDisplayInOrderBE($item, $row, &$html) 
	{
		return $this->plgVmDisplayInOrderFE($item, $row, $html);
	}
	*/

}
// No closing tag
