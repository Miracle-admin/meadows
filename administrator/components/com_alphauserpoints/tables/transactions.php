<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	12 March 2012
 * @file name	:	tables/project.php
 * @copyright   :	Copyright (C) 2012 - 2015 BriTech Solutions. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Class for table (jblance)
 */
defined('_JEXEC') or die( 'Restricted access' );

class TableTransactions extends JTable {
var $id                      =   null;
var $amt              =   null;
var $trans_id              =   null;
var $card_type              =   null;
var $uid              =   null;
var $transDate        = null;
		
	/**
	* @param database A database connector object
	*/
	function __construct(&$db){
		parent::__construct( '#__alpha_userpoints_transactions', array('id','uid'), $db);
		
	}
}

?>