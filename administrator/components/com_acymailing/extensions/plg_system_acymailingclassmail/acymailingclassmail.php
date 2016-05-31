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
class plgSystemAcymailingClassMail extends JPlugin {
	public function __construct(&$subject, $config = array()) {
		$file = rtrim(JPATH_SITE,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR.'joomlanotification'.DIRECTORY_SEPARATOR.'mail.php';
		try{
			require_once($file);
		}catch(Exception $e) {
 			echo "Could not load Acymailing JMail class at $file,<br />please disable the acymailingclassmail plugin (Override Joomla mailing system plugin)";
 		}
		parent::__construct($subject, $config);
	}
}
