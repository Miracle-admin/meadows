<?php

/**
 * @copyright	Copyright (C) 2014 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.form');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
// error_reporting(0);

class JFormFieldCkmaximenuchecking extends JFormField {

	protected $type = 'ckmaximenuchecking';

	protected function getLabel() {
		$imgpath = JUri::root(true) . '/modules/mod_maximenuck/elements/images/';
		$js_checking = '';

		// check if the maximenu params component is installed
		if ( file_exists(JPATH_ROOT . '/administrator/components/com_maximenuck/maximenuck.php') ) {
			$com_params_text = '<img src="' . $imgpath . 'accept.png"  />' . JText::_('MOD_MAXIMENUCK_COMPONENT_PARAMS_INSTALLED');

			$js_checking = '<script>
			jQuery(document).ready(function (){
				jQuery(\'.maximenuckchecking\').each(function(i ,el){
				if (jQuery(el).attr(\'data-name\')) {
					jQuery.ajax({
						type: "POST",
						url: \'' . JUri::root(true) . '/administrator/index.php?option=com_maximenuck&task=check_update\',
						data: {
							name: jQuery(el).attr(\'data-name\'),
							type: jQuery(el).attr(\'data-type\'),
							folder: jQuery(el).attr(\'data-folder\')
						}
					}).done(function(response) {
						response = response.trim();
						if ( response.substring(0,7).toLowerCase() == \'error\' ) {
							alert(response);
							// show_ckmodal(response);
						} else {
							jQuery(el).append(response);
						}
					}).fail(function() {
						//alert(Joomla.JText._(\'CK_FAILED\', \'Failed\'));
					});
				}
				});
			});
		</script>';
		} else {
			$com_params_text = '<img src="' . $imgpath . 'cross.png"  />' . JText::_('MOD_MAXIMENUCK_COMPONENT_PARAMS_NOT_INSTALLED');
		}

		// check if the maximenu params plugin (old method) is installed
		if ( file_exists(JPATH_ROOT . '/plugins/system/maximenuckparams/maximenuckparams.php') ) {
			require_once(JPATH_ROOT . '/plugins/system/maximenuckparams/maximenuckparams.php');
			if (! method_exists('plgSystemMaximenuckparams', 'check_version') ){
				$plugin_params_text = '<img src="' . $imgpath . 'exclamation.png"  />' . JText::_('MOD_MAXIMENUCK_PLUGIN_PARAMS_INSTALLED_BUT_OBSOLETE');
			} else {
				$plugin_params_text = '<img src="' . $imgpath . 'accept.png"  />' . JText::_('MOD_MAXIMENUCK_PLUGIN_PARAMS_INSTALLED');
				if (! JPluginHelper::isEnabled('system', 'maximenuckparams') ) {
					$plugin_params_text .= '<img src="' . $imgpath . 'error.png"  />' . '<a href="index.php?option=com_plugins&filter_folder=system&filter_search=maximenu" class="modal" rel="{handler: \'iframe\', size: {x: 900, y: 550}}">' . JText::_('MOD_MAXIMENUCK_ACTIVATE_PLUGIN') . '</a>';
				}
			}
		} else {
			$plugin_params_text = '<img src="' . $imgpath . 'cross.png"  />' . JText::_('MOD_MAXIMENUCK_PLUGIN_PARAMS_NOT_INSTALLED');
		}

		// check if the maximenu mobile plugin is installed
		if ( file_exists(JPATH_ROOT . '/plugins/system/maximenuckmobile/maximenuckmobile.php') ) {
			$plugin_mobile_text = '<img src="' . $imgpath . 'accept.png"  />' . JText::_('MOD_MAXIMENUCK_PLUGIN_MOBILE_INSTALLED');
			if (! JPluginHelper::isEnabled('system', 'maximenuckmobile') ) {
				$plugin_mobile_text .= '<img src="' . $imgpath . 'error.png"  />' . '<a href="' . JUri::root(true) . '/administrator/index.php?option=com_plugins&filter_folder=system&filter_search=maximenu" class="modal" rel="{handler: \'iframe\', size: {x: 900, y: 550}}">' . JText::_('MOD_MAXIMENUCK_ACTIVATE_PLUGIN') . '</a>';
			}
		} else {
			$plugin_mobile_text = '<img src="' . $imgpath . 'cross.png"  />' . JText::_('MOD_MAXIMENUCK_PLUGIN_MOBILE_NOT_INSTALLED');
		}

		// to inform that you can add some themes
		$themes_text = '<img src="' . $imgpath . 'information.png"  />' . JText::_('MAXIMENUCK_WIZARD_MENU_DOWNLOAD_THEMES');

		$html = '<style>.maximenuckchecking {background:#efefef;border: none;
    border-radius: 3px;
    color: #333;
    font-weight: normal;
	line-height: 24px;
    padding: 5px;
	margin: 3px 0;
    text-align: left;
    text-decoration: none;
    }
	.maximenuckchecking img {
	margin: 5px;
    }</style>';
		$html .= '<div class="maximenuckchecking" data-name="maximenuck" data-type="component" data-folder="">' . $com_params_text . '</div>';
		$html .= '<div class="maximenuckchecking" data-name="maximenuckparams" data-type="plugin" data-folder="system">' . $plugin_params_text . '</div>';
		$html .= '<div class="maximenuckchecking" data-name="maximenuckmobile" data-type="plugin" data-folder="system">' . $plugin_mobile_text . '</div>';
		$html .= '<div class="maximenuckchecking">' . $themes_text . '</div>';

		$html .= $js_checking;
		return $html;
	}

	protected function getInput() {

		return '';
	}
}

