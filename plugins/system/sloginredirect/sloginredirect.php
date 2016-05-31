<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 June 2012
 * @file name	:	plugins/system/jblanceredirect/jblanceredirect.php
 * @copyright   :	Copyright (C) 2012 - 2014 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.plugin.plugin');

class plgSystemSloginRedirect extends JPlugin {


	function onAfterRoute(){
		$app =JFactory::getApplication();

		$link = JRoute::_('index.php?option=com_jblance&view=developer&layout=register&Itemid=347', false);
		$app->redirect($link);
		return;
		
	}
}
