<?php
/*
 * @component AlphaUserPoints
 * @copyright Copyright (C) 2008-2015 Bernard Gilly
 * @license : GNU/GPL
 * @Website : http://www.alphaplug.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


class JTableRaffle_inscriptions extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	/** @var int */
	var $raffleid = '';
	/** @var int */
	var $userid = '';
	/** @var string */
	var $ticket = '';
    var $referredraw = '';
	/** @var datetime */
    var $inscription = '';
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__alpha_userpoints_raffle_inscriptions', 'id', $db);
	}
}
?>