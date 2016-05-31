<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.8.1
 * @author	acyba.com
 * @copyright	(C) 2009-2014 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

acymailing_get('controller.newsletter');
class AutonewsController extends NewsletterController{
	var $aclCat = 'autonewsletters';
	function generate(){
		if(!$this->isAllowed('autonewsletters','manage')) return;
		$app = JFactory::getApplication();

		$autoNewsHelper = acymailing_get('helper.autonews');
		if(!$autoNewsHelper->generate()){
			$app->enqueueMessage(JText::_('NO_AUTONEWSLETTERS'),'notice');
			$db = JFactory::getDBO();
			$db->setQuery("SELECT * FROM ".acymailing_table('mail')." WHERE `type` = 'autonews'");
			$allAutonews = $db->loadObjectList();
			if(!empty($allAutonews)){
				$time = time();
				foreach($allAutonews as $oneAutonews){
					if(($oneAutonews->published != 1)){
						$app->enqueueMessage(JText::sprintf('AUTONEWS_NOT_PUBLISHED','<b><i>'.$oneAutonews->subject.'</i></b>'),'notice');
					}elseif($oneAutonews->senddate >= $time){
						$app->enqueueMessage(JText::sprintf('AUTONEWS_NOT_READY','<b><i>'.$oneAutonews->subject.'</i></b>'),'notice');
					}
				}
			}

		}else{
			foreach($autoNewsHelper->messages as $oneMessage){
				$app->enqueueMessage($oneMessage);
			}
		}

		return $this->listing();
	}
}
