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

$my = JFactory::getUser();
if(empty($my->id)) die('You can not have access to this page');

include(ACYMAILING_BACK.'controllers'.DS.'editor.php');

class FronteditorController extends EditorController{

}
